<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturPembelian extends Model
{
    protected $table = 'retur_pembelian';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_transaksi', 'tgl_transaksi', 'supplier_id', 'keterangan'
    ];

    public function det_retur_pembelian()
    {
        return $this->hasMany('App\DetReturPembelian', 'retur_pembelian_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id', 'id');
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
