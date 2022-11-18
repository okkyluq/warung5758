<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    protected $table = 'bom';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'no_bom', 'item_id', 'satuan_item_id', 'qty', 'user_id', 'total_cost'
    ];

    public function det_bom()
    {
        return $this->hasMany('App\DetBillOfMaterial', 'bom_id', 'id');
    }


    public function history_item()
    {
        return $this->morphMany('App\HistoryItem', 'historyable');
    }

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item', 'item_id', 'id');
    }

    public function satuan_item() 
    {
    	return $this->belongsTo('App\SatuanItem', 'satuan_item_id', 'id');
    }

    public function produksi() 
    {
        return $this->hasOne('App\ProduksiBom', 'bom_id', 'id');
    }


}
