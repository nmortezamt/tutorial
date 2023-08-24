<?php

namespace Tutorial\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Tutorial\Media\Models\Media;
use Tutorial\User\Models\User;

class Lesson extends Model
{
    use HasFactory;

    protected $guarded = [];

    const CONFIRMATION_STATUS_ACCEPTED = 'accepted';
    const CONFIRMATION_STATUS_REJECTED = 'rejected';
    const CONFIRMATION_STATUS_PENDING = 'pending';

    const STATUS_OPENED = 'opened';
    const STATUS_LOCKED = 'locked';

    public static $statuses = [self::STATUS_OPENED,self::STATUS_LOCKED];

    public static $confirmation_statuses = [self::CONFIRMATION_STATUS_ACCEPTED,self::CONFIRMATION_STATUS_REJECTED,self::CONFIRMATION_STATUS_PENDING];


    public function course()
    {
        return $this->belongsTo(Course::class,'course_id','id');
    }

    public function season()
    {

        return $this->belongsTo(Season::class,'season_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class,'media_id','id');
    }

    public function getConfirmationStatusCssClass()
    {
        if($this->confirmation_status == self::CONFIRMATION_STATUS_ACCEPTED)
        return 'text-success';
        elseif($this->confirmation_status == self::CONFIRMATION_STATUS_REJECTED)
        return 'text-danger';
    }

    public function downloadLink()
    {
        if($this->media_id)
        return URL::temporarySignedRoute('download.media',now()->addDay(),
        ['media'=>$this->media_id]);
    }

    public function path()
    {
        return $this->course->path() . '?lesson=l-' . $this->id . '-' .$this->slug;
    }
}
