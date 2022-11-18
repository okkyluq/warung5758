<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryJurnal extends Model
{
    protected $table = 'history_jurnal';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_journal', 'tgl_set', 'autogen', 'historyjurnalable_id', 'historyjurnalable_type', 'total_debit', 'total_kredit', 'keterangan'
    ];

    public function det_history_jurnal()
    {
        return $this->hasMany('App\DetHistoryJurnal', 'history_journal_id', 'id'); 
    }  
}
