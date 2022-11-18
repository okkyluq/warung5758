<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PengaturanSistem;

class PengaturanSistemController extends Controller
{

    public function index()
    {
        $pengaturan = PengaturanSistem::all();
        return view('backoffice.page.pengaturan_sistem.index', [
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
                $setting = explode('-', $value);
                $json =  [
                    'key'   => $setting[0],
                    'label' => $setting[1]
                ];

                PengaturanSistem::updateOrCreate(
                    ['setting' => $key],
                    [
                        'value' => json_encode($json)
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

    public function get_pengaturan(Request $request)
    {

    }

}
