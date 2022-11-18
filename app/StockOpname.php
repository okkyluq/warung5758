<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $table = 'stock_opname';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_transaksi', 'tgl_transaksi', 'keterangan'
    ];

    public function det_stock_opname()
    {
        return $this->hasMany('App\DetStockOpname', 'stock_opname_id', 'id');
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
