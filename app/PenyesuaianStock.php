<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyesuaianStock extends Model
{
    protected $table = 'penyesuaian_stock';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_transaksi', 'tgl_set'
    ];

    public function det_penyesuaian_stock()
    {
        return $this->hasMany('App\DetPenyesuaianStock', 'penyesuaian_stock_id', 'id');
    }

    public function history_item()
    {
        return $this->morphMany('App\HistoryItem', 'historyable');
    }

    public function history_jurnal()
    {
        return $this->morphMany('App\HistoryJurnal', 'historyjurnalable');
    }
}
