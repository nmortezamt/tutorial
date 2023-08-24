<?php

namespace Tutorial\Ticket\Repositories;

use Tutorial\Ticket\Models\Reply;

class ReplyRepo
{
    public static function store($ticketId,$body,$mediaId = null)
    {
        return Reply::query()->create([
            'user_id' =>auth()->id(),
            'ticket_id' =>$ticketId,
            'body' =>$body,
            'media_id' =>$mediaId,
        ]);
    }

    public function delete($ticketId)
    {
        $attachments = Reply::query()->where('ticket_id',$ticketId)->whereNotNull('media_id')->with('media')->get();

        foreach($attachments as $reply){
            $reply->media->delete();
        }
    }
}
