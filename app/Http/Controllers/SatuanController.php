<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Satuan;

class SatuanController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $satuan = Satuan::orderBy('created_at', 'desc');
            return Datatables::of($satuan)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    return '
                         <div style="padding-left:5px;">
                                <a href="'.url("data-master/satuan/".$data->id)."/edit".'"><i class="icon-pencil5 text-success"></i></a>
                                <button class="btn-link" id="button_delete" data-id="'.$data->id.'"  type="button"><i class="icon-trash text-danger"></i></button>
                        </div>
                    ';
                })
                ->rawColumns(['action', 'satuan'])
                ->make(true);
        }

        return view('backoffice.page.satuan.index');

    }


    public function create()
    {
        return view('backoffice.page.satuan.create');
    }


    public function store(Request $request)
    {
        $rules = [
            'satuan' => 'required|unique:satuan,satuan',
        ];
        $message = [
            'satuan.required' => 'Satuan Wajib Diisi !',
            'satuan.unique' => 'Data Sudah ada!',
        ];

        $this->validate($request, $rules, $message);

        if($request->ajax()){
            try {
                DB::beginTransaction();
                $satuan = Satuan::create([
                    'satuan' => ucwords($request->satuan),
                    'keterangan' => "",
                    'user_id' => Auth::user()->id,
                ]);
                DB::commit();
                return response()->json($satuan);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([ 'message' => 'error', 'error' => $e ], 400);
            }


        } else {
            try {
                DB::beginTransaction();
                Satuan::create([
                    'satuan' => ucwords($request->satuan),
                    'keterangan' => $request->keterangan == "" ? "-" : $request->keterangan,
                    'user_id' => Auth::user()->id,
                ]);
                DB::commit();
                return redirect('data-master/satuan')->with('success', 'Berhasil Menambahkan Satuan');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect('data-master/satuan')->with('failed', 'Gagal Menambahkan Satuan');
            }
        }




    }


    public function show($id)
    {
        $satuan = Satuan::with(['satuan_item'])->findOrFail($id);

        dd($satuan->satuan_item()->exists());

        // if(count($satuan->satuan_item) > 0){
        //     return response()->json('ada');
        // }
        // return response()->json('tdk ada');
    }


    public function edit($id)
    {
        $satuan = Satuan::findOrFail($id);
        return view('backoffice.page.satuan.edit', compact('satuan'));
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'satuan' => 'required|unique:satuan,satuan,'.$id,
        ];
        $message = [
            'satuan.required' => 'satuan Wajib Diisi !',
            'satuan.unique' => 'Data Sudah ada!',
        ];

        $this->validate($request, $rules, $message);
        try {
            DB::beginTransaction();
            $satuan = Satuan::findOrFail($id)->update([
                'satuan' => ucwords($request->satuan),
                'keterangan' => $request->keterangan == "" ? "-" : $request->keterangan,
                'user_id' => Auth::user()->id,
            ]);
            DB::commit();
            return redirect('data-master/satuan')->with('success', 'Berhasil Update Satuan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('data-master/satuan')->with('failed', 'Gagal Menambahkan Satuan');
        }
    }


    public function destroy($id)
    {
        $satuan = Satuan::with(['satuan_item'])->findOrFail($id);
        try {
            DB::beginTransaction();
            if($satuan->satuan_item()->exists()){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak Dapat Menghapus Karena Data Satuan Sudah Dipakai Untuk Item !',
                ], 405);
            }
            $satuan->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Menghapus Data Satuan !',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e
            ], 400);
        }
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
