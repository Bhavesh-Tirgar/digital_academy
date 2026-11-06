<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_id',
        'status',
        'headline',
        'bio',
        'avatar',
        'balance',
        'escrow_balance',
        'earnings',
        'subscription_type',
        'subscribed_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscribed_until' => 'datetime',
        'balance' => 'decimal:2',
        'earnings' => 'decimal:2',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getRoleAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        if ($this->role_id === 1) {
            return 'admin';
        }
        if ($this->role_id === 3) {
            return 'instructor';
        }

        return 'user';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role_id === 1;
    }

    public function isInstructor(): bool
    {
        return $this->role === 'instructor' || $this->role_id === 3 || $this->subscription_type === 'instructor';
    }

    public function isSubscribed(): bool
    {
        return !empty($this->subscription_type) && ($this->subscribed_until === null || $this->subscribed_until >= now());
    }

    public function taughtCourses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function liveLectures()
    {
        return $this->hasMany(LiveLecture::class, 'instructor_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
