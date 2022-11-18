<?php

namespace App\Repositories\Eloquent;
use App\Repositories\HistoryItemRepositoryInterface;
use App\TransaksiPembelian;
use App\HistoryItem;
use App\Satuan;
use App\SatuanItem;


class HistoryItemRepository implements HistoryItemRepositoryInterface 
{

    private $historyItem;
    private $transaksiPembelian;

    public function __construct(HistoryItem $historyItem, TransaksiPembelian $transaksiPembelian)
    {
        $this->historyItem = $historyItem;        
        $this->transaksiPembelian = $transaksiPembelian;        
    }


    public function insertHistoryItem($attributes, $transaksi)
    {
        $history_item = $attributes->map(function($value){
            return new HistoryItem($value);
        });

        $transaksi->history_item()->saveMany($history_item);

    }


}