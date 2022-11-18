<?php

namespace App\Listeners;

use App\HistoryItem;
use App\TransaksiPembelian;
use App\Events\TransaksiPembelianEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransaksiPembelianInsertHistoryItemListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TransaksiPembelianEvent  $event
     * @return void
     */
    public function handle(TransaksiPembelianEvent $event)
    {
        // dd($event->request['list_item']);
        
        // $transaksi = TransaksiPembelian::findOrFail($event->id);

        $history_item = collect($event->request['list_item'])->map(function($value){
            return new HistoryItem($value);
        });

        dd($history_item);

        // $transaksi->history_item()->saveMany($history_item);


    }
}
