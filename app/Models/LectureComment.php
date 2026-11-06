<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'lecture_id',
        'material_id',
        'parent_id',
        'comment',
        'is_instructor_reply',
        'likes_count',
    ];

    protected $casts = [
        'is_instructor_reply' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecture()
    {
        return $this->belongsTo(LiveLecture::class, 'lecture_id');
    }

    public function parent()
    {
        return $this->belongsTo(LectureComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(LectureComment::class, 'parent_id')
            ->with(['user', 'replies'])
            ->orderBy('created_at', 'asc');
    }
}
