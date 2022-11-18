<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Supplier;
use Auth;
use DB;

class SupplierController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $supplier = Supplier::orderBy('created_at', 'desc');
            return Datatables::of($supplier)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    return '<ul class="icons-list">
                                <li class="text-primary-600"><a href="'.url("data-master/supplier/".$data->id)."/edit".'"><i class="icon-pencil5"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                            </ul>';
                })
                ->rawColumns(['action', 'satuan'])
                ->make(true);
        }

        return view('backoffice.page.supplier.index');
    }


    public function create()
    {
        return view('backoffice.page.supplier.create');
    }


    public function store(Request $request)
    {
        $rules = [
            'nama_supplier' => 'required|unique:supplier,nama_supplier',
        ];
        $message = [
            'nama_supplier.required' => 'Nama Supplier Wajib Diisi !',
            'nama_supplier.unique' => 'Data Sudah ada!',
        ];

        $this->validate($request, $rules, $message);
        try {
            DB::beginTransaction();
            Supplier::create([
                'nama_supplier' => ucwords($request->nama_supplier),
                'keterangan' => $request->keterangan == "" ? "-" : $request->keterangan,
                'user_id' => Auth::user()->id,
            ]);
            DB::commit();
            return redirect('data-master/supplier')->with('success', 'Berhasil Menambahkan Supplier');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('data-master/supplier')->with('failed', 'Gagal Menambahkan Supplier');
        }
    }


    public function show($id)
    {
        $supplier = Supplier::with(['transaksi_pembelian'])->findOrFail($id);
        dd($supplier->transaksi_pembelian()->exists());
    }


    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('backoffice.page.supplier.edit', ['supplier' =>  $supplier]);
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'nama_supplier' => 'required|unique:supplier,nama_supplier,'.$id,
        ];
        $message = [
            'nama_supplier.required' => 'Nama Supplier Wajib Diisi !',
            'nama_supplier.unique' => 'Data Sudah ada!',
        ];

        $this->validate($request, $rules, $message);

        $supplier = Supplier::findOrFail($id);

        try {
            DB::beginTransaction();
            $supplier->update([
                'nama_supplier' => ucwords($request->nama_supplier),
                'keterangan' => $request->keterangan == "" ? "-" : $request->keterangan,
                'user_id' => Auth::user()->id,
            ]);
            DB::commit();
            return redirect('data-master/supplier')->with('success', 'Berhasil Update Data Supplier');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('data-master/supplier')->with('failed', 'Gagal Update Data Supplier');
        }
    }


    public function destroy($id)
    {
        $supplier = Supplier::with(['transaksi_pembelian'])->findOrFail($id);
        try {
            DB::beginTransaction();
            if($supplier->transaksi_pembelian()->exists()){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak Dapat Menghapus Karena Supplier Sudah Memiliki Data Transaksi !',
                ], 405);
            }
            $supplier->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Menghapus Data Supplier !',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e
            ], 400);
        }
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
