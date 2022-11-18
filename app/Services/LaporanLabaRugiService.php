<?php

namespace App\Services;
use App\PengaturanAkun;
use DB;

class LaporanLabaRugiService {

    public function getSumDebitKredit(String $akun_string)
    {
        return $akun_penjualan = PengaturanAkun::withCount([
            'det_history_jurnal as total' => function($query) { $query->select(DB::raw("ABS(SUM(nominal_debit) - SUM(nominal_kredit)) as total")); },
        ])->where('setting', $akun_string)->first();
    }

}