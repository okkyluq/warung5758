<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetNilaiPiutang extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $table = 'set_nilai_piutang';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_transaksi', 'tgl_set', 'costumer_id', 'jatuh_tempo', 'total', 'akun_id', 'keterangan'
    ];

    public function costumer()
    {
        return $this->belongsTo('App\Costumer', 'costumer_id', 'id');
    }
    
    public function history_piutang()
    {
        return $this->morphMany('App\HistoryPiutang', 'historypiutangable');
    } 

    public function history_jurnal()
    {
        return $this->morphMany('App\HistoryJurnal', 'historyjurnalable');
    }

    public function det_history_jurnal()
    {
        return $this->hasManyDeep(
            'App\DetHistoryJurnal',
            ['App\HistoryJurnal'],
            [null, ['historyjurnalable_type', 'historyjurnalable_id']]
        );

    }
}
