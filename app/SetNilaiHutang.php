<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetNilaiHutang extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $table = 'set_nilai_hutang';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_transaksi', 'tgl_set', 'supplier_id', 'jatuh_tempo', 'total', 'akun_id', 'keterangan'
    ];

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id', 'id');
    }
    
    public function history_hutang()
    {
        return $this->morphMany('App\HistoryHutang', 'historyhutangable');
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
