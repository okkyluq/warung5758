<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'akun';
    protected $fillable = [
        'kategori_akun_id', 'kode_akun', 'nama_akun', 'status_header', 'status_pembayaran', 'default_saldo', 'parent_id'
    ];

    protected $appends = ['label_akun'];

    public function getLabelAkunAttribute()
    {
        return $this->attributes['kode_akun'].' - '.$this->attributes['nama_akun'];
    }

    public function kategori()
    {
        return $this->belongsTo('App\KategoriAkun', 'kategori_akun_id', 'id');
    }

    public function child_akun()
    {
        return $this->hasMany('App\Akun', 'parent_id', 'id');
    }

    public function parent_akun()
    {
        return $this->belongsTo('App\Akun', 'parent_id', 'id');
    }

    public function det_history_jurnal()
    {
        return $this->hasMany('App\DetHistoryJurnal', 'akun_id', 'id');
    }
}
