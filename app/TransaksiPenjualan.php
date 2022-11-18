<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    protected $table = 'transaksi_penjualan';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'no_penjualan', 'costumer_id', 'tgl_penjualan', 'user_id', 'termin', 'kas_id', 'total', 'jumlah_hari_tempo', 'tgl_jatuh_tempo', 'uang_muka'
    ];

    public function det_transaksi_penjualan()
    {
        return $this->hasMany('App\DetTransaksiPenjualan', 'transaksi_penjualan_id', 'id');
    }


    public function history_item()
    {
        return $this->morphMany('App\HistoryItem', 'historyable');
    }

    public function history_piutang()
    {
        return $this->morphOne('App\HistoryPiutang', 'historypiutangable');
    }

    public function history_kas()
    {
        return $this->morphMany('App\HistoryKas', 'historykasable');
    }

    public function history_jurnal()
    {
        return $this->morphMany('App\HistoryJurnal', 'historyjurnalable');
    }

    public function costumer()
    {
        return $this->belongsTo('App\Costumer', 'costumer_id', 'id');
    }


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function kas()
    {
        return $this->belongsTo('App\Kas', 'kas_id', 'id');
    }
}
