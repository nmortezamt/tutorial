<?php

namespace Tutorial\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tutorial\User\Models\User;

class Settlement extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = ['to'=>'json','from'=>'json'];
    const STATUS_PENDING = 'pending';
    const STATUS_SETTLED = 'settled';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    public static $statuses = [
        self::STATUS_PENDING,
        self::STATUS_SETTLED,
        self::STATUS_REJECTED,
        self::STATUS_CANCELLED,
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getStatusCssClass()
    {
        if($this->status == 'settled') return 'text-success';
        return 'text-error';
    }
}
