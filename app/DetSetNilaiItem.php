<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetSetNilaiItem extends Model
{
    protected $table = "det_set_nilai_item";
    protected $primaryKey = "id";
    protected $fillable = [
        "set_nilai_item_id", "item_id", "qty", "satuan_item_id", "hpp", "sub_total"
    ];

    public function set_nilai_item()
    {
        return $this->belongsTo('App\SetNilaiItem', 'set_nilai_item_id', 'id');
    }
}
