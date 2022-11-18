<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransaksiPenjualanRequest;
use App\Services\TransaksiPenjualanService;
use App\PengaturanSistem;
use App\TransaksiPenjualan;
use App\DetTransaksiPenjualan;
use Carbon\Carbon;
use DB;
use Auth;

class PenjualanController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        return view('kasir.page.penjualan.create', [
            'costumer_default' => PengaturanSistem::where('setting', 'costumer_default')->pluck('value')->first(),
            'kas_credit' => PengaturanSistem::where('setting', 'kas_penjualan_credit')->pluck('value')->first(),
            'kas_cash' => PengaturanSistem::where('setting', 'kas_penjualan_cash')->pluck('value')->first()
        ]);
    }


    public function store(TransaksiPenjualanRequest $request, TransaksiPenjualanService $transaksiPenjualanService)
    {
        try {
            DB::beginTransaction();

            $transaksi = TransaksiPenjualan::create([
                'no_penjualan' => $request->no_penjualan,
                'tgl_penjualan' => Carbon::createFromFormat('d/m/Y', $request->tgl_penjualan)->format('Y-m-d'),
                'costumer_id' => $request->costumer,
                'user_id' => Auth::user()->id,
                'termin' => $request->termin,
                'kas_id' => $request->kas,
                'total' => str_replace(',', '', $request->total),
                'jumlah_hari_tempo' => $request->hari_jatuh_tempo,
                'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo != '' ? Carbon::createFromFormat('d/m/Y', $request->tgl_penjualan)->addDays($request->hari_jatuh_tempo)->format('Y-m-d') : null,
                'uang_muka' => !empty($request->uang_muka) ? str_replace(',', '', $request->uang_muka) : null,
                'keterangan' => $request->keterangan,
            ]);

            $detail = collect($request->list_item)->transform(function($value, $index){
                $new['item_id']           = $value['item_id'];
                $new['qty']               = $value['qty'];
                $new['satuan_item_id']    = $value['satuan'];
                $new['harga']             = $value['harga'];
                $new['sub_total']         = $value['qty'] * $value['harga'];
                return new DetTransaksiPenjualan($new);
            });

            $transaksi->det_transaksi_penjualan()->saveMany($detail);

            $transaksiPenjualanService->insertHistoryItem($request->all(), $transaksi);
            $transaksiPenjualanService->insertHistoryHutang($request->all(), $transaksi);
            $transaksiPenjualanService->insertHistoryKas($request->all(), $transaksi);

            // history jurnal credit , Cash
            if($request->termin == '2') {
                // // pembelian secara credit
                $transaksiPenjualanService->insertJurnalTransaksiPenjualanCredit($request->all(), $transaksi);
                $transaksiPenjualanService->insertJurnalHppTransaksiPenjualan($request->all(), $transaksi);
                // cek jika ada uang muka
                if (str_replace(',', '', $request['uang_muka']) != "") {
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
}
