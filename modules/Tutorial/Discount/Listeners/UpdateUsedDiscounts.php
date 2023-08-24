<?php

namespace Tutorial\Discount\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateUsedDiscounts
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
        foreach($event->payment->discounts as $discount){
            $discount->uses++;
            if(! is_null($discount->usage_limitation)){
                $discount->usage_limitation--;
            }
            $discount->save();
        }
    }
}
