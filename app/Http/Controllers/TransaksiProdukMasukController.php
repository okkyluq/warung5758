<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TransaksiProdukMasuk;
use App\DetTransaksiProdukMasuk;
use App\HistoryProduk;
use Carbon\Carbon;
use DB;
use Auth;
use Fpdf;

class TransaksiProdukMasukController extends Controller
{
  
    public function index(Request $request)
    {
        if($request->ajax){
            $start = Carbon::createFromFormat('d-m-Y', $request->startdate)->format('Y-m-d');
            $end = Carbon::createFromFormat('d-m-Y', $request->enddate)->format('Y-m-d');
            $transaksi = TransaksiProdukMasuk::with(['user'])
                        ->whereBetween('tgl_masuk', [$start, $end])
                        ->where('kode_transaksi', 'like', '%' . $request->kodetransaksi . '%')
                        ->get();

            return response()->json($transaksi);

        }
        return view('backoffice.page.transaksi_produkmasuk.index');
    }

 
    public function create()
    {
        //
    }

 
    public function store(Request $request)
    {
        $this->validate($request, [
            'kode_transaksi' => 'required|unique:transaksi_produk_masuk,kode_transaksi',
            'tgl_masuk' => 'required|date',
            'list_produk' => 'required',
            'list_produk.*.produk_id' => 'required',
            'list_produk.*.jumlah' => 'required',
            'list_produk.*.harga_beli' => 'required',
        ], [
            'kode_transaksi.required' => 'Kode Transaksi Wajib Di isi',
            'kode_transaksi.unique' => 'Kode Transaksi Sudah Digunakan',
            'tgl_masuk.required' => 'Tanggal Masuk Wajib Di isi',
            'list_produk.required' => 'Produk Minimal 1 ',
            'list_produk.*.produk_id.required' => 'Produk Belum Dipilih',
            'list_produk.*.jumlah.required' => 'Jumlah Belum Dipilih',
            'list_produk.*.harga_beli.required' => 'Harga Tidak Boleh Kosong',
        ]);

        try { 
            DB::beginTransaction();
            
            $transaksi_masuk = TransaksiProdukMasuk::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_masuk' => Carbon::createFromFormat('d-m-Y', $request->tgl_masuk)->format('Y-m-d'), 
                'catatan' => $request->catatan == "" ? "-" : $request->catatan,
                'user_id' => Auth::user()->id,
            ]);

            $barang = collect($request->list_produk)->transform(function($detail, $index) use ($transaksi_masuk){
                $detail['transaksi_produk_masuk_id']   = $transaksi_masuk->id;
                $detail['produk_id']                   = $detail['produk_id'];
                $detail['jumlah']                      = $detail['jumlah'];
                $detail['harga']                       = $detail['harga_beli'];
                $detail['total_harga']                 = $detail['jumlah'] * $detail['harga'];
                return new DetTransaksiProdukMasuk($detail);
            });

            $transaksi_masuk->det_transaksi_produk_masuk()->saveMany($barang);
            
            $history = collect($request->list_produk)->transform(function($detail, $index) use ($transaksi_masuk){
                $detail['produk_id'] = $detail['produk_id'];
                $detail['qty']       = $detail['jumlah'];
                return new HistoryProduk($detail);
            });

            $transaksi_masuk->history()->saveMany($history);
   
            DB::commit();  
            return response()->json([ 'message' => 'success' ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'message' => 'error', 'error' => $e ], 400);
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
        $transaksi_masuk = TransaksiProdukMasuk::findOrFail($id);
        try {
            DB::beginTransaction();
            
            $transaksi_masuk->det_transaksi_produk_masuk()->delete();
            $transaksi_masuk->history()->delete();
            $transaksi_masuk->delete();

            DB::commit();  
            return response()->json([ 'message' => 'success' ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'message' => 'error', 'error' => $e ], 400);
        }

    }

    public function getprodukmasukid()
    {
        $check = TransaksiProdukMasuk::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_permintaan = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $no_permintaan = 'PM'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_permintaan, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'PM'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_permintaan, 6, 3);
            $tmp = 000+1;
            $kd = 'PM'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
