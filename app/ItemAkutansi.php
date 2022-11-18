<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemAkutansi extends Model
{
    protected $table = 'item_akutansi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'item_id', 'akun_pembelian', 'akun_hpp', 'akun_penjualan', 'akun_retur_penjualan'
    ];

    public function item()
    {
        return $this->belongsTo('App\Item', 'item_id', 'id');
    }

    public function akun_pembelian()
    {
        return $this->belongsTo('App\Akun', 'akun_pembelian', 'id');
    }

    public function akun_hpp()
    {
        return $this->belongsTo('App\Akun', 'akun_hpp', 'id');
    }

    public function akun_penjualan()
    {
        return $this->belongsTo('App\Akun', 'akun_penjualan', 'id');
    }

    public function akun_retur_penjualan()
    {
        return $this->belongsTo('App\Akun', 'akun_retur_penjualan', 'id');
    }

    
}
