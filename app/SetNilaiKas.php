<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetNilaiKas extends Model
{
    protected $table = "set_nilai_kas";
    protected $primaryKey = "id"; 
    protected $fillable = [
        "kode_transaksi", "tgl_set", "user_id", "kas_id", "nominal", "keterangan"
    ];

    public function kas()
    {
        return $this->belongsTo("App\Kas", "kas_id", "id");
    }

    
    public function history_kas()
    {
        return $this->morphMany('App\HistoryKas', 'historykasable');
    }

    public function history_jurnal()
    {
        return $this->morphMany('App\HistoryJurnal', 'historyjurnalable');
    }
}
