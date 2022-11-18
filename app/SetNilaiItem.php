<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetNilaiItem extends Model
{
    protected $table = "set_nilai_item";
    protected $primaryKey = "id";
    protected $fillable = [
        "kode_transaksi", "tgl_set", "user_id"
    ];

    public function det_set_nilai_item()
    {
        return $this->hasMany('App\DetSetNilaiItem', 'set_nilai_item_id', 'id');
    }

    public function history_item()
    {
        return $this->morphMany('App\HistoryItem', 'historyable');
    }

    public function history_jurnal()
    {
        return $this->morphMany('App\HistoryJurnal', 'historyjurnalable');
    }
}
