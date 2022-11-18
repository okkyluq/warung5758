<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class PenerimaanPembayaranCreate extends FormRequest
{
    
    public function authorize()
    {
        return Auth::check(); 
    }

    public function prepareForValidation()
    {
        $this['list_item'] = json_decode($this->list_item, true);
    }

    public function rules()
    {
        return [
            'kode_pembayaran' => 'required|unique:penerimaan_pembayaran,kode_penerimaan_pembayaran',
            'tgl_penerimaan' => 'required',
            'kas' => 'required',
            'list_item' => 'required',
            'total_piutang' => 'required|same:total_pembayaran|numeric|gt:0',
            'total_pembayaran' => 'required|same:total_piutang',
            'kas' => 'required',
            'list_item.*.data_id' => 'required',
            'list_item.*.jumlah_bayar' => 'required|gt:0|numeric',
        ];
    }

    public function messages()
    {
        $message = array();
        
        $message = [
            'kode_pembayaran.required' => 'Kode Pembayaran Wajib Di isi',
            'kode_pembayaran.unique' => 'Kode Pembayaran Sudah Digunakan',
            'tgl_penerimaan.required' => 'Tanggal Penerimaan Wajib Di isi',
            'kas.required' => 'Kas Wajib Dipilih',
            'total_piutang.required' => 'Total Hutang Wajib Terisi',
            'total_piutang.same' => 'Total Hutang Harus Sesuai Dengan Total Pembayaran',
            'total_piutang.numeric' => 'Total Hutang Wajib Angka',
            'total_piutang.gt' => 'Total Hutang Tidak Boleh 0',
            'total_pembayaran.required' => 'Total Pembayaran Wajib Terisi',
            'total_pembayaran.same' => 'Total Pembayaran Harus Sesuai Dengan Total Hutang',
            'total_pembayaran.numeric' => 'Total Pembayaran Wajib Angka',
            'total_pembayaran.gt' => 'Total Pembayaran Tidak Boleh 0',
            'list_item.required' => 'Data Penerimaan Pembayaran Minimal 1 ',
        ];
        foreach ($this['list_item'] as $key => $value) {
            $baris = $key+1;
            $message['list_item.'.$key.'.data_id.required'] = 'Baris Ke-'.$baris.' Item Belum Dipilih ';
            $message['list_item.'.$key.'.jumlah_bayar.required'] = 'Baris Ke-'.$baris.' Jumlah Bayar Wajib Di isi ';
            $message['list_item.'.$key.'.jumlah_bayar.gt'] = 'Baris Ke-'.$baris.' Jumlah Bayar Tidak Boleh 0 ';
            $message['list_item.'.$key.'.jumlah_bayar.numeric'] = 'Baris Ke-'.$baris.' Jumlah Bayar Wajib Angka ';
        }
        
        return $message;
    }
}
