<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TransaksiPembelian;
use Carbon\Carbon;

class LaporanPembelianController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {
            $get_tgl  = explode(' - ', $request->tgl_periode);
            $tgl_awal = Carbon::createFromFormat('d/m/Y', $get_tgl[0])->format('Y-m-d');
            $tgl_akhir =  Carbon::createFromFormat('d/m/Y', $get_tgl[1])->format('Y-m-d');

            $transaksi = TransaksiPembelian::with(['history_hutang', 'kas'])
                            ->when($request->has('tgl_periode') && $request->tgl_periode != "", function($query) use ($tgl_awal, $tgl_akhir){
                                $tgl = [$tgl_awal, $tgl_akhir];
                                $query->whereBetween('tgl_pembelian', $tgl);
                            })
                            ->when($request->has('supplier') && $request->supplier != "", function($query) use ($request) {
                                $query->where('supplier_id', $request->supplier);
                            })
                            ->when($request->has('tgl_jatuh_tempo') && $request->tgl_jatuh_tempo != "", function($query) use ($request){
                                $query->where('tgl_jatuh_tempo', Carbon::createFromFormat('d/m/Y', $request->tgl_jatuh_tempo)->format('Y-m-d'));
                            })
                            ->when($request->has('termin') && $request->termin != "", function($query) use ($request) {
                                $query->where('termin', $request->termin);
                            })
                            ->get();

                // dd($transaksi);
                            
            return view('backoffice.page.laporan_pembelian.partials.ajax_laporan_pembelian', [
                'transaksi' => $transaksi,
                'tgl_awal' => $tgl_awal,
                'tgl_akhir' => $tgl_akhir,
            ])->render();

        } 


        return view('backoffice.page.laporan_pembelian.index');
    }
}
