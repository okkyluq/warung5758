<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
        if($this->isMethod('PUT')){
            $id = $this->get('id');
            $rules = [
                'kode_item' => 'required|unique:item,kode_item,'.$id,
                'barcode' => 'nullable|unique:item,barcode,'.$id,
                'nama_item' => 'required|unique:item,nama_item,'.$id,
                'tipe_item' => 'required',
                'kategori_item' => 'required',
                'satuan_jual' => 'required',
                'satuan_beli' => 'required',
                'satuan_stock' => 'required',
                'akun_pembelian' => 'required|numeric',
                'akun_hpp' => 'required|numeric',
                'akun_penjualan' => 'required|numeric',
                'akun_retur_penjualan' => 'required|numeric',
                'satuan_qty_minmal' => 'required_unless:qty_minimal, 0',
                'satuan.*.satuan' => 'required',

            ];
        } else if($this->isMethod('POST')){
            $rules = [
                'kode_item' => 'required|unique:item,kode_item',
                'barcode' => 'nullable|unique:item,barcode',
                'nama_item' => 'required|unique:item,nama_item',
                'tipe_item' => 'required',
                'kategori_item' => 'required',
                'satuan_jual' => 'required',
                'satuan_beli' => 'required',
                'satuan_stock' => 'required',
                'akun_pembelian' => 'required|numeric',
                'akun_hpp' => 'required|numeric',
                'akun_penjualan' => 'required|numeric',
                'akun_retur_penjualan' => 'required|numeric',
                'satuan_qty_minmal' => 'required_unless:qty_minimal, 0',
                'satuan.*.satuan' => 'required',
            ];
        }

        return $rules;


    }

    public function messages()
    {

        $messages = array();
        $messages = [
            'kode_item.required' => 'Kode Item Wajib Diisi !',
            'kode_item.unique' => 'Kode Item Sudah Digunakan !',
            'barcode.unique' => 'Barcode Item Sudah Digunakan !',
            'nama_item.required' => 'Nama Item Wajib Diisi',
            'nama_item.unique' => 'Nama Item Sudah Digunakan',
            'tipe_item.required' => 'Tipe Item Wajib Dipilih',
            'kategori_item.required' => 'Kategori Item Wajib Dipilih',
            'satuan_jual.required' => 'Satuan Penjualan Wajib Diisi',
            'satuan_beli.required' => 'Satuan Pembelian Wajib Diisi',
            'satuan_stock.required' => 'Satuan Stock Wajib Diisi',
            'akun_pembelian.required' => 'Akun Pembelian Wajib Dipilih',
            'akun_hpp.required' => 'Akun HPP Wajib Dipilih',
            'akun_penjualan.required' => 'Akun Penjualan Wajib Dipilih',
            'akun_retur_penjualan.required' => 'Akun Retur Penjualan Wajib Dipilih',
            'satuan_qty_minmal.required_unless' => 'Satuan Wajib Dipilih Jika Qty Minimal Terisi',
            'satuan.*.satuan.required' => 'Satuan Belum Dipilih',
            'satuan.*.satuan.qty_konversi' => 'Qty Belum Terisi',
        ];
        return $messages;
    }
}
