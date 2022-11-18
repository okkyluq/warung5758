<?php

namespace App\Imports;

use App\Item;
use App\Satuan;
use App\SatuanItem;
use App\ItemStockMinimal;
use App\PengaturanAkun;
use App\ItemAkutansi;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class ItemImport3 implements OnEachRow , SkipsOnFailure, WithStartRow, WithHeadingRow, WithValidation
{
    use Importable, SkipsFailures;
    private $row_sucess = 0;

    public function onRow(Row $row)
    {

        $rowIndex = $row->getIndex();
        $row      = $row->toArray();

        $satuan_penjualan = Satuan::firstOrCreate(['satuan' => $row['satuan_penjualan'], 'user_id' => Auth::user()->id]);
        $satuan_pembelian = Satuan::firstOrCreate(['satuan' => $row['satuan_pembelian'], 'user_id' => Auth::user()->id]);
        $satuan_stock = Satuan::firstOrCreate(['satuan' => $row['satuan_stock'], 'user_id' => Auth::user()->id]);

        ++$this->row_sucess;

        $tipe_item = ['BARANG JADI', 'BARANG HASIL PRODUKSI', 'BAHAN BAKU'];
        $kategori_item = ['NON MAKANAN DAN MINUMAN', 'MAKANAN', 'MINUMAN'];
        $opsi_jual = ['TIDAK', 'YA'];

        $item = Item::create([
            'kode_item' => $row['kode_item'],
            'barcode' => $row['barcode'],
            'nama_item' => $row['nama_item'],
            'tipe_item' => strval(array_search($row['tipe_item'], $tipe_item)),
            'kategori_item' => strval(array_search($row['kategori_item'], $kategori_item)),
            'opsi_jual' => strval(array_search($row['opsi_jual'], $opsi_jual)),
            'satuan_penjualan' => $satuan_penjualan->id,
            'satuan_pembelian' => $satuan_pembelian->id,
            'satuan_stock' => $satuan_stock->id,
            'user_id' => Auth::user()->id
        ]);

        if($row['qty_minimal'] != null){
            $satuan_minimal = Satuan::firstOrCreate(['satuan' => $row['satuan_qty_minimal'], 'user_id' => Auth::user()->id]);
            $item->item_stock_minimal()->save(
                new ItemStockMinimal([
                    'qty_minimal' => $row['qty_minimal'],
                    'satuan_id' => $satuan_minimal->id
                ])
            );
        }

        $akuntasi_item = new ItemAkutansi([
            'akun_pembelian' => PengaturanAkun::select(['id'])->where('setting', 'inventory')->first()->id,
            'akun_hpp' => PengaturanAkun::select(['id'])->where('setting', 'hpp')->first()->id,
            'akun_penjualan' => PengaturanAkun::select(['id'])->where('setting', 'penjualan')->first()->id,
            'akun_retur_penjualan' => PengaturanAkun::select(['id'])->where('setting', 'retur_jual')->first()->id
        ]);

        $item->item_akutansi()->save($akuntasi_item);


        $satuan_1 = Satuan::firstOrCreate(['satuan' => $row['satuan_1'], 'user_id' => Auth::user()->id]);
        $satuan_item = new SatuanItem;
        $satuan_item->satuan_id = $satuan_1->id;
        $satuan_item->lvl = '1';
        $satuan_item->qty_konversi = 1;
        $satuan_item->harga_jual = $row['satuan_1_harga_jual'];
        $satuan_item->harga_beli = $row['satuan_1_harga_beli'];
        $item->satuan_item()->save($satuan_item);

        $satuan_2 = Satuan::firstOrCreate(['satuan' => $row['satuan_2'], 'user_id' => Auth::user()->id]);
        $satuan_item = new SatuanItem;
        $satuan_item->satuan_id = $satuan_2->id;
        $satuan_item->lvl = '2';
        $satuan_item->qty_konversi = $row['satuan_2_qty_konversi'];
        $satuan_item->harga_jual = $row['satuan_2_harga_jual'];
        $satuan_item->harga_beli = $row['satuan_2_harga_beli'];
        $item->satuan_item()->save($satuan_item);

        $satuan_3 = Satuan::firstOrCreate(['satuan' => $row['satuan_3'], 'user_id' => Auth::user()->id]);
        $satuan_item = new SatuanItem;
        $satuan_item->satuan_id = $satuan_3->id;
        $satuan_item->lvl = '3';
        $satuan_item->qty_konversi = $row['satuan_3_qty_konversi'];
        $satuan_item->harga_jual = $row['satuan_3_harga_jual'];
        $satuan_item->harga_beli = $row['satuan_3_harga_beli'];
        $item->satuan_item()->save($satuan_item);

    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
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
        return 4;
    }

    public function prepareForValidation($data, $index)
    {
        return $data;
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
                'required',
                'in:BARANG JADI,BARANG HASIL PRODUKSI,BAHAN BAKU'
            ],
            'kategori_item' => [
                'required',
                'in:NON MAKANAN DAN MINUMAN,MAKANAN,MINUMAN'
            ],
            'opsi_jual' => [
                'required',
                'in:YA,TIDAK'
            ],
            'satuan_1' => [
                'required',
            ],
            'satuan_1_harga_jual' => [
                'nullable',
                'numeric'
            ],
            'satuan_1_harga_beli' => [
                'nullable',
                'numeric'
            ],

            'satuan_2' => [
                'required',
            ],
            'satuan_2_harga_jual' => [
                'nullable',
                'numeric'
            ],
            'satuan_2_harga_beli' => [
                'nullable',
                'numeric'
            ],
            'satuan_2_qty_konversi' => [
                'required',
                'numeric'
            ],

            'satuan_3' => [
                'required',
            ],
            'satuan_3_harga_jual' => [
                'nullable',
                'numeric'
            ],
            'satuan_3_harga_beli' => [
                'nullable',
                'numeric'
            ],
            'satuan_3_qty_konversi' => [
                'required',
                'numeric'
            ],

            'qty_minimal' => [
                'nullable',
                'integer'
            ],
            'satuan_qty_minimal' => [
                'nullable',
                'required_if:qty_minimal,!=,""'
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
            'kode_item.required' => '- Gagal Karena Kode Item Wajib Di Isi',
            'kode_item.unique' => '- Gagal Karena Kode Item Sudah Digunakan',
            'barcode.required' => '- Gagal Karena Barcode Wajib Di Isi',
            'barcode.unique' => '- Gagal Karena Barcode Sudah Digunakan',
            'nama_item.unique' => '- Gagal Karena Nama Item Sudah Digunakan',
            'tipe_item.required' => '- Gagal Karena Tipe Item Wajib Di Isi',
            'tipe_item.in' => '- Gagal Karena Tipe Item Tidak Sesuai Dengan Opsi',
            'kategori_item.required' => '- Gagal Karena Kategori Item Wajib Di Isi',
            'kategori_item.in' => '- Gagal Karena Kategori Item Tidak Sesuai Dengan Opsi',
            'opsi_jual.required' => '- Gagal Karena Opsi Jual Item Wajib Di Isi',
            'opsi_jual.in' => '- Gagal Karena Opsi Jual Item Tidak Sesuai Dengan Opsi',

            'satuan_1.required' => '- Gagal Karena Satuan 1 Wajib Di Isi',
            'satuan_1_harga_jual.numeric' => '- Gagal Karena Harga Jual Satuan 1 Harus Menggunakan Angka',
            'satuan_1_harga_beli.numeric' => '- Gagal Karena Harga beli Satuan 1 Harus Menggunakan Angka',

            'satuan_2.required' => '- Gagal Karena Satuan 2 Wajib Di Isi',
            'satuan_2_harga_jual.numeric' => '- Gagal Karena Harga Jual Satuan 2 Harus Menggunakan Angka',
            'satuan_2_harga_beli.numeric' => '- Gagal Karena Harga beli Satuan 2 Harus Menggunakan Angka',
            'satuan_2_qty_konversi.required' => '- Gagal Karena Qty Konversi Satuan 2 Wajib Di Isi',
            'satuan_2_qty_konversi.numeric' => '- Gagal Karena Qty Konversi Satuan 2 Harus Menggunakan Angka',

            'satuan_3.required' => '- Gagal Karena Satuan 3 Wajib Di Isi',
            'satuan_3_harga_jual.numeric' => '- Gagal Karena Harga Jual Satuan 3 Harus Menggunakan Angka',
            'satuan_3_harga_beli.numeric' => '- Gagal Karena Harga beli Satuan 3 Harus Menggunakan Angka',
            'satuan_3_qty_konversi.required' => '- Gagal Karena Qty Konversi Satuan 3 Wajib Di Isi',
            'satuan_3_qty_konversi.numeric' => '- Gagal Karena Qty Konversi Satuan 3 Harus Menggunakan Angka',

            'qty_minimal.integer' => '- Gagal Karena Qty Minimal Harus Menggunakan Angka',
            'satuan_qty_minimal.required_if' => '- Gagal Karena Satuan Qty Minimal Wajib Tersisi Jika Qty Terisi',
            'satuan_penjualan.required' => '- Gagal Karena Satuan Penjualan Wajib Di Isi',
            'satuan_pembelian.required' => '- Gagal Karena Satuan Pembelian Wajib Di Isi',
            'satuan_stock.required' => '- Gagal Karena Satuan Stock Wajib Di Isi',
        ];
    }
}
