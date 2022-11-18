<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Satuan;
use App\Http\Controllers\Controller;

class SatuanController extends Controller
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

    public function get_satuan(Request $request)
    {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = Satuan::select(["id","satuan"])
                    ->where('satuan','LIKE',"%$search%")
                    ->get();
        }
        return response()->json($data);
    }
}
