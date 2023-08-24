<?php

namespace Tutorial\Comment\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Tutorial\Comment\Events\CommentApprovedEvent;
use Tutorial\Comment\Events\CommentRejectedEvent;
use Tutorial\Comment\Events\CommentSubmittedEvent;
use Tutorial\Comment\Listeners\CommentApprovedListener;
use Tutorial\Comment\Listeners\CommentRejectedListener;
use Tutorial\Comment\Listeners\CommentSubmittedListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CommentSubmittedEvent::class => [
            CommentSubmittedListener::class
        ],

        CommentApprovedEvent::class => [
            CommentApprovedListener::class
        ],

        CommentRejectedEvent::class => [
            CommentRejectedListener::class
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
