<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetNilaiAkun extends Model
{
    protected $table = "set_nilai_akun";
    protected $primaryKey = "id";
    protected $fillable = [
        "kode_transaksi", "tgl_set", "user_id"
    ];

    public function det_set_nilai_akun()
    {
        return $this->hasMany('App\DetSetNilaiAkun', 'set_nilai_akun_id', 'id');
    }

    public function history_akun()
    {
        return $this->morphMany('App\HistoryAkun', 'historyakunable');
    }
}
