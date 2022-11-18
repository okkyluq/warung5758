<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetProduksiBom extends Model
{
    protected $table = 'det_produksi_bom';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'produksi_bom_id', 'item_id', 'satuan_item_id', 'qty'
    ];

    public function produksi_bom()
    {
        return $this->belongsTo('App\ProduksiBom', 'produksi_bom_id', 'id');
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
