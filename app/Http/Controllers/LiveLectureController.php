<?php

namespace App\Http\Controllers;

use App\Models\LiveLecture;
use App\Models\LectureComment;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LiveLectureController extends Controller
{
    /**
     * Display live lectures schedule & timetable.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $query = LiveLecture::with(['instructor', 'course']);

        $now = Carbon::now();

        if ($filter === 'live') {
            $query->where('status', 'live');
        } elseif ($filter === 'today') {
            $query->whereDate('start_time', Carbon::today());
        } elseif ($filter === 'upcoming') {
            $query->where('start_time', '>', $now);
        }

        $lectures = $query->orderBy('start_time', 'asc')->paginate(12);

        $liveNow = LiveLecture::with(['instructor', 'course'])
            ->where('status', 'live')
            ->first();

        return view('frontend.schedule', compact('lectures', 'filter', 'liveNow'));
    }

    /**
     * In-Platform Native Live Virtual Classroom & Studio.
     * No external redirects (Google Meet / Zoom). Runs 100% inside Digital Academy.
     */
    public function classroom($id)
    {
        $lecture = LiveLecture::with(['instructor', 'course.instructor', 'comments.user'])
            ->findOrFail($id);

        $user = Auth::user();

        // Check enrollment or host access
        $hasAccess = false;
        $isHost = false;

        if ($user) {
            // Strict Host Scoping: User is only host if they are platform Admin,
            // OR the assigned instructor of THIS lecture, OR the owner of THIS lecture's course.
            $isHost = $user->isAdmin() || $user->id === $lecture->instructor_id || ($lecture->course && $user->id === $lecture->course->instructor_id);

            if ($isHost || $lecture->is_demo) {
                $hasAccess = true;
            } elseif ($lecture->course_id) {
                // Must be actively enrolled in the course
                $hasAccess = Enrollment::where('user_id', $user->id)
                    ->where('course_id', $lecture->course_id)
                    ->exists();
            } else {
                // Public open session
                $hasAccess = true;
            }
        } elseif ($lecture->is_demo) {
            $hasAccess = true;
        }

        // Strict Paywall Enforcement:
        // If user is not authorized or not enrolled in this private cohort live lecture, block with checkout redirect!
        if (!$hasAccess) {
            if (!$user) {
                session(['url.intended' => request()->fullUrl()]);
                return redirect()->route('login')
                    ->with('info', "Please log in to attend '{$lecture->title}'.");
            }

            if ($lecture->course_id) {
                return redirect()->route('courses.checkout', $lecture->course_id)
                    ->with('info', "This live cohort session is private for enrolled students of '{$lecture->course->name}'. Please enroll to get instant access to live masterclasses, studio replays, and doubt resolution!");
            }

            return redirect()->route('schedule.index')
                ->with('error', "This private live classroom requires cohort access.");
        }

        $isInstructorOrAdmin = $isHost;

        // Active poll sample if none exists
        $poll = $lecture->live_poll;
        if (!$poll) {
            $poll = [
                'question' => 'What is the time complexity of binary search on a sorted array?',
                'options' => [
                    ['id' => 1, 'text' => 'O(1)', 'votes' => 4],
                    ['id' => 2, 'text' => 'O(log n)', 'votes' => 86],
                    ['id' => 3, 'text' => 'O(n)', 'votes' => 12],
                    ['id' => 4, 'text' => 'O(n log n)', 'votes' => 2],
                ],
                'total_votes' => 104,
                'active' => true,
            ];
        }

        return view('frontend.live_classroom', compact('lecture', 'hasAccess', 'isInstructorOrAdmin', 'poll'));
    }

    /**
     * Poll / fetch live messages for classroom (Fast JSON endpoint).
     */
    public function getMessages($id)
    {
        $messages = LectureComment::with('user:id,name,role,avatar')
            ->where('lecture_id', $id)
            ->oldest()
            ->take(80)
            ->get();

        $lecture = LiveLecture::select('id', 'active_viewers', 'status', 'live_poll')->find($id);

        return response()->json([
            'status' => 'success',
            'viewers' => $lecture ? $lecture->active_viewers : 142,
            'poll' => $lecture ? $lecture->live_poll : null,
            'messages' => $messages->map(function ($m) {
                return [
                    'id' => $m->id,
                    'user_id' => $m->user_id,
                    'user_name' => $m->user ? $m->user->name : 'Anonymous Learner',
                    'is_instructor' => (bool)$m->is_instructor_reply,
                    'comment' => $m->comment,
                    'time' => $m->created_at->format('h:i A'),
                ];
            }),
        ]);
    }

    /**
     * Send real-time chat message in live classroom.
     */
    public function postMessage(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please sign in to participate in live classroom chat.'], 401);
        }

        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $lecture = LiveLecture::findOrFail($id);
        $user = Auth::user();

        $isInstructorReply = ($user->id === $lecture->instructor_id || $user->isAdmin() || $user->isInstructor());

        $comment = LectureComment::create([
            'course_id' => $lecture->course_id,
            'lecture_id' => $lecture->id,
            'user_id' => $user->id,
            'comment' => $request->message,
            'is_instructor_reply' => $isInstructorReply,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => [
                'id' => $comment->id,
                'user_name' => $user->name,
                'is_instructor' => $isInstructorReply,
                'comment' => $comment->comment,
                'time' => $comment->created_at->format('h:i A'),
            ],
        ]);
    }

    /**
     * Submit vote on live classroom poll.
     */
    public function votePoll(Request $request, $id)
    {
        $lecture = LiveLecture::findOrFail($id);
        $optionId = (int)$request->input('option_id');

        $poll = $lecture->live_poll ?? [
            'question' => 'What is the time complexity of binary search on a sorted array?',
            'options' => [
                ['id' => 1, 'text' => 'O(1)', 'votes' => 4],
                ['id' => 2, 'text' => 'O(log n)', 'votes' => 86],
                ['id' => 3, 'text' => 'O(n)', 'votes' => 12],
                ['id' => 4, 'text' => 'O(n log n)', 'votes' => 2],
            ],
            'total_votes' => 104,
            'active' => true,
        ];

        foreach ($poll['options'] as &$opt) {
            if ($opt['id'] === $optionId) {
                $opt['votes'] += 1;
                $poll['total_votes'] += 1;
                break;
            }
        }

        $lecture->live_poll = $poll;
        $lecture->save();

        return response()->json([
            'status' => 'success',
            'poll' => $poll,
        ]);
    }

    /**
     * Instructor creates a live poll during stream.
     */
    public function createPoll(Request $request, $id)
    {
        $lecture = LiveLecture::findOrFail($id);
        $user = Auth::user();

        // Strict Host Scoping: Only the assigned instructor of this lecture or admin can launch polls
        $isHost = $user && ($user->isAdmin() || $user->id === $lecture->instructor_id || ($lecture->course && $user->id === $lecture->course->instructor_id));
        if (!$isHost) {
            return response()->json(['error' => 'Unauthorized: Only the host instructor can launch live quizzes in this class.'], 403);
        }

        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2|max:4',
        ]);

        $opts = [];
        foreach ($request->options as $index => $text) {
            $opts[] = [
                'id' => $index + 1,
                'text' => $text,
                'votes' => 0,
            ];
        }

        $poll = [
            'question' => $request->question,
            'options' => $opts,
            'total_votes' => 0,
            'active' => true,
        ];

        $lecture->live_poll = $poll;
        $lecture->save();

        return response()->json([
            'status' => 'success',
            'poll' => $poll,
        ]);
    }

    /**
     * WebRTC & Stream Signaling Receiver (Broadcast stream state, SDP, and ICE).
     */
    public function sendSignal(Request $request, $id)
    {
        $lecture = LiveLecture::findOrFail($id);
        $user = Auth::user();
        $isHost = $user && ($user->isAdmin() || $user->id === $lecture->instructor_id || ($lecture->course && $user->id === $lecture->course->instructor_id));

        $payload = $request->all();
        $payload['sender_id'] = Auth::id();
        $payload['sender_name'] = $user ? $user->name : 'Anonymous';
        $payload['timestamp'] = microtime(true);

        // Security Guard: Only the host instructor can broadcast active video/screen states or send video frames
        if (isset($payload['frame_data']) || (isset($payload['type']) && $payload['type'] === 'stream_update')) {
            if (!$isHost) {
                return response()->json(['error' => 'Unauthorized: Only the host instructor can broadcast video or frames.'], 403);
            }
        }

        // Store active broadcast stream state ONLY when is_live is explicitly specified
        // (Fixes race condition where frame_data payload without is_live inadvertently resets is_live to false!)
        if (isset($payload['stream_type']) && isset($payload['is_live'])) {
            Cache::put("live_stream_state_{$id}", [
                'type' => $payload['stream_type'], // 'webcam', 'screen', 'whiteboard', 'cdn'
                'is_live' => (bool)$payload['is_live'],
                'source_label' => $payload['source_label'] ?? 'Live Stream',
                'instructor_name' => $user ? $user->name : 'Instructor',
                'updated_at' => now()->toIso8601String()
            ], 300);
        }

        // WebRTC SDP/ICE signaling queue
        if (isset($payload['signal_type'])) {
            $key = "live_webrtc_signals_{$id}";
            $signals = Cache::get($key, []);
            $signals[] = $payload;
            if (count($signals) > 15) {
                $signals = array_slice($signals, -15);
            }
            Cache::put($key, $signals, 60);
        }

        // Frame snapshot relay fallback (instant visual sync across browsers)
        if (isset($payload['frame_data'])) {
            Cache::put("live_frame_{$id}", [
                'frame' => $payload['frame_data'],
                'type' => $payload['stream_type'] ?? 'screen',
                'timestamp' => microtime(true)
            ], 15);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * WebRTC & Stream Signaling Poller (for viewers/students).
     */
    public function getSignal(Request $request, $id)
    {
        $streamState = Cache::get("live_stream_state_{$id}", [
            'type' => 'cdn',
            'is_live' => false,
            'source_label' => 'CDN Primary Stream',
            'instructor_name' => 'Host Instructor',
        ]);

        $signals = Cache::get("live_webrtc_signals_{$id}", []);
        $frame = Cache::get("live_frame_{$id}", null);
        $raisedHands = Cache::get("live_raised_hands_{$id}", []);

        return response()->json([
            'status' => 'success',
            'stream_state' => $streamState,
            'signals' => $signals,
            'frame' => $frame,
            'raised_hands' => $raisedHands,
        ]);
    }

    /**
     * Student Raises or Lowers Hand (Ask Doubt).
     */
    public function raiseHand(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please sign in to raise your hand.'], 401);
        }

        $user = Auth::user();
        $key = "live_raised_hands_{$id}";
        $hands = Cache::get($key, []);

        $action = $request->input('action', 'raise');
        if ($action === 'lower') {
            $hands = array_values(array_filter($hands, fn($h) => ($h['user_id'] ?? 0) !== $user->id));
        } else {
            $exists = false;
            foreach ($hands as $h) {
                if (($h['user_id'] ?? 0) === $user->id) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $hands[] = [
                    'id' => uniqid('hand_'),
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'avatar' => $user->avatar,
                    'question' => $request->input('question', 'Student wants to ask a question'),
                    'raised_at' => now()->format('h:i A'),
                ];
            }
        }

        Cache::put($key, $hands, 3600);

        return response()->json(['status' => 'success', 'raised_hands' => $hands]);
    }

    /**
     * Instructor Dismisses or Answers a Raised Hand.
     */
    public function dismissHand(Request $request, $id, $handId)
    {
        $lecture = LiveLecture::findOrFail($id);
        $user = Auth::user();

        // Strict Host Scoping: Only the assigned instructor of this lecture or admin can dismiss doubts
        $isHost = $user && ($user->isAdmin() || $user->id === $lecture->instructor_id || ($lecture->course && $user->id === $lecture->course->instructor_id));
        if (!$isHost) {
            return response()->json(['error' => 'Unauthorized: Only the host instructor can manage the question queue.'], 403);
        }

        $key = "live_raised_hands_{$id}";
        $hands = Cache::get($key, []);
        $hands = array_values(array_filter($hands, fn($h) => ($h['id'] ?? '') !== $handId));
        Cache::put($key, $hands, 3600);

        return response()->json(['status' => 'success', 'raised_hands' => $hands]);
    }

    /**
     * Download RFC 5545 iCalendar (.ics) file for Google / Apple Calendar sync.
     */
    public function downloadCalendarEvent($id)
    {
        $lecture = LiveLecture::with(['course', 'instructor'])->findOrFail($id);

        $start = $lecture->start_time ? $lecture->start_time->format('Ymd\THis\Z') : now()->format('Ymd\THis\Z');
        $end = $lecture->start_time ? $lecture->start_time->copy()->addMinutes(90)->format('Ymd\THis\Z') : now()->addMinutes(90)->format('Ymd\THis\Z');
        $classroomUrl = route('live.classroom', $lecture->id);

        $ics = "BEGIN:VCALENDAR\r\n"
             . "VERSION:2.0\r\n"
             . "PRODID:-//Digital Academy//Live Classroom Sync//EN\r\n"
             . "CALSCALE:GREGORIAN\r\n"
             . "METHOD:PUBLISH\r\n"
             . "BEGIN:VEVENT\r\n"
             . "UID:lecture-{$lecture->id}@digitalacademy.com\r\n"
             . "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n"
             . "DTSTART:{$start}\r\n"
             . "DTEND:{$end}\r\n"
             . "SUMMARY:" . addcslashes($lecture->title, ',;') . "\r\n"
             . "DESCRIPTION:Join live interactive cohort session on Digital Academy. URL: {$classroomUrl}\r\n"
             . "URL:{$classroomUrl}\r\n"
             . "STATUS:CONFIRMED\r\n"
             . "END:VEVENT\r\n"
             . "END:VCALENDAR\r\n";

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="digital-academy-lecture-' . $lecture->id . '.ics"',
        ]);
    }
}
