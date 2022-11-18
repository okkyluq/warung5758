<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryAkun extends Model
{
    protected $table = 'history_akun';
    protected $primaryKey = 'id';
    protected $fillable = [
        'akun_id', 'historyakunable_id', 'historyakunable_type', 'nominal_debit', 'nominal_kredit'
    ];
}
