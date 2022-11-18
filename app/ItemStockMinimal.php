<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemStockMinimal extends Model
{
    protected $table = 'item_stock_minimal';
    protected $primaryKey = 'id';
    protected $fillable = [
        'item_id', 'qty_minimal', 'satuan_id'
    ];

    public function satuan()
    {
        return $this->belongsTo('App\Satuan', 'satuan_id', 'id');
    }


}
