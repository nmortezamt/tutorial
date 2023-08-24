<?php

namespace Tutorial\Ticket\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tutorial\User\Models\User;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_PENDING = 'pending';
    const STATUS_OPEN = 'open';
    const STATUS_CLOSE = 'close';
    const STATUS_REPLIED = 'replied';

    public static $statuses = [
        self::STATUS_CLOSE,
        self::STATUS_OPEN,
        self::STATUS_PENDING,
        self::STATUS_REPLIED,
    ];

    public function replies()
    {
        return $this->hasMany(Reply::class,'ticket_id');
    }

    public function ticketable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getStatusCssClass()
    {
        if($this->status == self::STATUS_REPLIED) return 'text-success';
        if($this->status == self::STATUS_OPEN) return 'text-info';
        if($this->status == self::STATUS_PENDING) return 'text-warning';
        return 'text-error';
    }
}
