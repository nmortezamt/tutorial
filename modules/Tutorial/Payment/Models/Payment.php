<?php

namespace Tutorial\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tutorial\Discount\Models\Discount;
use Tutorial\User\Models\User;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_PENDING = 'pending';
    const STATUS_CANCELED = 'canceled';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAIL = 'fail';

    public static $statuses = [
        self::STATUS_PENDING,
        self::STATUS_CANCELED,
        self::STATUS_SUCCESS,
        self::STATUS_FAIL,
    ];

    public function paymentable()
    {
        return $this->morphTo();
    }

    public function buyer()
    {
        return $this->belongsTo(User::class,'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getStatusCssClass()
    {
        if($this->status == 'success') return 'text-success';
        return 'text-error';
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class,'discount_payment','payment_id','discount_id');
    }
}
