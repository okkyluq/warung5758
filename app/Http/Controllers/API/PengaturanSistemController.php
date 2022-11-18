<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PengaturanAkun;


class PengaturanSistemController extends Controller
{
    public function get_pengaturan_akun(Request $request)
    {
    	return PengaturanAkun::all();
    }
}
