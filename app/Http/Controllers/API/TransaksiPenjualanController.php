<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\TransaksiPenjualanRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\API\TransaksiPenjualanService;
use App\DetTransaksiPenjualan;
use Illuminate\Http\Request;
use App\TransaksiPenjualan;
use Carbon\Carbon;


class TransaksiPenjualanController extends Controller
{

    public function index(Request $request)
    {
        //
    }

    public function store(TransaksiPenjualanRequest $request, TransaksiPenjualanService $transaksiPenjualanService)
    {
        // return response()->json($request->all());
        try {
            DB::beginTransaction();

            $transaksi = TransaksiPenjualan::create([
                'no_penjualan' => $request->no_penjualan,
                'tgl_penjualan' => $request->tgl_penjualan,
                'costumer_id' => $request->costumer,
                'user_id' => 1,
                'termin' => $request->termin,
                'kas_id' => $request->kas,
                'total' => $request->total,
                'jumlah_hari_tempo' => null,
                'tgl_jatuh_tempo' => null,
                'uang_muka' => $request->uang_muka,
                'keterangan' => '-',
            ]);

            $det_transaksi_penjualan = collect($request->list_item)->transform(function($value, $index){
                $new = [
                    'item_id'        => $value['item_id'],
                    'qty'            => $value['qty'],
                    'satuan_item_id' => $value['satuan'],
                    'status_in_out'  => '1',
                    'harga'          => $value['harga'],
                    'sub_total'      => $value['harga'] * $value['qty'],
                ];
                return new DetTransaksiPenjualan($new);
            });

            $transaksi->det_transaksi_penjualan()->saveMany($det_transaksi_penjualan);

            $transaksiPenjualanService->insertHistoryItem($request->all(), $transaksi);
            $transaksiPenjualanService->insertHistoryHutang($request->all(), $transaksi);
            $transaksiPenjualanService->insertHistoryKas($request->all(), $transaksi);

            // history jurnal credit , Cash
            if($request->termin == '2') {
                // // pembelian secara credit
                $transaksiPenjualanService->insertJurnalTransaksiPenjualanCredit($request->all(), $transaksi);
                $transaksiPenjualanService->insertJurnalHppTransaksiPenjualan($request->all(), $transaksi);
                // cek jika ada uang muka
                if ($request['uang_muka'] != "") {
                    $transaksiPenjualanService->autoInsertPenerimaanPembayaran($request->all(), $transaksi);
                    $transaksiPenjualanService->autoInsertJurnalPenerimaanPembayaran($request->all(), $transaksi);
                }
            } else {
                // pembelian secara cash
                $transaksiPenjualanService->insertJurnalTransaksiPenjualanCash($request->all(), $transaksi);
                $transaksiPenjualanService->insertJurnalHppTransaksiPenjualan($request->all(), $transaksi);
            }

            DB::commit();
            return response()->json([
                'status' => 'sukses',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 400);
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function get_kode_transaksi()
    {
        $check = TransaksiPenjualan::select('no_penjualan', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_penjualan = $check->no_penjualan;
        } else {
            $bulan_last = date('m');
            $no_penjualan = 'PJ'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_penjualan, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'PJ'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_penjualan, 6, 3);
            $tmp = 000+1;
            $kd = 'PJ'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }

}
