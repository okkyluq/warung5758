<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Costumer;

class CostumerController extends Controller
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

    public function get_costumer(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = Costumer::select(['id', 'nama_costumer'])
                    ->where('nama_costumer','LIKE',"%$search%")
                    ->get();
        }
        return response()->json($data);
    }
}
