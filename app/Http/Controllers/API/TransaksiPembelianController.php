<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\TransaksiPembelianRequest;
use App\Services\API\TransaksiPembelianService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\DetTransaksiPembelian;
use Illuminate\Http\Request;
use App\TransaksiPembelian;
use App\HistoryItem;
use Carbon\Carbon;

class TransaksiPembelianController extends Controller
{

    public function index(Request $request)
    {
        $transaksi = TransaksiPembelian::select(['no_pembelian', 'supplier_id', 'tgl_pembelian', 'id'])
                        ->with(['supplier', 'det_transaksi_pembelian'])
                        ->when($request->has('no_pembelian') AND $request->no_pembelian != '', function($query) use ($request){
                            $query->where('no_pembelian', $request->no_pembelian);
                        })
                        ->when($request->has('supplier') AND $request->supplier != '', function($query) use ($request){
                            $query->where('supplier_id', $request->supplier);
                        })
                        ->when($request->has('tanggal_awal') AND $request->tanggal_awal != '' AND $request->has('tanggal_akhir') AND $request->tanggal_akhir != '', function($query) use ($request){
                            $query->whereBetween('tgl_pembelian', [$request->tanggal_awal, $request->tanggal_akhir]);
                        })
                        ->withCount([
                            'det_transaksi_pembelian AS total' => function($query){
                                $query->select(DB::raw("SUM(sub_total) as paidsum"));
                            }
                        ])
                        ->orderBy('created_at', 'desc');
        $total_pembelian = $transaksi->sum('total');
        $transaksi = $transaksi->paginate(10)->appends($request->all())->toArray();
        $transaksi['total_pembelian'] = $total_pembelian;

        return response()->json($transaksi);

    }

    public function store(TransaksiPembelianRequest $request, TransaksiPembelianService $transaksiPembelianService)
    {
        try {
            DB::beginTransaction();
            $transaksi = TransaksiPembelian::create([
                'no_pembelian' => $request->no_pembelian,
                'tgl_pembelian' => $request->tgl_pembelian,
                'supplier_id' => $request->supplier,
                'user_id' => 1,
                'termin' => $request->termin,
                'kas_id' => $request->kas,
                'total' => $request->total,
                'jumlah_hari_tempo' => $request->hari,
                'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
                'uang_muka' => $request->uang_muka,
                'keterangan' => '',
            ]);
            $det_transaksi_pembelian = collect($request->list_item)->transform(function($value, $index){
                $new = [
                    'item_id'        => $value['item'],
                    'qty'            => $value['qty'],
                    'satuan_item_id' => $value['satuan'],
                    'status_in_out'  => '1',
                    'harga'          => $value['harga'],
                    'sub_total'      => $value['harga'] * $value['qty'],
                ];
                return new DetTransaksiPembelian($new);
            });
            $transaksi->det_transaksi_pembelian()->saveMany($det_transaksi_pembelian);

            $transaksiPembelianService->insertHistoryItem($request->all(), $transaksi);
            $transaksiPembelianService->insertHistoryHutang($request->all(), $transaksi);
            $transaksiPembelianService->insertHistoryKas($request->all(), $transaksi);
            // history jurnal 1.Cash , 2.Credit
            if($request->termin == 2) {
                // pembelian secara credit
                $transaksiPembelianService->insertJurnalTransaksiPembelianCredit($request->all(), $transaksi);
                if ($request->uang_muka != "") {
                    $transaksiPembelianService->autoInsertPembayaran($request->all(), $transaksi);
                    $transaksiPembelianService->autoInsertJurnalPembayaran($request->all(), $transaksi);
                }
            } else {
                // pembelian secara cash
                $transaksiPembelianService->insertJurnalTransaksiPembelianCash($request->all(), $transaksi);
            }

            DB::commit();
            return response()->json([
                'status' => 'sukses',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 400);
        }
        return response()->json($request->all());
    }

    public function edit($id)
    {
        $transaksi =  TransaksiPembelian::with(['kas', 'supplier', 'det_transaksi_pembelian.satuan_item', 'det_transaksi_pembelian.item.satuan_item.satuan'])->findOrFail($id);
        return response()->json($transaksi);
    }

    public function update(TransaksiPembelianRequest $request, $id, TransaksiPembelianService $transaksiPembelianService)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);
        try {
            DB::beginTransaction();
            $transaksi->update([
                'no_pembelian'      => $request->no_pembelian,
                'tgl_pembelian'     => $request->tgl_pembelian,
                'supplier_id'       => $request->supplier,
                'user_id'           => 1,
                'termin'            => $request->termin,
                'kas_id'            => $request->kas,
                'total'             => $request->total,
                'jumlah_hari_tempo' => $request->hari,
                'tgl_jatuh_tempo'   => $request->tgl_jatuh_tempo,
                'uang_muka'         => $request->uang_muka,
                'keterangan'        => '',
            ]);
            $itemIds = [];
            $itemIdh = [];
            foreach ($request->list_item as $isi) {
                if(isset($isi["id"]) && $isi["id"] != ""){
                    $itemIds[] = $isi['id'];
                    $itemIdh[] = $isi['item'];

                    $transaksi->det_transaksi_pembelian()->whereId($isi["id"])->update([
                        'item_id'       => $isi["item"],
                        'satuan_item_id'=> $isi["satuan"],
                        'qty'           => $isi["qty"],
                        'harga'         => $isi["harga"],
                        'sub_total'     => $isi["qty"] * $isi["harga"],
                    ]);

                    $transaksi->history_item()
                    ->where('item_id', $isi["item"])
                    ->where('historyable_id', $transaksi->id)
                    ->where('historyable_type', 'App\TransaksiPembelian')
                    ->update([
                       'item_id'        => $isi["item"],
                       'satuan_item_id' => $isi["satuan"],
                       'qty'            => 0 - $isi["qty"],
                       'status_in_out'  => '0'
                    ]);

                } else {
                    $det_transaksi = new DetTransaksiPembelian;
                    $det_transaksi->item_id        = $isi["item"];
                    $det_transaksi->satuan_item_id = $isi["satuan"];
                    $det_transaksi->qty            = $isi["qty"];
                    $det_transaksi->harga          = $isi["harga"];
                    $det_transaksi->sub_total      = $isi["qty"] * $isi["harga"];
                    $transaksi->det_transaksi_pembelian()->save($det_transaksi);

                    $history = new HistoryItem;
                    $history->item_id        = $isi["item"];
                    $history->satuan_item_id = $isi["satuan"];
                    $history->qty            = 0 - $isi["qty"];
                    $history->status_in_out  = '0';
                    $transaksi->history_item()->save($history);

                    $itemIds[] = $det_transaksi->id;
                    $itemIdh[] = $history->item_id;
                }
            }

            $transaksi->det_transaksi_pembelian()->where('transaksi_pembelian_id', $transaksi->id)->whereNotIn('id', $itemIds)->delete();
            $transaksi->history_item()->where('historyable_id', $transaksi->id)
                        ->where('historyable_type', 'App\TransaksiPembelian')
                        ->whereNotIn('item_id', $itemIdh)->delete();

            // clear data history_hutang, history_kas, history_jurnal
            $transaksi->history_hutang()->delete();
            $transaksi->history_kas()->delete();
            $transaksi->history_jurnal()->delete();
            // insert ulang lagi berdasarkan data transaksi yg diedit
            $transaksiPembelianService->insertHistoryHutang($request->all(), $transaksi);
            $transaksiPembelianService->insertHistoryKas($request->all(), $transaksi);
            // history jurnal 1.Cash , 2.Credit
            if($request->termin == 2) {
                // pembelian secara credit
                $transaksiPembelianService->insertJurnalTransaksiPembelianCredit($request->all(), $transaksi);
                if ($request->uang_muka != "") {
                    $transaksiPembelianService->autoInsertPembayaran($request->all(), $transaksi);
                    $transaksiPembelianService->autoInsertJurnalPembayaran($request->all(), $transaksi);
                }
            } else {
                // pembelian secara cash
                $transaksiPembelianService->insertJurnalTransaksiPembelianCash($request->all(), $transaksi);
            }

            DB::commit();
            return response()->json([
                'status' => 'sukses',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 400);
        }


        return response()->json($request->all());
    }


    public function destroy($id)
    {
        $transaksi =  TransaksiPembelian::findOrFail($id);

        try {
            $transaksi->history_item()->delete();
            $transaksi->history_hutang()->delete();
            $transaksi->history_kas()->delete();
            $transaksi->history_jurnal()->delete();
            $transaksi->delete();
            return response()->json(['deleted' => true ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'message' => $e ], 400);
        }
    }

    public function get_kode_transaksi()
    {
        $check = TransaksiPembelian::select('no_pembelian', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_pembelian = $check->no_pembelian;
        } else {
            $bulan_last = date('m');
            $no_pembelian = 'PB'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'PB'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = 000+1;
            $kd = 'PB'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
