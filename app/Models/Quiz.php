<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'subject_id',
        'title',
        'description',
        'pass_percentage',
        'time_limit_minutes',
        'is_final_exam',
    ];

    protected $casts = [
        'pass_percentage' => 'integer',
        'time_limit_minutes' => 'integer',
        'is_final_exam' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function userBestAttempt($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->orderBy('percentage', 'desc')
            ->first();
    }
}
