<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenerimaanPembayaran extends Model
{
    protected $table = 'penerimaan_pembayaran';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_penerimaan_pembayaran', 'costumer_id', 'tgl_penerimaan_pembayaran', 'total_piutang', 'total_penerimaan_pembayaran', 'kas_id', 'keterangan'
    ];

    public function det_penerimaan_pembayaran()
    {
        return $this->hasMany('App\DetPenerimaanPembayaran', 'penerimaan_pembayaran_id', 'id');
    }

    public function costumer()
    {
        return $this->belongsTo('App\Costumer', 'costumer_id', 'id');
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
