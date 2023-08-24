<?php

namespace Tutorial\Ticket\Services;

use Illuminate\Http\UploadedFile;
use Tutorial\Media\Services\MediaFileService;
use Tutorial\Ticket\Models\Ticket;
use Tutorial\Ticket\Repositories\ReplyRepo;
use Tutorial\Ticket\Repositories\TicketRepo;

class ReplyService
{
    public static function store(Ticket $ticket,$body,$attachment)
    {
        $ticketRepo = new TicketRepo();
        $media_id = null;
        if($attachment && ($attachment instanceof UploadedFile)){
            $media_id = MediaFileService::privateUpload($attachment)->id;
        }
        $reply = ReplyRepo::store($ticket->id,$body,$media_id);
        if($reply->user_id != $ticket->user_id){
            $ticketRepo->changeStatus($ticket->id,Ticket::STATUS_REPLIED);
        }else{
            $ticketRepo->changeStatus($ticket->id,Ticket::STATUS_OPEN);
        }
        return $reply;
    }
}
