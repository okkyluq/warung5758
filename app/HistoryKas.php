<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryKas extends Model
{
    protected $table = 'history_kas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kas_id', 'historykasable_id', 'historykasable_type', 'nominal_debit', 'nominal_kredit'
    ];
}
