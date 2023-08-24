<?php

namespace Tutorial\User\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tutorial\Comment\Models\Comment;
use Tutorial\Course\Models\Course;
use Tutorial\Media\Models\Media;
use Tutorial\Payment\Models\Payment;
use Tutorial\Payment\Models\Settlement;
use Tutorial\RolePermissions\Models\Role;
use Tutorial\Ticket\Models\Reply;
use Tutorial\Ticket\Models\Ticket;
use Tutorial\User\Notifications\ResetPasswordRequestNotification;
use Tutorial\User\Notifications\VerifyMail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_BAN = 'ban';

    public static $statuses =
    [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_BAN,
    ];

    public static $defaultUser =
    [
        [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'demo123',
            'role' => Role::ROLE_SUPER_ADMIN,
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id', 'id');
    }

    public function getThumbAttribute()
    {
        return $this->image_id ? '/storage/' . $this->image->files[300] : asset('panel/img/profile.jpg');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id', 'id');
    }

    public function purchases()
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'buyer_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function ticketReplies()
    {
        return $this->hasMany(Reply::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function studentsCount()
    {
        return DB::table('courses')->select('id')->where('teacher_id', $this->id)
            ->join('course_user', 'courses.id', '=', 'course_user.course_id')->count();
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class, 'user_id');
    }

    public function profilePath()
    {
        return $this->username ? route('users.profile', $this->username) : route('users.profile', 'username');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyMail);
    }

    public function sendResetPasswordRequestNotification()
    {
        $this->notify(new ResetPasswordRequestNotification);
    }

    public function routeNotificationForSms()
    {
        return $this->mobile;
    }
}
