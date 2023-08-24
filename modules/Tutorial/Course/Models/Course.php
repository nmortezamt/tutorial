<?php

namespace Tutorial\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tutorial\Category\Models\Category;
use Tutorial\Comment\Models\Comment;
use Tutorial\Comment\Traits\HasComments;
use Tutorial\Course\Repositories\CourseRepo;
use Tutorial\Discount\Models\Discount;
use Tutorial\Discount\Repositories\DiscountRepo;
use Tutorial\Discount\Services\DiscountService;
use Tutorial\Media\Models\Media;
use Tutorial\Payment\Models\Payment;
use Tutorial\Ticket\Models\Ticket;
use Tutorial\User\Models\User;

class Course extends Model
{
    use HasFactory , HasComments;
    protected $guarded = [];
    const TYPE_FREE = 'free';
    const TYPE_CASH = 'cash';

    const STATUS_COMPLETED = 'completed';
    const STATUS_NOT_COMPLETED = 'not-completed';
    const STATUS_LOCKED = 'locked';

    const CONFIRMATION_STATUS_ACCEPTED = 'accepted';
    const CONFIRMATION_STATUS_REJECTED = 'rejected';
    const CONFIRMATION_STATUS_PENDING = 'pending';

    public static $types = [self::TYPE_FREE, self::TYPE_CASH];
    public static $statuses = [self::STATUS_COMPLETED, self::STATUS_NOT_COMPLETED, self::STATUS_LOCKED];
    public static $confirmation_statuses = [self::CONFIRMATION_STATUS_ACCEPTED, self::CONFIRMATION_STATUS_REJECTED, self::CONFIRMATION_STATUS_PENDING];


    public function banner()
    {
        return $this->belongsTo(Media::class, 'banner_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function seasons()
    {
        return $this->hasMany(Season::class, 'course_id', 'id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id', 'id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')->withTimestamps();
    }

    public  function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function discounts()
    {
        return $this->morphToMany(Discount::class, 'discountable');
    }

    public function payment()
    {
        return $this->payment()->latest()->first();
    }

    public function getConfirmationStatusCssClass()
    {
        if ($this->confirmation_status == self::CONFIRMATION_STATUS_ACCEPTED)
            return 'text-success';
        elseif ($this->confirmation_status == self::CONFIRMATION_STATUS_REJECTED)
            return 'text-danger';
    }

    public function hasStudent($studentId)
    {
        return resolve(CourseRepo::class)->hasStudent($this, $studentId);
    }

    public function downloadLinks(): array
    {
        $links = [];
        foreach (resolve(CourseRepo::class)->getLessons($this->id) as $lesson) {
            $links[] = $lesson->downloadLink();
        }
        return $links;
    }

    public function getDuration()
    {
        return (new CourseRepo)->getDuration($this->id);
    }

    public function formattedDuration()
    {
        $duration = $this->getDuration();
        if ($duration >= 59) {
            $h = round($duration / 60) < 10 ? '0' . round($duration / 60) : round($duration / 60);
        } else {
            $h = '00';
        }
        $m = ($duration % 60) < 10 ? '0' . ($duration % 60) : ($duration % 60);
        return $h . ':' . $m . ':' . '00';
    }

    public function formattedPrice()
    {
        return number_format($this->price);
    }

    public function getDiscount()
    {
        $DiscountRepo = new DiscountRepo();
        $discount = $DiscountRepo->getCourseBiggerDiscount($this->id);
        $globalDiscount = $DiscountRepo->getGlobalBiggerDiscount();
        if ($discount == null && $globalDiscount == null) return null;
        if ($discount == null && $globalDiscount != null) return $globalDiscount;
        if ($discount != null && $globalDiscount == null) return $discount;
        if ($globalDiscount->percent > $discount->percent) return $globalDiscount;
        return $discount;
    }

    public function discountPercent()
    {
        $discount = $this->getDiscount();
        if ($discount) return $discount->percent;
        return null;
    }

    public function discountAmount($percent = null)
    {
        if (is_null($percent)) {
            $discount = $this->getDiscount();
            $percent = $discount ? $discount->percent : 0;
        }
        return DiscountService::calculateDiscountAmount($this->price, $percent);
    }

    public function finalPrice($code = null, $withDiscounts = false)
    {
        $discounts = [];
        $amount = $this->price;
        $discount = $this->getDiscount();
        if ($discount) {
            $discounts[] = $discount;
            $amount = $this->price - $this->discountAmount($discount->percent);
        }
        if ($code) {
            $DiscountRepo = new DiscountRepo();
            $discountCode = $DiscountRepo->getValidDiscountByCode($this->id, $code);
            if ($discountCode) {
                $amount = $amount - DiscountService::calculateDiscountAmount($amount, $discountCode->percent);
                $discounts[] = $discountCode;
            }
        }
        if ($withDiscounts)
            return [$amount, $discounts];

        return $amount;
    }
    public function formattedFinalPrice()
    {
        return number_format($this->finalPrice());
    }

    public function lessonsCount()
    {
        return (new CourseRepo)->getLessonsCount($this->id);
    }

    public function shortUrl()
    {
        return route('singleCourse', $this->id);
    }

    public function path()
    {
        return route('singleCourse', $this->id . '-' . $this->slug);
    }
}
