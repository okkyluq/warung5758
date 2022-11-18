<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProduksiBom extends Model
{
    protected $table = 'produksi_bom';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'no_produksi', 'tgl_produksi', 'bom_id', 'qty', 'user_id' 
    ];

    public function det_produksi_bom()
    {
        return $this->hasMany('App\DetProduksiBom', 'produksi_bom_id', 'id');
    }
 
    public function history_item() 
    {
        return $this->morphMany('App\HistoryItem', 'historyable');
    }

    public function bom()
    {
        return $this->belongsTo('App\BillOfMaterial', 'bom_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
