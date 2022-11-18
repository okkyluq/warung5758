<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_pembayaran', 'supplier_id', 'kas_id', 'tgl_pembayaran', 'total_hutang', 'total_pembayaran'
    ];

    public function det_pembayaran()
    {
        return $this->hasMany('App\DetPembayaran', 'pembayaran_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id', 'id');
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
