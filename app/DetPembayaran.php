<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetPembayaran extends Model
{
    protected $table = 'det_pembayaran';
    protected $primaryKey = 'id';
    protected $fillable = [
        'pembayaran_id', 'pembayaran_type', 'no_ref', 'jumlah_bayar', 'keterangan', 'history_hutang_id', 'akun_id'
    ];
}
