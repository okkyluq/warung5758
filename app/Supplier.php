<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'nama_supplier', 'keterangan', 'user_id'
    ];

    public function history_hutang()
    {
        return $this->hasMany('App\HistoryHutang', 'supplier_id', 'id');
    }

    public function transaksi_pembelian()
    {
        return $this->hasMany('App\TransaksiPembelian', 'supplier_id', 'id');
    }
}
