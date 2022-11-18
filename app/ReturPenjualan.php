<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturPenjualan extends Model
{
    protected $table = 'retur_penjualan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_transaksi', 'tgl_transaksi', 'costumer_id', 'keterangan'
    ];

    public function det_retur_penjualan()
    {
        return $this->hasMany('App\DetReturPenjualan', 'retur_penjualan_id', 'id');
    }

    public function costumer()
    {
        return $this->belongsTo('App\Costumer', 'costumer_id', 'id');
    }
    
    public function history_item()
    {
        return $this->morphMany('App\HistoryItem', 'historyable');
    }

    public function history_kas()
    {
        return $this->morphMany('App\HistoryKas', 'historykasable');
    }

    public function history_jurnal()
    {
        return $this->morphMany('App\HistoryJurnal', 'historyjurnalable');
    }
}
