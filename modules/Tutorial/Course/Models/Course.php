<?php

namespace Tutorial\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tutorial\Media\Models\Media;
use Tutorial\User\Models\User;

class Course extends Model
{
    use HasFactory;
    protected $guarded= [];
    const TYPE_FREE = 'free';
    const TYPE_CASH = 'cash';

    const STATUS_COMPLETED = 'completed';
    const STATUS_NOT_COMPLETED = 'not-completed';
    const STATUS_LOCKED = 'locked';

    const CONFIRMATION_STATUS_ACCEPTED = 'accepted';
    const CONFIRMATION_STATUS_REJECTED = 'rejected';
    const CONFIRMATION_STATUS_PENDING = 'pending';

    public static $types = [self::TYPE_FREE,self::TYPE_CASH];
    public static $statuses = [self::STATUS_COMPLETED,self::STATUS_NOT_COMPLETED,self::STATUS_LOCKED];
    public static $confirmation_statuses = [self::CONFIRMATION_STATUS_ACCEPTED,self::CONFIRMATION_STATUS_REJECTED,self::CONFIRMATION_STATUS_PENDING];


    public function banner()
    {
        return $this->belongsTo(Media::class,'banner_id','id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class,'teacher_id','id');
    }
}
