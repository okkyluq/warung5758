<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Costumer;
use DB;
class LihatPiutangController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $costumer = Costumer::select(['nama_costumer', 'id'])
                                ->withCount([
                                    'history_piutang as total_piutang' => function($query){
                                        $query->select(DB::raw('SUM(nominal) as total_bayar'))->where('status_lunas', '0');
                                    },
                                    'history_piutang as total_terbayar' => function($query){
                                        $query->select(DB::raw('SUM(terbayar) as total_terbayar'))->where('status_lunas', '0');
                                    }
                                ])
                                ->orderBy('created_at', 'desc');
            return Datatables::of($costumer)
                        ->addIndexColumn()
                        ->addColumn('total', function($data){
                            return 'Rp.'.number_format($data->total_piutang - $data->total_terbayar, 0); 
                        })
                        ->addColumn('action', function($data){
                            return '<ul class="icons-list">
                                <li class="text-primary-600"><a href="'.url("keuangan/lihat-piutang/detail-piutang/".$data->id).'"><i class="icon-file-eye2"></i></a></li>
                        </ul>';
                        })
                        ->rawColumns(['total', 'action'])
                        ->make(true);

        }

        return view('backoffice.page.lihat_piutang.index');
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
