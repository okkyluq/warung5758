<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetTransaksiPenjualan extends Model
{
    protected $table = 'det_transaksi_penjualan'; 
    protected $primaryKey = 'id';
    protected $fillable = [
    	'transaksi_penjualan_id', 'item_id', 'qty', 'satuan_item_id', 'harga', 'sub_total'
    ];

    public function transaksi_penjualan()
    {
        return $this->belongsTo('App\TransaksiPenjualan', 'transaksi_penjualan_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item', 'item_id', 'id');
    }

    public function satuan_item()
    {
        return $this->belongsTo('App\SatuanItem', 'satuan_item_id', 'id');
    }
}
