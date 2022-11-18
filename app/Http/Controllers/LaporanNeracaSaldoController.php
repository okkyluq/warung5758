<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\KategoriAkun;
use Carbon\Carbon;

class LaporanNeracaSaldoController extends Controller
{

    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {

            $tgl = explode(" - ", $request->tgl_periode);
            $tgl_start = Carbon::createFromFormat('d/m/Y', $tgl[0])->format('Y-m-d');
            $tgl_end = Carbon::createFromFormat('d/m/Y', $tgl[1])->format('Y-m-d');

            $akun = KategoriAkun::with([
                'akun' => function($query) use ($tgl_start, $tgl_end){
                    $query->withCount([
                        'det_history_jurnal as kredit' => function($query) use ($tgl_start, $tgl_end) {
                            $query->select(DB::raw("SUM(det_history_jurnal.nominal_kredit) as kredit"))
                                  ->join('history_jurnal', 'history_jurnal.id', '=', 'det_history_jurnal.history_journal_id')
                                  ->whereBetween('history_jurnal.tgl_set', [$tgl_start, $tgl_end])
                                  ->groupBy('det_history_jurnal.akun_id');
                        },
                        'det_history_jurnal as debit' => function($query) use ($tgl_start, $tgl_end){
                            $query->select(DB::raw("SUM(det_history_jurnal.nominal_debit) as debit"))
                                  ->join('history_jurnal', 'history_jurnal.id', '=', 'det_history_jurnal.history_journal_id')
                                  ->whereBetween('history_jurnal.tgl_set', [$tgl_start, $tgl_end])
                                  ->groupBy('det_history_jurnal.akun_id');
                        }
                    ]);
                },
            ])
            ->get();

            return view('backoffice.page.laporan_neraca_saldo.partials.ajax_laporan_neraca_saldo', [
                'akun' => $akun,
                'tgl_awal' => $tgl[0],
                'tgl_akhir' => $tgl[1]
            ])->render();

        }
        return view('backoffice.page.laporan_neraca_saldo.index');
    }



}
