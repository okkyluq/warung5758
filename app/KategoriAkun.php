<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriAkun extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'kategori_akun';
    protected $fillable = [
        'no_kategori', 'nama_kategori'
    ];

    public function akun()
    {
        return $this->hasMany('App\Akun', 'kategori_akun_id', 'id');
    }


    public function det_history_jurnal()
    {
        return $this->hasManyThrough(
            'App\DetHistoryJurnal',
            'App\Akun',
            'kategori_akun_id',
            'akun_id',
            'id',
            'id'
        );
    }

}
