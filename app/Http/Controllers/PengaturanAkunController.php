<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Akun;
use App\PengaturanAkun;

class PengaturanAkunController extends Controller
{

    public function index()
    {
        $pengaturan = PengaturanAkun::all();
        return view('backoffice.page.pengaturan_akun.index', [
            'pengaturan' => $pengaturan
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->request->remove('_token');
        $all = $request->all();
        if (count($all) > 0) {
            foreach ($all as $key => $value) {
                $akun = Akun::find($value);
                $pengaturan = PengaturanAkun::updateOrCreate(
                    ['setting' => $key],
                    [
                        'akun_id' => $akun['id'],
                        'value' => $akun['nama_akun'],
                        'kode' => $akun['kode_akun']
                    ]
                );
            }
            return redirect()->back()->with(['success' => 'Berhasil Menyimpan Pengaturan']);
        } else {
            return redirect()->back();
        }
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
}
