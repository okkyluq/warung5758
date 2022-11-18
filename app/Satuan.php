<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'satuan', 'user_id', 'keterangan'
    ];

    public function satuan_item()
    {
        return $this->hasOne('App\SatuanItem', 'satuan_id', 'id');
    }


}
