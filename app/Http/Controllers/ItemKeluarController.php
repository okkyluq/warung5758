<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemKeluar;
use App\DetItemKeluar;
use App\HistoryItem;
use Carbon\Carbon;
use DB;
use Auth;
use Fpdf;

class ItemKeluarController extends Controller
{
    
    public function index(Request $request)
    {
        if($request->ajax){
            $start = Carbon::createFromFormat('d-m-Y', $request->startdate)->format('Y-m-d');
            $end = Carbon::createFromFormat('d-m-Y', $request->enddate)->format('Y-m-d');
            $item = ItemKeluar::with(['user'])
                        ->whereBetween('tgl_keluar', [$start, $end])
                        ->where('kode_transaksi', 'like', '%' . $request->kodetransaksi . '%')
                        ->get();

            return response()->json($item); 

        }
        return view('backoffice.page.item_keluar.index');
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        $this->validate($request, [
            'kode_transaksi' => 'required|unique:item_keluar,kode_transaksi',
            'tgl_keluar' => 'required|date',
            'list_item' => 'required',
            'list_item.*.item_id' => 'required',
            'list_item.*.jumlah' => 'required',
            'list_item.*.harga_beli' => 'required',
        ], [
            'kode_transaksi.required' => 'Kode Transaksi Wajib Di isi',
            'kode_transaksi.unique' => 'Kode Transaksi Sudah Digunakan',
            'tgl_keluar.required' => 'Tanggal keluar Wajib Di isi',
            'list_item.required' => 'Item Minimal 1 ',
            'list_item.*.item_id.required' => 'Item Belum Dipilih',
            'list_item.*.jumlah.required' => 'Jumlah Belum Dipilih',
            'list_item.*.harga_beli.required' => 'Harga Tidak Boleh Kosong',
        ]);

        try { 
            DB::beginTransaction();
            
            $item_keluar = ItemKeluar::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_keluar' => Carbon::createFromFormat('d-m-Y', $request->tgl_keluar)->format('Y-m-d'), 
                'catatan' => $request->catatan == "" ? "-" : $request->catatan,
                'user_id' => Auth::user()->id,
            ]);

            $barang = collect($request->list_item)->transform(function($detail, $index) use ($item_keluar){
                $detail['item_keluar_id']   = $item_keluar->id;
                $detail['item_id']       = $detail['item_id'];
                $detail['jumlah']          = $detail['jumlah'];
                $detail['harga']           = $detail['harga_beli'];
                $detail['total_harga']     = $detail['jumlah'] * $detail['harga'];
                return new DetItemKeluar($detail);
            });

            $item_keluar->det_item_keluar()->saveMany($barang);
            
            $history = collect($request->list_item)->transform(function($detail, $index) use ($item_keluar){
                $detail['item_id'] = $detail['item_id'];
                $detail['qty']       = $detail['jumlah'];
                return new HistoryItem($detail);
            });

            $item_keluar->history()->saveMany($history);
   
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
        $item_keluar = ItemKeluar::findOrFail($id);
        try {
            DB::beginTransaction();
            
            $item_keluar->det_item_keluar()->delete();
            $item_keluar->history()->delete();
            $item_keluar->delete();

            DB::commit();  
            return response()->json([ 'message' => 'success' ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'message' => 'error', 'error' => $e ], 400);
        }
    }

    public function getitemkeluarid()
    {
        $check = ItemKeluar::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_permintaan = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $no_permintaan = 'IK'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_permintaan, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'IK'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_permintaan, 6, 3);
            $tmp = 000+1;
            $kd = 'IK'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
