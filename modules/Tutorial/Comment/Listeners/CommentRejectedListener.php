<?php

namespace Tutorial\Comment\Listeners;

use Tutorial\Comment\Notifications\CommentRejectedNotification;

class CommentRejectedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(object $event): void
    {
        $event->comment->user->notify(new CommentRejectedNotification($event->comment));
    }
}
