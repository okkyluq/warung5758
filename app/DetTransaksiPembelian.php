<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetTransaksiPembelian extends Model
{
    protected $table = 'det_transaksi_pembelian'; 
    protected $primaryKey = 'id';
    protected $fillable = [
    	'transaksi_pembelian_id', 'item_id', 'qty', 'satuan_item_id', 'harga', 'sub_total'
    ];

    public function transaksi_pembelian()
    {
        return $this->belongsTo('App\TransaksiPembelian', 'transaksi_pembelian_id', 'id'); 
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
