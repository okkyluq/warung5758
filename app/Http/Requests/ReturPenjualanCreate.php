<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturPenjualanCreate extends FormRequest
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
            'tgl_set' => 'required',
            'costumer' => 'required',
            'list_item' => 'required',
            'list_item.*.item_id' => 'required',
            'list_item.*.qty' => 'required',
            'list_item.*.satuan_item_id' => 'required',
            'list_item.*.harga' => 'required',
            'list_item.*.sub_total' => 'required',
        ];
    }

    public function messages()
    {
        $messages = array();

        $messages = [
            'kode_transaksi.required' => 'Kode Transaksi Wajib terisi !',
            'tgl_set.required' => 'Tgl Transaksi Wajib terisi !',
            'costumer.required' => 'Costumer Wajib Dipilih !',
            'list_item.required' => 'Item Belum Terpilih',
        ];

        foreach ($this->list_item as $key => $value) {
            $baris = $key +1;
            $messages['list_item.'.$key.'.item_id.required'] = 'Baris Ke-'.$baris.' Item Belum Dipilih ';
            $messages['list_item.'.$key.'.qty.required'] = 'Baris Ke-'.$baris.' Qty Belum Disi ';
            $messages['list_item.'.$key.'.satuan_item_id.required'] = 'Baris Ke-'.$baris.' Satuan Item Belum Terisi ';
            $messages['list_item.'.$key.'.harga.required'] = 'Baris Ke-'.$baris.' Harga Item Belum Diisi ';
            $messages['list_item.'.$key.'.sub_total.required'] = 'Baris Ke-'.$baris.' Sub Total Belum Terisi ';
        }

        return $messages;


    }
}
