<?php

namespace Tutorial\Payment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddSellerShareToHisAccount
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if($event->payment->seller){
            $event->payment->seller->balance += $event->payment->seller_share;
            $event->payment->seller->save();
        }
    }
}
