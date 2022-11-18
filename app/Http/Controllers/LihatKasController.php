<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Kas;
use Illuminate\Support\Facades\DB;

class LihatKasController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kas = Kas::select(['kode_kas', 'nama_kas'])
                    ->withCount([
                        'history_kas AS kas_masuk' => function($query){
                            $query->select(DB::raw("SUM(nominal_debit) as debit"))->whereMonth('created_at', '=', date('m'));
                        },
                        'history_kas AS kas_keluar' => function($query){
                            $query->select(DB::raw("SUM(nominal_kredit) as kredit"))->whereMonth('created_at', '=', date('m'));
                        },
                        'history_kas AS total_masuk' => function($query){
                            $query->select(DB::raw("SUM(nominal_debit) as debit"));
                        },
                        'history_kas AS total_keluar' => function($query){
                            $query->select(DB::raw("SUM(nominal_kredit) as kredit"));
                        },
                    ])
                    ->orderBy('created_at', 'desc');

            return Datatables::of($kas)
                    ->addIndexColumn()
                    ->addColumn('saldo', function($data){
                        return 'Rp.'.number_format($data->total_masuk - $data->total_keluar, 0);
                    })
                    ->addColumn('kas_masuk', function($data){
                        return 'Rp.'.number_format($data->kas_masuk, 0);
                    })
                    ->addColumn('kas_keluar', function($data){
                        return 'Rp.'.number_format($data->kas_keluar, 0);
                    })
                    ->rawColumns(['kas_masuk', 'kas_keluar'])
                    ->make(true);

        }
        return view('backoffice.page.lihat_kas.index');
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
}
