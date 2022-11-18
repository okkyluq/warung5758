<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Item extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    protected $table = 'item';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'kode_item', 'barcode', 'nama_item', 'tipe_item', 'kategori_item', 'opsi_jual', 'satuan_penjualan', 'satuan_pembelian', 'satuan_stock', 'gambar_item', 'user_id'
    ];

    // protected $appends = ['tipe_item_text'];

    // public function getTipeItemTextAttribute()
    // {
    //     $tipe_item = ['Barang Jadi', 'Barang Hasil Produksi', 'Bahan Baku'];
    //     return $tipe_item[$this->attributes['tipe_item']];
    // }

    public function satuan_item()
    {
    	return $this->hasMany('App\SatuanItem', 'item_id', 'id');
    }


    public function get_satuan_penjualan()
    {
    	return $this->belongsTo('App\Satuan', 'satuan_penjualan', 'id');
    }

    public function get_satuan_pembelian()
    {
    	return $this->belongsTo('App\Satuan', 'satuan_pembelian', 'id');
    }

    public function get_satuan_stock()
    {
    	return $this->belongsTo('App\Satuan', 'satuan_stock', 'id');
    }

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function item_akutansi()
    {
        return $this->hasOne('App\ItemAkutansi', 'item_id', 'id');
    }

    public function item_stock_minimal()
    {
        return $this->hasOne('App\ItemStockMinimal', 'item_id', 'id');
    }

    public function history_item()
    {
        return $this->hasMany('App\HistoryItem', 'item_id', 'id');
    }

    public function det_transaksi_pembelian()
    {
        return $this->hasMany('App\DetTransaksiPembelian', 'item_id', 'id');
    }

    public function bom()
    {
        return $this->hasOne('App\BillOfMaterial', 'item_id', 'id');
    }

    public function hpp($satuan_id)
    {
        // mengambil total cost dari bom berdasarkan item bersangkutan
        $biaya_produksi = $this->bom()->first()->qty ?? 0;
        // mengambil nilai rata dari harga pembelian berdasarkan item terkait
        $harga_rata_rata_pembelian = $this->det_transaksi_pembelian()->select(DB::raw("round(AVG(harga), 2) as hpp"))->where('satuan_item_id', $satuan_id)->first()->hpp ?? 0;
        // return $this->det_transaksi_pembelian()->select(DB::raw("round(AVG(harga), 2) as hpp"))->where('satuan_item_id', $satuan_id)->first()->hpp;
        //sisa kalkulasi dari nilai bom dan nilai rata2 harga pembelian
        return $biaya_produksi + $harga_rata_rata_pembelian;
    }

    public function det_bom()
    {
        return $this->hasManyThrough(
            'App\DetBillOfMaterial',
            'App\BillOfMaterial',
            'item_id',
            'bom_id',
            'id',
            'id'
        );
    }

    public function get_hpp($satuan_id)
    {
        $qty_konversi = $this->satuan_item()->where('id', $satuan_id)->first()->qty_konversi;
        $hpp = $this->det_transaksi_pembelian()->select(DB::raw("round(AVG(harga), 2) as hpp"))->first()->hpp ?? 0;
        return $hpp / floatval($qty_konversi);
        // return $qty_konversi;

    }

    public function get_stock_satuan($satuan_id)
    {
        return $this->satuan_item()->where('satuan_id', $satuan_id)->first()->qty_konversi;

        return $this->det_bom()->withCount([
            'det_transaksi_pembelian as hpp' => function($query){
                 $query->select(DB::raw("round(AVG(harga), 2) as total_hpp"));
            }
        ])->first()->hpp ?? 0;
    }



    public function tester()
    {
        return 1;
    }



    // public function get_stock()
    // {
    //     /*
    //     $item = Item::with(['get_satuan_stock', 'satuan_item'])->withCount(['history_item' => function($query){
    //         $query->select(DB::raw("SUM(history_item.qty * satuan_item.qty_konversi) as stock"))
    //                 ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
    //                 ->groupBy('history_item.item_id');
    //     }]);
    //     */

    //     return 1;
    // }

    // ->withCount([
    //     'det_transaksi_pembelian as hpp' => function($query){
    //         $query->select(DB::raw("round(AVG(harga), 2) as paidsum"));
    //     }
    // ])


}
