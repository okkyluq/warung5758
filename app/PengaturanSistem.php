<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengaturanSistem extends Model
{
    protected $table = 'pengaturan_sistem';
    protected $primaryKey = 'id';
    protected $fillable = [
        'setting', 'value'
    ];
}
