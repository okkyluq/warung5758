<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MutasiKas extends Model
{
    protected $table = 'mutasi_kas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_transaksi', 'tgl_transaksi', 'kas_utama', 'kas_tujuan', 'nominal_utama', 'nominal_tujuan'
    ];

    public function k_utama()
    {
        return $this->belongsTo('App\Kas', 'kas_utama', 'id');
    }

    public function k_tujuan()
    {
        return $this->belongsTo('App\Kas', 'kas_tujuan', 'id');
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
