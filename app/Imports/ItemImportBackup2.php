<?php

namespace App\Imports;

use App\Item;
use App\Satuan;
use App\SatuanItem;
use Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;



class ItemImport implements ToCollection, SkipsOnFailure, WithStartRow, WithHeadingRow, WithValidation
{
    use Importable, SkipsFailures;

    private $row_sucess = 0;

    public function collection(Collection $rows)
    {   

        Validator::make($rows->toArray(), [
            '*.kode_item' => 'required|unique:item,kode_item',
        ])->validate();

        foreach ($rows as $row) 
        {
            ++$this->row_sucess;
            $satuan_penjualan = Satuan::firstOrCreate(['satuan' => $row['satuan_penjualan'], 'user_id' => Auth::user()->id]);
            $satuan_pembelian = Satuan::firstOrCreate(['satuan' => $row['satuan_pembelian'], 'user_id' => Auth::user()->id]);
            $satuan_stock = Satuan::firstOrCreate(['satuan' => $row['satuan_stock'], 'user_id' => Auth::user()->id]);
            $anu = Satuan::firstOrCreate(['satuan' => $row['satuan_1'], 'user_id' => Auth::user()->id]);
            
            $item = new Item;
            $item->kode_item = $row['kode_item'];
            $item->barcode = $row['barcode'];
            $item->nama_item =  $row['nama_item'];
            $item->tipe_item = $row['tipe_item'] == 'Barang Jadi' ? '0' : $row['tipe_item'] == 'Barang Hasil Produksi' ? '1' : '2';
            $item->opsi_jual = $row['opsi_jual'] == 'Jual' ? '0' : '1';
            $item->satuan_penjualan = $satuan_penjualan->id;
            $item->satuan_pembelian = $satuan_pembelian->id;
            $item->satuan_stock = $satuan_stock->id;
            $item->user_id = Auth::user()->id;
            $item->save();

            $satuan_item = new SatuanItem;
            $satuan_item->satuan_id = $anu->id;
            $satuan_item->item_id = $item->id;
            $satuan_item->lvl = '1';
            $satuan_item->qty_konversi = 1;
            $item->satuan_item()->save($satuan_item);
        }
        
        
        // $satuan_penjualan = Satuan::firstOrCreate(['satuan' => $row['satuan_penjualan'], 'user_id' => Auth::user()->id]);
        // $satuan_pembelian = Satuan::firstOrCreate(['satuan' => $row['satuan_pembelian'], 'user_id' => Auth::user()->id]);
        // $satuan_stock = Satuan::firstOrCreate(['satuan' => $row['satuan_stock'], 'user_id' => Auth::user()->id]);
        // $anu = Satuan::firstOrCreate(['satuan' => $row['satuan_1'], 'user_id' => Auth::user()->id]);
        // ++$this->row_sucess;

        

        // $item->create([
        //     'kode_item' => $row['kode_item'],
        //     'barcode' => $row['barcode'],
        //     'nama_item' => $row['nama_item'],
        //     'tipe_item' => $row['tipe_item'] == 'Barang Jadi' ? '0' : $row['tipe_item'] == 'Barang Hasil Produksi' ? '1' : '2',
        //     'opsi_jual' => $row['opsi_jual'] == 'Jual' ? '0' : '1',
        //     'satuan_penjualan' => $satuan_penjualan->id,
        //     'satuan_pembelian' => $satuan_pembelian->id,
        //     'satuan_stock' => $satuan_stock->id,
        //     'user_id' => Auth::user()->id
        // ]);
        
        // $satuan_item = new SatuanItem;
        // $satuan_item->satuan_id = $anu->id;
        // $satuan_item->lvl = '1';
        // $satuan_item->qty_konversi = 0;
        // $item->satuan_item()->save($satuan_item);



        
        // return new Item([
        //     'kode_item' => $row['kode_item'],
        //     'barcode' => $row['barcode'],
        //     'nama_item' => $row['nama_item'],
        //     'tipe_item' => $row['tipe_item'] == 'Barang Jadi' ? '0' : $row['tipe_item'] == 'Barang Hasil Produksi' ? '1' : '2',
        //     'opsi_jual' => $row['opsi_jual'] == 'Jual' ? '0' : '1',
        //     'satuan_penjualan' => $satuan_penjualan->id,
        //     'satuan_pembelian' => $satuan_pembelian->id,
        //     'satuan_stock' => $satuan_stock->id,
        //     'user_id' => Auth::user()->id
        // ]);
    }

    public function getRowCount(): int
    {
        return $this->row_sucess;
    }


    public function startRow(): int
    {
        return 6;
    }

    public function headingRow(): int
    {
        return 5;
    }

    public function rules(): array
    {
        return [
            'kode_item' => [
                'required',
                'unique:item,kode_item'
            ],
            'barcode' => [
                'nullable',
                'unique:item,barcode'
            ],
            'nama_item' => [
                'nullable',
                'unique:item,nama_item'
            ],
            'tipe_item' => [
                'required'
            ],
            'satuan_penjualan' => [
                'required'
            ],
            'satuan_pembelian' => [
                'required'
            ],
            'satuan_stock' => [
                'required'
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode_item.required' => 'Gagal Karena Kode Item Wajib Di Isi',
            'kode_item.unique' => 'Gagal Karena Kode Item Sudah Digunakan',
            'barcode.required' => 'Gagal Karena Barcode Wajib Di Isi',
            'barcode.unique' => 'Gagal Karena Barcode Sudah Digunakan',
            'nama_item.unique' => 'Gagal Karena Nama Item Sudah Digunakan',
            'tipe_item.required' => 'Gagal Karena Tipe Item Wajib Di Isi',
            'satuan_penjualan.required' => 'Gagal Karena Satuan Penjualan Wajib Di Isi',
            'satuan_pembelian.required' => 'Gagal Karena Satuan Pembelian Wajib Di Isi',
            'satuan_stock.required' => 'Gagal Karena Satuan Stock Wajib Di Isi',
        ];
    }
    
   



}
