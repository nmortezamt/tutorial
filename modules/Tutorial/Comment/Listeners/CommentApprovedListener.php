<?php

namespace Tutorial\Comment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Tutorial\Comment\Notifications\CommentApprovedNotification;
use Tutorial\Comment\Notifications\CommentSubmittedNotification;

class CommentApprovedListener
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
        if($event->comment->user_id != $event->comment->commentable->teacher->id)
        $event->comment->commentable->teacher->notify(new CommentSubmittedNotification($event->comment,true));

        $event->comment->user->notify(new CommentApprovedNotification($event->comment));
    }
}
