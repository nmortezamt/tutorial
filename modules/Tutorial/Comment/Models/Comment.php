<?php

namespace Tutorial\Comment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tutorial\User\Models\User;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_NEW = 'new';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public static $statues = [
        self::STATUS_NEW,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class,'comment_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class,'comment_id');
    }

    public function approvedReplies()
    {
        return $this->hasMany(Comment::class,'comment_id')->where('status',self::STATUS_APPROVED)->get();
    }

    public function notApprovedComment()
    {
        return $this->hasMany(Comment::class,'comment_id')->where('status',self::STATUS_NEW);
    }


    public function getStatusCssClass()
    {
        if($this->status == self::STATUS_APPROVED) return 'text-success';
        if($this->status == self::STATUS_REJECTED) return 'text-error';
        return 'text-warning';
    }

}
