<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemCreate extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this['list_satuan'] = json_decode($this->list_satuan, true);
    }


    public function rules()
    {
        return [
            'kode' => 'required|unique:item,kode_item',
            'barcode' => 'nullable|unique:item,barcode',
            'nama_item' => 'required|unique:item,nama_item',
            'tipe_item' => 'required',
            'kategori_item' => 'required',
            'opsi_jual' => 'required',
            'satuan_penjualan' => 'required',
            'satuan_pembelian' => 'required',
            'satuan_stock' => 'required',
            'pembelian' => 'required',
            'hpp' => 'required',
            'penjualan' => 'required',
            'retur_penjualan' => 'required',
            'gambar_item' => 'sometimes|mimes:jpeg,png,jpg',
            'list_satuan' => 'required',
            'list_satuan.*.satuan_id' => 'required',
            'list_satuan.*.qty_konversi' => 'required',
        ];
    }

    public function messages()
    {
        $messages = array();
        $messages = [
            'kode.required' => 'Kode Item Wajib Diisi !',
            'kode.unique' => 'Kode Item Sudah Digunakan !',
            'barcode.unique' => 'Barcode Item Sudah Digunakan !',
            'nama_item.required' => 'Nama Item Wajib Diisi',
            'nama_item.unique' => 'Nama Item Sudah Digunakan',
            'tipe_item.required' => 'Tipe Item Wajib Dipilih',
            'kategori_item.required' => 'Kategori Item Wajib Dipilih',
            'opsi_jual.required' => 'Opsi Jual Item Wajib Checklist',
            'satuan_penjualan.required' => 'Satuan Penjualan Item Wajib Diisi',
            'satuan_pembelian.required' => 'Satuan Pembelian Item Wajib Diisi',
            'satuan_stock.required' => 'Satuan Stock Item Wajib Diisi',
            'pembelian.required' => 'Akun Pembelian Item Wajib Dipilih',
            'hpp.required' => 'Akun HPP Item Wajib Dipilih',
            'penjualan.required' => 'Akun Penjualan Item Wajib Dipilih',
            'retur_penjualan.required' => 'Akun Retur Penjualan Item Wajib Dipilih',
            'gambar_item.mimes' => 'File Wajib Bereksistensi JPEG, JPG & PNG',
        ];

        foreach ($this['list_satuan'] as $key => $value) {
            $baris = $key+1;
            $messages['list_satuan.'.$key.'.satuan_id.required'] = 'Baris Ke-'.$baris.' Satuan Belum Dipilih ';
            $messages['list_satuan.'.$key.'.qty_konversi.required'] = 'Baris Ke-'.$baris.' Qty Konversi Satuan Wajib Diisi';
        }

        return $messages;
    }
}
