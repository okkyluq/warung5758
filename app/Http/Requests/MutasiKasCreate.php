<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MutasiKasCreate extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'kode_transaksi' => 'required',
            'tgl_transaksi' => 'required',
            'kas_utama' => 'required',
            'jumlah_1' => 'required',
            'kas_tujuan' => 'required',
            'jumlah_2' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'kode_transaksi.required' => 'Kode Transaksi Wajib DIisi !',
            'tgl_transaksi.required' => 'Tgl Transaksi Wajib Diisi !',
            'kas_utama.required' => 'Kas Utama Wajib DIpilih',
            'kas_tujuan.required' => 'Kas Tujuan Wajib Dipilih',
            'jumlah_1.required' => 'Jumlah Kas Utama Wajib Di Isi',
            'jumlah_2.required' => 'Jumlah Kas Tujuan Wajib Di Isi',
        ];
    }
}
