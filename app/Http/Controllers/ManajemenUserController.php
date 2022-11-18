<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\User;


class ManajemenUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = User::where('tipe_akun', '!=', '4')->where('tipe_akun', '!=', '1')->orderBy('created_at', 'desc');
            return Datatables::of($user)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    return '
                         <div style="padding-left:5px;">
                                <a href="'.url("pengaturan/manajemen-user/".$data->id)."/edit".'"><i class="icon-pencil5 text-success"></i></a>
                                <button class="btn-link" id="button_delete" data-id="'.$data->id.'"  type="button"><i class="icon-trash text-danger"></i></button>
                        </div>
                    ';
                })
                ->rawColumns(['action', 'satuan'])
                ->make(true);
        }
        return view('backoffice.page.manajemen_user.index');
    }

    public function create()
    {
        return view('backoffice.page.manajemen_user.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|confirmed',
        ], [
            'name.required' => 'Nama Wajib Diisi!',
            'username.required' => 'Username Wajib Diisi',
            'username.unique' => 'Username Sudah Digunakan',
            'password.required' => 'Password Wajib Diisi',
            'password.confirmed' => 'Konfirmasi Password Tidak Cocok',
        ]);

        try {
            DB::beginTransaction();

            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'tipe_akun' => '0',
            ]);

            DB::commit();
            return redirect('pengaturan/manajemen-user')->with('success', 'Berhasil Menambahkan Pengguna');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('pengaturan/manajemen-user')->with('failed', 'Gagal Menambahkan Pengguna');
        }


        return response()->json($request->all());
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backoffice.page.manajemen_user.edit', [
            'user' =>$user
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users,username,'.$id,
            'password' => 'required|confirmed',
        ], [
            'name.required' => 'Nama Wajib Diisi!',
            'username.required' => 'Username Wajib Diisi',
            'username.unique' => 'Username Sudah Digunakan',
            'password.required' => 'Password Wajib Diisi',
            'password.confirmed' => 'Konfirmasi Password Tidak Cocok',
        ]);

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'tipe_akun' => '0',
            ]);

            DB::commit();
            return redirect('pengaturan/manajemen-user')->with('success', 'Berhasil Edit Pengguna');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('pengaturan/manajemen-user')->with('failed', 'Gagal Edit Pengguna');
        }

    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        try {
            DB::beginTransaction();
            $user->delete();
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
}
