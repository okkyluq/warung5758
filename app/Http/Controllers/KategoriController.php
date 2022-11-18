<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\Datatables;
use App\Kategori;
use Auth;
use DB;

class KategoriController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kategori = Kategori::orderBy('created_at', 'desc');
            return Datatables::of($kategori)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    return '
                         <div style="padding-left:5px;">
                                <a href="'.url("data-master/kategori/".$data->id)."/edit".'"><i class="icon-pencil5 text-success"></i></a>
                                <button class="btn-link" id="button_delete" data-id="'.$data->id.'"  type="button"><i class="icon-trash text-danger"></i></button>
                        </div>
                    ';
                })
                ->rawColumns(['action', 'kategori']) 
                ->make(true);
        }

        return view('backoffice.page.kategori.index');

    }

   
    public function create()
    {
        return view('backoffice.page.kategori.create');
    }

   
    public function store(Request $request)
    {

        $rules = [
            'kategori' => 'required|unique:kategori_item,kategori',
        ];
        $message = [
            'kategori.required' => 'Kategori Wajib Diisi !',
            'kategori.unique' => 'Data Sudah ada!',
        ];

        $this->validate($request, $rules, $message);

        if($request->ajax()){

            try {
                DB::beginTransaction();
                $kategori = Kategori::create([
                    'kategori' => ucwords($request->kategori),
                    'keterangan' => "",
                    'user_id' => Auth::user()->id,
                ]);
                DB::commit();  
                return response()->json($kategori);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([ 'message' => 'error', 'error' => $e ], 400);
            }


        } else {
            
            try {
                DB::beginTransaction();
                Kategori::create([
                    'kategori' => ucwords($request->kategori),
                    'keterangan' => $request->keterangan == "" ? "-" : $request->keterangan,
                    'user_id' => Auth::user()->id,
                ]);
                DB::commit();  
                return redirect('data-master/kategori')->with('success', 'Berhasil Menambahkan Kategori');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect('data-master/kategori')->with('failed', 'Gagal Menambahkan Kategori');
            }
        }
        
        
        


    }

   
    public function show($id)
    {
        

    }

   
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('backoffice.page.kategori.edit', compact('kategori'));

    }

    
    public function update(Request $request, $id)
    {
        $rules = [
            'kategori' => 'required|unique:kategori_item,kategori,'.$id,
        ];
        $message = [
            'kategori.required' => 'Kategori Wajib Diisi !',
            'kategori.unique' => 'Data Sudah ada!',
        ];

        $this->validate($request, $rules, $message);
        try {
            DB::beginTransaction();
            $kategori = Kategori::findOrFail($id)->update([
                'kategori' => ucwords($request->kategori),
                'keterangan' => $request->keterangan == "" ? "-" : $request->keterangan,
                'user_id' => Auth::user()->id,
            ]);
            DB::commit();  
            return redirect('data-master/kategori')->with('success', 'Berhasil Update Kategori');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('data-master/kategori')->with('failed', 'Gagal Menambahkan Kategori');
        }
    }

    
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        try {
            DB::beginTransaction();
            $kategori->delete();
            DB::commit();  
            return response()->json([
                'status' => 'sukses',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 400); 
        }
    }

    public function get_kategori(Request $request)
    {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = Kategori::select("id","kategori")
                    ->where('kategori','LIKE',"%$search%")
                    ->limit(5)
                    ->get();
        }
        return response()->json(['items' => $data]);

    }
}
