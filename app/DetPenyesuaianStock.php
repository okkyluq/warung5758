<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetPenyesuaianStock extends Model
{
    protected $table = 'det_penyesuaian_stock';
    protected $primaryKey = 'id';
    protected $fillable = [
        'penyesuaian_stock_id', 'item_id', 'qty', 'satuan_item_id'
    ];

    public function penyesuaian_stock()
    {
        return $this->belongsTo('App\PenyesuaianStock', 'penyesuaian_stock_id', 'id');
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
