<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetReturPenjualan extends Model
{
    protected $table = 'det_retur_penjualan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'retur_penjualan_id', 'kas_id', 'item_id', 'qty', 'satuan_item_id', 'harga', 'sub_total'
    ];

    public function retur_penjualan()
    {
        return $this->belongsTo('App\ReturPenjualan', 'retur_penjualan_id', 'id');
        
    }

    public function item()
    {
        return $this->belongsTo('App\Item', 'item_id', 'id');
    }

    public function kas()
    {
        return $this->belongsTo('App\Kas', 'kasi_id', 'id');
    }

    public function satuan_item()
    {
        return $this->belongsTo('App\SatuanItem', 'satuan_item_id', 'id');
    }
}
