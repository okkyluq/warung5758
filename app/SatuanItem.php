<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SatuanItem extends Model
{
    protected $table = 'satuan_item';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'item_id', 'satuan_id', 'lvl', 'qty_konversi', 'harga_jual', 'harga_beli'
    ];


    public function satuan()
    {
        return $this->belongsTo('App\Satuan', 'satuan_id', 'id');
    }


}
