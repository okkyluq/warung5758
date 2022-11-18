<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryPiutang extends Model
{
    protected $table = 'history_piutang';
    protected $primaryKey = 'id';
    protected $fillable = [
        'costumer_id','historypiutangable_id', 'historypiutangable_type', 'nominal', 'terbayar', 'sisa_pembayaran', 'status_lunas', 'tgl_jatuh_tempo'
    ];

    public function costumer()
    {
        return $this->belongsTo('App\Costumer', 'costumer_id', 'id');
    }

    public function historypiutangable()
    {
        return $this->morphTo();
    }
}
