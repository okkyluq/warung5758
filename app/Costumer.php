<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Costumer extends Model
{
    protected $table = 'costumer';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'nama_costumer', 'keterangan', 'user_id'
    ];

    public function history_piutang()
    {
        return $this->hasMany('App\HistoryPiutang', 'costumer_id', 'id');
    }

    public function transaksi_penjualan()
    {
        return $this->hasMany('App\TransaksiPenjualan', 'costumer_id', 'id');
    }
}
