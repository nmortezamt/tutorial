<?php

namespace Tutorial\Payment\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Tutorial\Payment\Events\PaymentWasSuccessful;
use Tutorial\Payment\Listeners\AddSellerShareToHisAccount;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentWasSuccessful::class => [
            AddSellerShareToHisAccount::class
        ],
    ];

    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
