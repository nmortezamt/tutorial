<?php

namespace Tutorial\Comment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Tutorial\Comment\Notifications\CommentSubmittedNotification;

class CommentSubmittedListener
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
        if($event->comment->comment_id && $event->comment->user_id != $event->comment->comment->user->id)
        $event->comment->comment->user->notify(new CommentSubmittedNotification($event->comment,false));
    }
}
