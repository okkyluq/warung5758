<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TransaksiPenjualanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check(); 
    }

    protected function prepareForValidation()
    {
        $this['list_item'] = json_decode($this->list_item, true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'no_penjualan' => 'required|unique:transaksi_penjualan,no_penjualan',
            'tgl_penjualan' => 'required',
            'costumer' => 'required',
            'termin' => 'required',
            'kas' => 'required',
            'list_item' => 'required',
            'list_item.*.item_id' => 'required',
            'list_item.*.satuan' => 'required',
            'list_item.*.qty' => 'required|gt:0',
            'list_item.*.harga' => 'required|gt:0',
        ];
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
            $message['list_item.'.$key.'.qty.gt'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
            $message['list_item.'.$key.'.harga.required'] = 'Baris Ke-'.$baris.' Harga Wajib Di isi ';
            $message['list_item.'.$key.'.harga.gt'] = 'Baris Ke-'.$baris.' Item Harus Memiliki Harga ';
        }
        
        return $message;
    }
}
