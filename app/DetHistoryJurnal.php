<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetHistoryJurnal extends Model
{
    protected $table = 'det_history_jurnal';
    protected $primaryKey = 'id';
    protected $fillable = [
        'history_journal_id', 'akun_id', 'nominal_debit', 'nominal_kredit', 'keterangan'
    ];

    public function history_jurnal()
    {
        return $this->belongsTo('App\HistoryJurnal', 'history_jurnal_id', 'id');
    }

    public function akun()
    {
        return $this->belongsTo('App\Akun', 'akun_id', 'id');
    }
}
