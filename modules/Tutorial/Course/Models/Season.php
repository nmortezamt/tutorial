<?php

namespace Tutorial\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tutorial\User\Models\User;

class Season extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function course()
    {

        return $this->belongsTo(Course::class,'course_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class,'lesson_id','id');
    }

    public function getConfirmationStatusCssClass()
    {
        if($this->confirmation_status == self::CONFIRMATION_STATUS_ACCEPTED)
        return 'text-success';
        elseif($this->confirmation_status == self::CONFIRMATION_STATUS_REJECTED)
        return 'text-danger';
    }

    const CONFIRMATION_STATUS_ACCEPTED = 'accepted';
    const CONFIRMATION_STATUS_REJECTED = 'rejected';
    const CONFIRMATION_STATUS_PENDING = 'pending';

    const STATUS_OPENED = 'opened';
    const STATUS_LOCKED = 'locked';

    public static $statuses = [self::STATUS_OPENED,self::STATUS_LOCKED];

    public static $confirmation_statuses = [self::CONFIRMATION_STATUS_ACCEPTED,self::CONFIRMATION_STATUS_REJECTED,self::CONFIRMATION_STATUS_PENDING];
}
