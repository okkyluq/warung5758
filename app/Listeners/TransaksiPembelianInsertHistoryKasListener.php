<?php

namespace App\Listeners;

use App\Events\TransaksiPembelianEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransaksiPembelianInsertHistoryKasListener
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
        //
    }
}
