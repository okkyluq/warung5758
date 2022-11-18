<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class TransaksiPembelianRequest extends FormRequest
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
        $rules = array();
        $rules = [
            'tgl_pembelian' => 'required',
            'termin' => 'required',
            'kas' => 'required',
            'supplier' => 'required',
            'list_item.*.item_id' => 'required',
            'list_item.*.satuan' => 'required',
            'list_item.*.qty' => 'required|not_in:0',
            'list_item.*.harga' => 'required|not_in:0',
        ];
        if($this->isMethod('PUT')){
            $id = $this->get('id');
            $rules = [ 'no_pembelian' => 'required|unique:transaksi_pembelian,no_pembelian,'.$id];
        } else if($this->isMethod('POST')){
            $rules = [ 'no_pembelian' => 'required|unique:transaksi_pembelian,no_pembelian'];
        }
        return $rules;
    }


}
