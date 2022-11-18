<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    protected $table = 'transaksi_pembelian';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'no_pembelian', 'supplier_id', 'tgl_pembelian', 'user_id', 'termin', 'kas_id', 'total', 'jumlah_hari_tempo', 'tgl_jatuh_tempo', 'uang_muka'
    ];

    public function det_transaksi_pembelian()
    {
        return $this->hasMany('App\DetTransaksiPembelian', 'transaksi_pembelian_id', 'id');
    }


    public function history_item()
    {
        return $this->morphMany('App\HistoryItem', 'historyable');
    }

    public function history_hutang()
    {
        return $this->morphOne('App\HistoryHutang', 'historyhutangable');
    }

    public function history_kas()
    {
        return $this->morphMany('App\HistoryKas', 'historykasable');
    }

    public function history_jurnal()
    {
        return $this->morphMany('App\HistoryJurnal', 'historyjurnalable');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id', 'id');
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
