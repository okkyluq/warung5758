<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Item;
use App\HistoryItem;
use Carbon\Carbon;
use Auth;
use DB;

class LaporanStockItemController extends Controller
{
     
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $item = Item::with(['get_satuan_stock', 'satuan_item' => function($query){
                $query->orderBy('lvl', 'desc');
            },
            'satuan_item.satuan'])
            ->withCount(['history_item' => function($query){
                $query->select(DB::raw("SUM(history_item.qty * satuan_item.qty_konversi) as stock"))
                        ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
                        ->groupBy('history_item.item_id');
            }]);


            return Datatables::of($item)
                ->addIndexColumn()
                ->editColumn('tipe_item', function($data){
                    $tipe = ['Barang Jadi', 'Barang Hasil Produksi', 'Bahan Baku'];
                    return "<span class='label bg-success-400'>".$tipe[$data->tipe_item]."</span>";
                })
                ->addColumn('action', function($data){
                    return '
                         <div style="padding-left:5px;">
                                <a href="'.url("data-master/item/".$data->id)."/edit".'"><i class="icon-eye text-info"></i></a>
                        </div>
                    ';
                })
                ->addColumn('stock', function($data){
                    // $qty_konversi = $data->satuan_item->where('item_id', $data->id)->where('satuan_id', $data->get_satuan_stock->id)->first();
                    // return round($data->history_item_count / $qty_konversi->qty_konversi, 2).' ('.$data->get_satuan_stock->satuan.')';

                    $qty = (int)$data->history_item_count;
                    $satuan = $this->get_satuan($data->satuan_item, $qty);
                    return collect($satuan)->map(function($data){
                        return "<span class='label bg-success-400'>".$data['unit'].' '.round($data['qty'])."</span>";
                    })->implode(' ');



                })
                ->rawColumns(['action', 'stock', 'tipe_item'])
                ->make(true);
        }
        return view('backoffice.page.laporan_stock_item.index');

    }

    public function show($id)
    {
        $item = Item::with(['get_satuan_stock', 'satuan_item' => function($query){
            $query->orderBy('lvl', 'desc');
        },
        'satuan_item.satuan'])
        ->withCount(['history_item' => function($query){
            $query->select(DB::raw("SUM(history_item.qty * satuan_item.qty_konversi) as stock"))
                    ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
                    ->groupBy('history_item.item_id');
        }])->where('id', $id)->first();

        $qty = (int)$item->history_item_count;

        return $this->get_satuan($item->satuan_item, $qty);

    }

    public function get_satuan($list, $quantity)
    {
        $sisa = 0;
        $bingkai = [];
        foreach ($list as $isi) {
            array_push($bingkai, ['size' => $isi->qty_konversi, 'qty' => floor($quantity/$isi->qty_konversi), 'unit' => $isi->satuan->satuan]);
            $sisa = $quantity % $isi->qty_konversi;
            $quantity = $sisa;
        }
        return $bingkai;
    }

    public function laporan_saldo_stock(Request $request)
    {
        if ($request->isMethod('POST')) {
            $item = Item::select(['id', 'kode_item', 'nama_item', 'satuan_penjualan', 'satuan_pembelian', 'satuan_stock'])
                    ->with(['get_satuan_stock'])
                    ->paginate(5);
            // dd($item);
            return view('backoffice.page.laporan_stock_item.view_laporan_saldo_stock', [
                'item' => $item
            ]);
        }
        return view('backoffice.page.laporan_stock_item.view_laporan_saldo_stock');
    }

    public function laporan_kartu_stock(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {

            $this->validate($request, [
                'tgl_periode' => 'required',
                'item' => 'required',
            ], [
                'tgl_periode.required' => 'Tanggal Periode Wajib Diisi !',
                'item.required' => 'Item Wajib Dipilih !'
            ]);


            $get_tgl  = explode(' - ', $request->tgl_periode);
            $tgl_awal = Carbon::createFromFormat('d/m/Y', $get_tgl[0])->format('Y-m-d');
            $tgl_akhir =  Carbon::createFromFormat('d/m/Y', $get_tgl[1])->format('Y-m-d');
            $tgl = [$tgl_awal, $tgl_akhir];
            $item = $request->item;



            // $query_out = HistoryItem::select(['history_item.item_id', 'history_item.satuan_item_id', DB::raw('"-" as debit'), DB::raw('ROUND(ABS(history_item.qty * satuan_item.qty_konversi)) as kredit'), DB::raw('history_item.created_at as waktu')])
            //             ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
            //             ->where('history_item.status_in_out', "0")->where('history_item.item_id', $item)->union($query_in);


            $transaksi_pembelian = HistoryItem::select(['history_item.item_id', 'transaksi_pembelian.tgl_pembelian as tgl_transaksi', DB::raw('CONCAT("#Pembelian ", transaksi_pembelian.no_pembelian) as keterangan'), 'supplier.nama_supplier as mitra_bisnis', DB::raw('ROUND(ABS(history_item.qty * satuan_item.qty_konversi)) as debit'), DB::raw('"-" as kredit')])
                                    ->join('transaksi_pembelian', function($query){
                                        $query->on('transaksi_pembelian.id', '=', 'history_item.historyable_id');
                                        $query->where('historyable_type', 'App\TransaksiPembelian');
                                    })
                                    ->join('supplier', 'supplier.id', '=', 'transaksi_pembelian.supplier_id')
                                    ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
                                    ->where('history_item.historyable_type', 'App\TransaksiPembelian')->where('history_item.status_in_out', "1")->where('history_item.item_id', $item);
                                    // ->whereBetween('transaksi_pembelian.tgl_pembelian', $tgl);


            $transaksi_penjualan = HistoryItem::select(['history_item.item_id', 'transaksi_penjualan.tgl_penjualan as tgl_transaksi', DB::raw('CONCAT("#Penjualan ", transaksi_penjualan.no_penjualan) as keterangan'), 'costumer.nama_costumer as mitra_bisnis', DB::raw('"-" as debit'), DB::raw('ROUND(ABS(history_item.qty * satuan_item.qty_konversi)) as kredit')])
                                    ->join('transaksi_penjualan', function($query){
                                        $query->on('transaksi_penjualan.id', '=', 'history_item.historyable_id');
                                        $query->where('historyable_type', 'App\TransaksiPenjualan');
                                    })
                                    ->join('costumer', 'costumer.id', '=', 'transaksi_penjualan.costumer_id')
                                    ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
                                    ->where('history_item.historyable_type', 'App\TransaksiPenjualan')->where('history_item.status_in_out', "0")->where('history_item.item_id', $item);
                                    // ->whereBetween('transaksi_penjualan.tgl_penjualan', $tgl);

            $produksi_in = HistoryItem::select(['history_item.item_id', 'produksi_bom.tgl_produksi as tgl_transaksi', DB::raw('CONCAT("#Produksi ", produksi_bom.no_produksi) as keterangan'), DB::raw("'-' as mitra_bisnis"), DB::raw('ROUND(ABS(history_item.qty * satuan_item.qty_konversi)) as debit'), DB::raw('"-" as kredit')])
                            ->join('produksi_bom', function($query){
                                $query->on('produksi_bom.id', '=', 'history_item.historyable_id');
                                $query->where('historyable_type', 'App\ProduksiBom');
                            })
                            ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
                            ->where('history_item.historyable_type', 'App\ProduksiBom')->where('history_item.status_in_out', "1")->where('history_item.item_id', $item);
                            // ->whereBetween('produksi_bom.tgl_produksi', $tgl);

            $produksi_out = HistoryItem::select(['history_item.item_id', 'produksi_bom.tgl_produksi as tgl_transaksi', DB::raw('CONCAT("#Produksi ", produksi_bom.no_produksi) as keterangan'), DB::raw("'-' as mitra_bisnis"), DB::raw('"-" as debit'), DB::raw('ROUND(ABS(history_item.qty * satuan_item.qty_konversi)) as kredit')])
                            ->join('produksi_bom', function($query){
                                $query->on('produksi_bom.id', '=', 'history_item.historyable_id');
                                $query->where('historyable_type', 'App\ProduksiBom');
                            })
                            ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
                            ->where('history_item.historyable_type', 'App\ProduksiBom')->where('history_item.status_in_out', "0")->where('history_item.item_id', $item)
                            // ->whereBetween('produksi_bom.tgl_produksi', $tgl)
                            ->union($transaksi_pembelian)
                            ->union($transaksi_penjualan)
                            ->union($produksi_in);



            DB::statement("SET @saldo:=0");
            $item = Item::select(["*", DB::raw("@saldo := @saldo + IF(history_item.debit = '-', 0, history_item.debit) - IF(history_item.kredit = '-', 0, history_item.kredit) as sisa_stock")])
                    ->where('item.id', $item)
                    ->joinSub($produksi_out, 'history_item', function ($join) {
                        $join->on('item.id', '=', 'history_item.item_id');
                    })
                    ->orderBy('history_item.tgl_transaksi', 'ASC');

            // dd($item->toArray());

            $final = Item::select(['item.id', 'item.nama_item', 'history_item.tgl_transaksi', 'history_item.keterangan', 'history_item.mitra_bisnis',DB::raw("IFNULL(history_item.sisa_stock, 0) + IFNULL(history_item.kredit, 0) - IFNULL(history_item.debit, 0) stock_awal"), "history_item.debit", "history_item.kredit", "history_item.sisa_stock"])
                    ->with(['satuan_item' => function($query){
                        $query->where('satuan_item.lvl', '1')->with(['satuan']);
                    }])
                    ->joinSub($item, 'history_item', function ($join) {
                        $join->on('item.id', '=', 'history_item.item_id');
                    })
                    ->whereBetween('history_item.tgl_transaksi', $tgl)
                    ->get();

            // dd($final->toArray());
            return view('backoffice.page.laporan_stock_item.partials.ajax_laporan_kartu_stock', [
                'item' => $final
            ])->render();

        }

        return view('backoffice.page.laporan_stock_item.view_laporan_kartu_stock');
    }


}
