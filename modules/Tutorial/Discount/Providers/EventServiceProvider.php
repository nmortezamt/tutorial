<?php

namespace Tutorial\Discount\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Tutorial\Discount\Listeners\UpdateUsedDiscounts;
use Tutorial\Payment\Events\PaymentWasSuccessful;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentWasSuccessful::class => [
            UpdateUsedDiscounts::class
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
