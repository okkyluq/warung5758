<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TransaksiProdukKeluar;
use App\DetTransaksiProdukKeluar;
use App\HistoryProduk;
use Carbon\Carbon;
use DB;
use Auth;
use Fpdf;

class TransaksiProdukKeluarController extends Controller
{
    
    public function index(Request $request)
    {
        if($request->ajax){
            $start = Carbon::createFromFormat('d-m-Y', $request->startdate)->format('Y-m-d');
            $end = Carbon::createFromFormat('d-m-Y', $request->enddate)->format('Y-m-d');
            $transaksi = TransaksiProdukKeluar::with(['user'])
                        ->whereBetween('tgl_keluar', [$start, $end])
                        ->where('kode_transaksi', 'like', '%' . $request->kodetransaksi . '%')
                        ->get();

            return response()->json($transaksi);
        }
        return view('backoffice.page.transaksi_produkkeluar.index');
    } 

    
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        $this->validate($request, [
            'kode_transaksi' => 'required|unique:transaksi_produk_keluar,kode_transaksi',
            'tgl_keluar' => 'required|date',
            'list_produk' => 'required',
            'list_produk.*.produk_id' => 'required',
            'list_produk.*.jumlah' => 'required',
            'list_produk.*.harga_beli' => 'required',
        ], [
            'kode_transaksi.required' => 'Kode Transaksi Wajib Di isi',
            'kode_transaksi.unique' => 'Kode Transaksi Sudah Digunakan',
            'tgl_keluar.required' => 'Tanggal Keluar Wajib Di isi',
            'list_produk.required' => 'Produk Minimal 1 ',
            'list_produk.*.produk_id.required' => 'Produk Belum Dipilih',
            'list_produk.*.jumlah.required' => 'Jumlah Belum Dipilih',
            'list_produk.*.harga_beli.required' => 'Harga Tidak Boleh Kosong',
        ]);

        try { 
            DB::beginTransaction();
            
            $transaksi_keluar = TransaksiProdukKeluar::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_keluar' => Carbon::createFromFormat('d-m-Y', $request->tgl_keluar)->format('Y-m-d'), 
                'catatan' => $request->catatan == "" ? "-" : $request->catatan,
                'user_id' => Auth::user()->id,
            ]);

            $barang = collect($request->list_produk)->transform(function($detail, $index) use ($transaksi_keluar){
                $detail['transaksi_produk_keluar_id']   = $transaksi_keluar->id;
                $detail['produk_id']                   = $detail['produk_id'];
                $detail['jumlah']                      = $detail['jumlah'];
                $detail['harga']                       = $detail['harga_beli'];
                $detail['total_harga']                 = $detail['jumlah'] * $detail['harga'];
                return new DetTransaksiProdukKeluar($detail);
            });

            $transaksi_keluar->det_transaksi_produk_keluar()->saveMany($barang);
            
            $history = collect($request->list_produk)->transform(function($detail, $index) use ($transaksi_keluar){
                $detail['produk_id'] = $detail['produk_id'];
                $detail['qty']       = $detail['jumlah'];
                return new HistoryProduk($detail);
            });

            $transaksi_keluar->history()->saveMany($history);
   
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
        $transaksi_keluar = TransaksiProdukKeluar::findOrFail($id);
        try {
            DB::beginTransaction();
            
            $transaksi_keluar->det_transaksi_produk_keluar()->delete();
            $transaksi_keluar->history()->delete();
            $transaksi_keluar->delete();

            DB::commit();  
            return response()->json([ 'message' => 'success' ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'message' => 'error', 'error' => $e ], 400);
        }
    }

    public function getprodukkeluarid()
    {
        $check = TransaksiProdukKeluar::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_permintaan = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $no_permintaan = 'PK'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_permintaan, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'PK'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_permintaan, 6, 3);
            $tmp = 000+1;
            $kd = 'PK'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
