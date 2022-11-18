<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\Datatables;
use Illuminate\Http\Request;
use App\KategoriAkun;
use App\Akun;

class AkunController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $akun = Akun::select(['id', 'kategori_akun_id', 'kode_akun', 'nama_akun', 'status_header', 'status_pembayaran', 'default_saldo', 'parent_id'])
                    ->when($request->order[0]['column'] == 1, function($query) use ($request) {
                        $query->orderBy('kategori_akun_id', $request->order[0]['dir']);
                    })
                    ->when($request->order[0]['column'] == 3, function($query) use ($request) {
                        $query->orderBy('status_header', $request->order[0]['dir']);
                    })
                    ->when($request->order[0]['column'] == 4, function($query) use ($request) {
                        $query->orderBy('status_pembayaran', $request->order[0]['dir']);
                    })
                    ->orderBy('created_at', 'desc')
                    ->with(['kategori']);

            return Datatables::of($akun)
                ->addIndexColumn()
                ->editColumn('nama_akun', function($data){
                    return '<p class="text-bold">'.$data->nama_akun.'</p>';
                })
                ->editColumn('kategori_akun_id', function($data){
                    $color = ['btn-default', 'btn-primary', 'btn-success', 'btn-warning', 'btn-danger', 'btn-purple'];
                    return '<button class="btn btn-xs btn-block text-bold '.$color[$data->kategori->no_kategori].'">'.$data->kategori->nama_kategori.'</button>';
                })
                ->editColumn('status_pembayaran', function($data){
                    if ($data->status_pembayaran == '0') {
                        return '<span class="text-danger-600"><i class="icon-cancel-circle2 position-left"></i>Tidak</span>';
                    } else {
                        return '<span class="text-success-600"><i class="icon-checkmark3 position-left"></i>Ya</span>';
                    }
                })
                ->editColumn('status_header', function($data){
                    if ($data->status_header == '0') {
                        return '<span class="text-danger-600"><i class="icon-cancel-circle2 position-left"></i>Tidak</span>';
                    } else {
                        return '<span class="text-success-600"><i class="icon-checkmark3 position-left"></i>Ya</span>';
                    }
                })
                ->addColumn('action', function($data){
                    return '
                         <div style="padding-left:5px;">
                                <a href="'.url("data-master/akun/".$data->id)."/edit".'"><i class="icon-pencil5 text-success"></i></a>
                                <button disabled class="btn-link disabled" id="button_delete" data-id="'.$data->id.'"  type="button"><i class="icon-trash text-danger"></i></button>
                        </div>
                    ';
                })
                ->rawColumns(['action', 'kategori_akun_id', 'status_pembayaran', 'nama_akun', 'status_header'])
                ->make(true);

        }

        return view('backoffice.page.akun.index');
    }


    public function create()
    {
        $kategori = KategoriAkun::select(['id', 'no_kategori', 'nama_kategori'])->get();
        return view('backoffice.page.akun.create', [
            'kategori' => $kategori
        ]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'kategori_akun_id' => 'required',
            'kode_akun' => 'required|unique:akun,kode_akun',
            'nama_akun' => 'required|unique:akun,nama_akun',
            'parent_id' => 'required_if:status_header,==,""',
        ], [
            'kategori_akun_id.required' => 'Kategori Akun Wajib Dipilih',
            'kode_akun.required' => 'Kode Akun Wajib Diisi',
            'kode_akun.unique' => 'Kode Akun Sudah Digunakan',
            'nama_akun.required' => 'Nama Akun Wajib Diisi',
            'nama_akun.unique' => 'Nama Akun Sudah Digunakan',
            'parent_id.required_if' => 'Induk Akun Wajib Disi Jika Status Induk Dipilih Tidak',
        ]);

        try {
            $akun = Akun::create([
                'kategori_akun_id' => $request->kategori_akun_id,
                'kode_akun' => $request->kode_akun,
                'nama_akun' => $request->nama_akun,
                'status_header' => $request->has('status_header') ? '1' : '0',
                'status_pembayaran' => $request->has('status_pembayaran') ? '1' : '0',
                'default_saldo' => $request->has('default_saldo') ? '1' : '2',
                'parent_id' => $request->has('parent_id') ? $request->parent_id : null,
            ]);
            return redirect('data-master/akun')->with(['success' => 'Berhasil Menambahkan Akun']);
        } catch (\Exception $e) {
            return redirect('data-master/akun')->with(['error' => 'Gagal Menambahkan Akun']);
        }

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $akun = Akun::with(['parent_akun'])->findOrFail($id);
        $kategori = KategoriAkun::select(['id', 'no_kategori', 'nama_kategori'])->get();
        return view('backoffice.page.akun.edit', [
            'kategori' => $kategori,
            'akun' => $akun
        ]);
    }


    public function update(Request $request, $id)
    {
        try {
            Akun::findOrFail($id)->update([
                'nama_akun' => $request->nama_akun,
            ]);
            return redirect('data-master/akun')->with(['success' => 'Berhasil Update Akun']);
        } catch (\Exception $e) {
            return redirect('data-master/akun')->with(['error' => 'Gagal Update Akun']);
        }

    }


    public function destroy($id)
    {
        // try {
        //     Akun::findOrFail($id)->update([
        //         'nama_akun' => $request->nama_akun,
        //     ]);
        //     return redirect('data-master/akun')->with(['success' => 'Berhasil Update Akun']);
        // } catch (\Exception $e) {
        //     return redirect('data-master/akun')->with(['error' => 'Gagal Update Akun']);
        // }
    }

    public function getakun_select2(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = Akun::where('nama_akun', 'LIKE', '%'.$search.'%')
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


    public function getakun_autocomplete(Request $request)
    {
        $search = $request->get('query');
        $result = Akun::select(['id', 'kategori_akun_id', 'kode_akun', 'nama_akun', 'status_header', 'status_pembayaran', 'default_saldo', 'parent_id'])
                    ->where('nama_akun','LIKE',"%$search%")
                    ->take('5')
                    ->get();
        foreach($result as $data) {
            $output['suggestions'][] = [
                'value'       => $data['kode_akun'].' - '.$data['nama_akun'],
                'id'          => $data['id'],
                'item' => $data,
            ];
        }

        if (! empty($output)) {
            echo json_encode($output);
        }
    }


}
