<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetSetNilaiKas extends Model
{
    protected $table = "det_set_nilai_kas";
    protected $primaryKey = "id";
    protected $fillable = [
        "set_nilai_kas_id", "kas_id", "nominal_debit", "nominal_kredit"
    ];

    public function set_nilai_kas()
    {
        return $this->belongsTo('App\SetNilaiKas', 'set_nilai_kas_id', 'id');
    }
}
