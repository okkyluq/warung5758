<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Supplier;

class SupplierController extends Controller
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

    public function get_supplier(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = Supplier::select(['id', 'nama_supplier'])
                    ->where('nama_supplier','LIKE',"%$search%")
                    ->get();
        }
        return response()->json($data);
    }
}
