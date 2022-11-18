<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetBillOfMaterial extends Model
{
    protected $table = 'det_bom';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'bom_id', 'item_id', 'satuan_item_id', 'qty', 'cost'
    ];

    public function bom()
    {
        return $this->belongsTo('App\BillOfMaterial', 'bom_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item', 'item_id', 'id');
    }

    public function satuan_item()
    {
        return $this->belongsTo('App\SatuanItem', 'satuan_item_id', 'id');
    }

    public function det_transaksi_pembelian()
    {
        return $this->hasMany('App\DetTransaksiPembelian', 'item_id', 'item_id');
    }
}
