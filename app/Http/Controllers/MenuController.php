<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use App\DetMenu;
use Carbon\Carbon;
use DB;
use Auth;
use Fpdf;

class MenuController extends Controller
{

    public function index(Request $request)
    {
        if($request->ajax){
            // $start = Carbon::createFromFormat('d-m-Y', $request->startdate)->format('Y-m-d');
            // $end = Carbon::createFromFormat('d-m-Y', $request->enddate)->format('Y-m-d');
            // $item = ItemMasuk::with(['user'])
            //             ->whereBetween('tgl_masuk', [$start, $end])
            //             ->where('kode_transaksi', 'like', '%' . $request->kodetransaksi . '%')
            //             ->get();

            // return response()->json($item); 

        }
        return view('backoffice.page.menu.index');
    }


    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
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

    public function getkodemenu()
    {
        $check = Menu::select('kode_menu', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_permintaan = $check->kode_menu;
        } else {
            $bulan_last = date('m');
            $no_permintaan = 'MN'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_permintaan, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'MN'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_permintaan, 6, 3);
            $tmp = 000+1;
            $kd = 'MN'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
