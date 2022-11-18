<?php

namespace App\Services;
use App\TransaksiPenjualan;

class DashboardService {

    public function getSumByMonthYear($month, $year)
    {
        return TransaksiPenjualan::whereMonth('tgl_penjualan', $month)->whereYear('tgl_penjualan', $year)->sum('total');
    }

    public function getSumByDateYear($date)
    {
        return TransaksiPenjualan::whereDate('tgl_penjualan', $date)->sum('total');
    }

}