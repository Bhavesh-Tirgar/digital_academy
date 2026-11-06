<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'instructor_id',
        'category_id',
        'price',
        'thumbnail',
        'demo_video_url',
        'level',
        'duration',
        'enrolled_count',
        'rating',
        'what_you_will_learn',
        'is_completed',
        'completed_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->name) . '-' . rand(100, 999);
            }
        });
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'course_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function liveLectures()
    {
        return $this->hasMany(LiveLecture::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function comments()
    {
        return $this->hasMany(LectureComment::class)
            ->whereNull('parent_id')
            ->with(['user', 'replies'])
            ->latest();
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
