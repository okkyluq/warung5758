<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $table = "kas";
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_kas', 'nama_kas', 'type_kas', 'akun_id'
    ];

    public function akun()
    {
        return $this->belongsTo('App\Akun', 'akun_id', 'id');
    }

    public function history_kas()
    {
        return $this->hasMany('App\HistoryKas', 'kas_id', 'id');
    }


}
