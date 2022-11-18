<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class TransaksiPenjualanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this['check'] = $this->isMethod('PUT') ? 'PUT' : 'POST';
    }

    public function rules()
    {
        if($this->isMethod('PUT')){
            $id = $this->get('id');
            $rules = [
                'no_penjualan' => 'required|unique:transaksi_penjualan,no_penjualan,'.$id,
                'tgl_penjualan' => 'required',
                'termin' => 'required',
                'kas' => 'required',
                'costumer' => 'required',
                'list_item.*.item' => 'required',
                'list_item.*.satuan' => 'required',
                'list_item.*.qty' => 'required|not_in:0',
                'list_item.*.harga' => 'required|not_in:0',
            ];
        } else if($this->isMethod('POST')){
            $rules = [
                'no_penjualan' => 'required|unique:transaksi_penjualan,no_penjualan',
                'tgl_penjualan' => 'required',
                'termin' => 'required',
                'kas' => 'required',
                'costumer' => 'required',
                'list_item' => 'required',
                'list_item.*.item_id' => 'required',
                'list_item.*.satuan' => 'required',
                'list_item.*.qty' => 'required|not_in:0',
                'list_item.*.harga' => 'required|not_in:0',
            ];
        }
        return $rules;
    }

    public function messages()
    {
        $message = array();
        $message = [
            'no_penjualan.required' => 'No Penjualan Wajib Di isi',
            'no_penjualan.unique' => 'No Penjualan Sudah Digunakan',
            'costumer.required' => 'Costumer Belum Dipilih',
            'tgl_penjualan.required' => 'Tanggal Penjualan Wajib Di isi',
            'termin.required' => 'Termin Wajib Dipilih',
            'kas.required' => 'Kas Wajib Dipilih',
            'list_item.required' => 'Item Minimal 1 ',
        ];
        foreach ($this->list_item as $key => $value) {
            $baris = $key+1;
            $message['list_item.'.$key.'.item_id.required'] = 'Baris Ke-'.$baris.' Item Belum Dipilih ';
            $message['list_item.'.$key.'.satuan.required'] = 'Baris Ke-'.$baris.' Satuan Belum Diset ';
            $message['list_item.'.$key.'.qty.required'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
            $message['list_item.'.$key.'.qty.not_in'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
            $message['list_item.'.$key.'.harga.required'] = 'Baris Ke-'.$baris.' Harga Wajib Di isi ';
            $message['list_item.'.$key.'.harga.not_in'] = 'Baris Ke-'.$baris.' Item Harus Memiliki Harga ';
        }

        return $message;
    }
}
