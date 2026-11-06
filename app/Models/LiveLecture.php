<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LiveLecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'instructor_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'meeting_link',
        'stream_url',
        'active_viewers',
        'live_poll',
        'chat_enabled',
        'status',
        'is_demo',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_demo' => 'boolean',
        'chat_enabled' => 'boolean',
        'live_poll' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function comments()
    {
        return $this->hasMany(LectureComment::class, 'lecture_id')->with('user')->oldest();
    }

    public function isLiveNow(): bool
    {
        $now = Carbon::now();
        return $this->status === 'live' || ($this->start_time <= $now && ($this->end_time === null || $this->end_time >= $now));
    }
}
