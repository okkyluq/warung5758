<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockOpnameCreate extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this['list_item'] = json_decode($this->list_item, true);
    }

    public function rules()
    {
        return [
            'kode_transaksi' => 'required', 
            'tgl_transaksi' => 'required', 
            'list_item' => 'required',
            'list_item.*.item_id' => 'required',
            'list_item.*.satuan' => 'required',
            'list_item.*.qty_opname' => 'required'
        ];
    }

    public function messages()
    {
        $message = array();
        
        $message = [
            'kode_transaksi.required' => 'Kode Transaksi Wajib Terisi',
            'tgl_set.required' => 'Tanggal Wajib Terisi',
            'list_item.required' => 'Item Minimal 1',
        ];

        foreach ($this->list_item as $key => $value) {
            $baris = $key+1;
            $message['list_item.'.$key.'.item_id.required'] = 'Baris Ke-'.$baris.' Item Belum Dipilih ';
            $message['list_item.'.$key.'.satuan.required'] = 'Baris Ke-'.$baris.' Satuan Belum Diset ';
            $message['list_item.'.$key.'.qty_opname.required'] = 'Baris Ke-'.$baris.' Qty Opname Tidak Boleh Kosong ';
        }
        
        return $message;
    }
}
