<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryHutang extends Model
{
    protected $table = 'history_hutang';
    protected $primaryKey = 'id';
    protected $fillable = [
        'supplier_id','historyhutangable_id', 'historyhutangable_type', 'nominal', 'terbayar', 'sisa_pembayaran', 'status_lunas', 'tgl_jatuh_tempo'
    ];
    
    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id', 'id');
    }

    public function historyhutangable()
    {
        return $this->morphTo();
    }

    
    // public function transaksi_pembelian()
    // {
    //     // return $this->morphedByMany('App\TransaksiPembelian', 'historyhutangable');
    //     return $this->morphedByMany('App\TransaksiPembelian', 'historyhutangable');
    // }
     
}
