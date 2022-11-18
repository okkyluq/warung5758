<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengaturanAkun extends Model
{
    protected $table = 'pengaturan_akun';
    protected $primaryKey = 'id';
    protected $fillable = [
        'setting', 'akun_id', 'value', 'kode'
    ];


    public function akun()
    {
        return $this->hasOne('App\Akun', 'akun_id', 'id');
    }

    public function det_history_jurnal()
    {
        return $this->hasMany('App\DetHistoryJurnal', 'akun_id', 'akun_id');
    }
}
