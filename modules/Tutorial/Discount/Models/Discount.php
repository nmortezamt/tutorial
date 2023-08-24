<?php

namespace Tutorial\Discount\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tutorial\Course\Models\Course;
use Tutorial\Payment\Models\Payment;

class Discount extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = ['expire_at' => 'datetime'];

    const TYPE_ALL = 'all';
    const TYPE_SPECIAL = 'special';
    public static $types = [
        self::TYPE_ALL,
        self::TYPE_SPECIAL
    ];

    public function courses()
    {
        return $this->morphedByMany(Course::class,'discountable');
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class,'discount_payment','discount_id','payment_id');
    }
}
