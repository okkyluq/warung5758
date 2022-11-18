<?php

namespace App\Http\Livewire\Produk;

use Livewire\Component;
use App\Produk;

class Create extends Component
{
    public $i = 1;
    public $inputs = [];
    public $produk_type = "tunggal";

    public function add($i)
    {
        // $i = $i + 1;
        // $this->i = $i;

        array_push($this->inputs ,[
            'nama_produk' => 'tes',
            'satuan' => 'stuan tes',
        ]);
        // $this->emit('addSelect2');
    }

    public function remove($i)
    {
        unset($this->inputs[$i]);
    }

    public function toggleTypeProduk($type)
    {
        return $this->produk_type = $type;
    }

    public function render()
    {
        return view('livewire.produk.create', [
            'bahan_baku' => Produk::select(['id', 'nama_produk'])->get()
        ]);
    }
}
