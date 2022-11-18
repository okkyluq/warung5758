<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetPenerimaanPembayaran extends Model
{
    protected $table = 'det_penerimaan_pembayaran';
    protected $primaryKey = 'id';
    protected $fillable = [
        'penerimaan_pembayaran_id', 'history_piutang_id', 'akun_id', 'penerimaan_pembayaran_type', 'no_ref', 'jumlah_bayar', 'keterangan'
    ];

    
}
