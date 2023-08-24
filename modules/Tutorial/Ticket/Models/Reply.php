<?php

namespace Tutorial\Ticket\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Tutorial\Media\Models\Media;
use Tutorial\User\Models\User;

class Reply extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'ticket_replies';


    public function ticket()
    {
        return $this->belongsTo(Ticket::class,'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class,'media_id');
    }

    public function attachmentLink()
    {
        if($this->media_id)
        return URL::temporarySignedRoute('download.media',now()->addDay(),
        ['media'=>$this->media_id]);
    }
}
