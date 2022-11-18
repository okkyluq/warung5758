<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Costumer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CostumerController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $costumer = Costumer::orderBy('created_at', 'desc');
            return Datatables::of($costumer)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    return '<ul class="icons-list">
                                <li class="text-primary-600"><a href="'.url("data-master/costumer/".$data->id)."/edit".'"><i class="icon-pencil5"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                            </ul>';
                })
                ->rawColumns(['action', 'satuan'])
                ->make(true);
        }

        return view('backoffice.page.costumer.index');
    }


    public function create()
    {
        return view('backoffice.page.costumer.create');
    }


    public function store(Request $request)
    {
        $rules = [
            'nama_costumer' => 'required|unique:costumer,nama_costumer',
        ];
        $message = [
            'nama_costumer.required' => 'Nama Costumer Wajib Diisi !',
            'nama_costumer.unique' => 'Data Sudah ada!',
        ];

        $this->validate($request, $rules, $message);
        try {
            DB::beginTransaction();
            Costumer::create([
                'nama_costumer' => ucwords($request->nama_costumer),
                'keterangan' => $request->keterangan == "" ? "-" : $request->keterangan,
                'user_id' => Auth::user()->id,
            ]);
            DB::commit();
            return redirect('data-master/costumer')->with('success', 'Berhasil Menambahkan Costumer');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('data-master/costumer')->with('failed', 'Gagal Menambahkan Costumer');
        }
    }


    public function show($id)
    {
        $costumer = Costumer::with(['transaksi_penjualan'])->findOrFail($id);
        dd($costumer->transaksi_penjualan()->exists());
    }


    public function edit($id)
    {
        $costumer = Costumer::findOrFail($id);
        return view('backoffice.page.costumer.edit', ['costumer' =>  $costumer]);
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'nama_costumer' => 'required|unique:costumer,nama_costumer,'.$id,
        ];
        $message = [
            'nama_costumer.required' => 'Nama Costumer Wajib Diisi !',
            'nama_costumer.unique' => 'Data Sudah ada!',
        ];

        $this->validate($request, $rules, $message);

        $costumer = Costumer::findOrFail($id);

        try {
            DB::beginTransaction();
            $costumer->update([
                'nama_costumer' => ucwords($request->nama_costumer),
                'keterangan' => $request->keterangan == "" ? "-" : $request->keterangan,
                'user_id' => Auth::user()->id,
            ]);
            DB::commit();
            return redirect('data-master/costumer')->with('success', 'Berhasil Update Data Costumer');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('data-master/costumer')->with('failed', 'Gagal Update Data Costumer');
        }
    }


    public function destroy($id)
    {
        $costumer = Costumer::findOrFail($id);
        try {
            DB::beginTransaction();
            if ($costumer->transaksi_penjualan()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak Dapat Menghapus Karena Costumer Sudah Memiliki Data Transaksi !',
                ], 405);
            }
            $costumer->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Menghapus Data Costumer !',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e
            ], 400);
        }
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
