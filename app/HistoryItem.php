<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryItem extends Model
{
    protected $table = 'history_item';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'item_id', 'satuan_item_id', 'qty', 'historyable_id', 'historyable_type', 'status_in_out', 'harga'
    ];

    public function historyable()
    {
        return $this->morphTo();
    }

    public function item() 
    {
        return $this->hasOne('App\Item', 'item_id', 'id');
    }

    public function satuan_item()
    {
        return $this->belongsTo('App\SatuanItem', 'satuan_item_id', 'id');
    }

    public function get_stock()
    {
        return 1;
    }

    public function historyItemPembelian()
    {
        return $this->historyable()->where('historyable_type', "App\TransaksiPembelian")->get();
    }
}
