<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetStockOpname extends Model
{
    protected $table = 'det_stock_opname';
    protected $primaryKey = 'id';
    protected $fillable = [
        'stock_opname_id', 'item_id', 'qty_opname', 'qty_program', 'qty_selisih', 'satuan_item_id'
    ];

    public function stock_opname()
    {
        return $this->belongsTo('App\StockOpname', 'stock_opname_id', 'id');
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
