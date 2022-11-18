<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetSetNilaiAkun extends Model
{
    protected $table = "det_set_nilai_akun";
    protected $primaryKey = "id";
    protected $fillable = [
        "set_nilai_akun_id", "akun_id", "nominal_debit", "nominal_kredit"
    ];

    public function set_nilai_akun()
    {
        return $this->belongsTo('App\SetNilaiAkun', 'set_nilai_akun_id', 'id');
    }
}
