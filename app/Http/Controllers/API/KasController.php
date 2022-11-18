<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Kas;

class KasController extends Controller
{

    public function index()
    {
        //
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

    public function get_kas(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = Kas::where('nama_kas', 'LIKE', '%'.$search.'%')
                        ->when($request->has('status_header') && $request->status_header != "", function($query){
                            $query->where('status_header', '1');
                        })
                        ->when($request->has('status_pembayaran') && $request->status_pembayaran != "", function($query) use ($request){
                            $query->where('status_pembayaran', $request->status_pembayaran);
                        })
                        ->when($request->has('category') && $request->category != "", function($query) use ($request){
                                $query->where('kategori_akun_id', $request->category);
                        })
                        ->get();
        }
        return response()->json($data);
    }
}
