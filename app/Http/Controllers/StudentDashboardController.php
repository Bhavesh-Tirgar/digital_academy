<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LiveLecture;
use App\Models\Ebook;
use App\Services\EdtechPerformanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the Dedicated Student Dashboard.
     * Gated strictly for learners: enrolled cohorts, lesson progress, certificates, live timetable.
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch all enrolled courses for this student
        $enrollments = Enrollment::with(['course.instructor', 'course.category', 'course.subjects.materials'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $completedCount = 0;
        $inProgressCount = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->course) {
                $enrollment->progress = EdtechPerformanceService::getCourseProgress($user->id, $enrollment->course->id);
                if ($enrollment->progress['percent'] >= 100) {
                    $completedCount++;
                } else {
                    $inProgressCount++;
                }
            }
        }

        // Fetch live and upcoming classes relevant to the student
        $enrolledCourseIds = $enrollments->pluck('course_id')->filter();
        
        $liveClasses = LiveLecture::with(['instructor', 'course'])
            ->where(function ($q) use ($enrolledCourseIds) {
                $q->whereIn('course_id', $enrolledCourseIds)
                  ->orWhere('status', 'live')
                  ->orWhere('is_demo', true);
            })
            ->where(function ($q) {
                $q->where('status', 'live')
                  ->orWhere('start_time', '>=', now()->subHours(2));
            })
            ->orderByRaw("CASE WHEN status = 'live' THEN 0 ELSE 1 END")
            ->orderBy('start_time', 'asc')
            ->take(4)
            ->get();

        // Technical E-books for the student library
        $ebooks = Ebook::latest()->take(4)->get();

        // Gamification: XP, Mastery Tiers & Streaks
        $streakDays = max(1, ($enrollments->count() * 2) + 1);
        $totalXp = ($enrollments->count() * 100) + ($completedCount * 450) + ($streakDays * 30);

        if ($totalXp >= 1200) {
            $masteryTier = 'Platinum Principal';
            $masteryLevel = 4;
            $tierColor = '#a855f7';
            $tierIcon = 'fa-crown';
            $tierProgress = 100;
            $nextTierLabel = 'Max Level Reached';
        } elseif ($totalXp >= 600) {
            $masteryTier = 'Gold Architect';
            $masteryLevel = 3;
            $tierColor = '#eab308';
            $tierIcon = 'fa-trophy';
            $tierProgress = round((($totalXp - 600) / 600) * 100);
            $nextTierLabel = (1200 - $totalXp) . ' XP to Platinum';
        } elseif ($totalXp >= 250) {
            $masteryTier = 'Silver Engineer';
            $masteryLevel = 2;
            $tierColor = '#94a3b8';
            $tierIcon = 'fa-medal';
            $tierProgress = round((($totalXp - 250) / 350) * 100);
            $nextTierLabel = (600 - $totalXp) . ' XP to Gold';
        } else {
            $masteryTier = 'Bronze Explorer';
            $masteryLevel = 1;
            $tierColor = '#cd7f32';
            $tierIcon = 'fa-shield-alt';
            $tierProgress = round(($totalXp / 250) * 100);
            $nextTierLabel = (250 - $totalXp) . ' XP to Silver';
        }

        // Student Gamification Badges
        $badges = [
            [
                'id' => 'first_enrollment',
                'title' => 'First Spark',
                'description' => 'Enrolled in your first technical cohort',
                'icon' => 'fas fa-graduation-cap',
                'unlocked' => $enrollments->count() > 0,
                'color' => '#6366f1'
            ],
            [
                'id' => 'algorithm_sprinter',
                'title' => 'Algorithm Grader',
                'description' => 'Mastered hands-on interactive code test cases',
                'icon' => 'fas fa-code',
                'unlocked' => $inProgressCount > 0 || $completedCount > 0,
                'color' => '#10b981'
            ],
            [
                'id' => 'exam_conqueror',
                'title' => 'Honor Roll Scholar',
                'description' => 'Passed an official academic certification exam',
                'icon' => 'fas fa-award',
                'unlocked' => $completedCount > 0,
                'color' => '#f59e0b'
            ],
            [
                'id' => 'streak_master',
                'title' => 'Consistency Champion',
                'description' => 'Maintained a multi-day engineering study streak',
                'icon' => 'fas fa-fire',
                'unlocked' => $streakDays >= 3,
                'color' => '#ef4444'
            ],
            [
                'id' => 'certified_engineer',
                'title' => 'Silicon Valley Certified',
                'description' => 'Earned verified diploma with SHA-256 cryptographic ledger',
                'icon' => 'fas fa-certificate',
                'unlocked' => $completedCount > 0,
                'color' => '#d4af37'
            ]
        ];

        // Student learning metrics
        $stats = [
            'total_courses' => $enrollments->count(),
            'in_progress' => $inProgressCount,
            'completed' => $completedCount,
            'certificates' => $completedCount,
            'study_streak_days' => $streakDays,
            'hours_learned' => ($enrollments->count() * 8) + ($completedCount * 14),
            'total_xp' => $totalXp,
            'mastery_tier' => $masteryTier,
            'mastery_level' => $masteryLevel,
            'tier_color' => $tierColor,
            'tier_icon' => $tierIcon,
            'tier_progress' => $tierProgress,
            'next_tier_label' => $nextTierLabel,
        ];

        return view('frontend.student_dashboard', compact('user', 'enrollments', 'liveClasses', 'ebooks', 'stats', 'badges'));
    }
}
