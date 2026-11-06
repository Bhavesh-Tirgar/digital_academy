<?php
namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Review;
use App\Models\Course;
use App\Models\LiveLecture;
use App\Models\Ebook;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UiController extends Controller
{
    /**
     * High-concurrency cached landing page for 1,000+ concurrent users.
     */
    public function index()
    {
        $categories = Cache::remember('ui_categories', 1800, function () {
            return Category::all();
        });

        $blogs = Cache::remember('ui_blogs', 600, function () {
            return Blog::latest()->take(3)->get();
        });

        $reviews = Cache::remember('ui_reviews', 300, function () {
            return Review::with(['user', 'course'])->latest()->take(6)->get();
        });

        $courses = Cache::remember('ui_courses', 300, function () {
            return Course::with(['instructor', 'category', 'subjects'])->get();
        });
        
        $liveLectures = Cache::remember('ui_live_lectures', 120, function () {
            return LiveLecture::with(['instructor', 'course'])
                ->orderBy('start_time', 'asc')
                ->take(4)
                ->get();
        });
            
        $liveNow = $liveLectures->firstWhere('status', 'live') ?? $liveLectures->first();

        $ebooks = Cache::remember('ui_ebooks', 900, function () {
            return Ebook::take(4)->get();
        });

        $notifications = Notification::latest()->take(5)->get();

        $instructors = Cache::remember('ui_instructors', 900, function () {
            return User::where('role', 'instructor')->orWhere('role_id', 3)->get();
        });

        $stats = Cache::remember('ui_stats', 300, function () use ($instructors, $courses) {
            return [
                'students' => $courses->sum('enrolled_count') + 1540,
                'courses' => $courses->count(),
                'instructors' => $instructors->count(),
                'satisfaction' => '99.4%',
            ];
        });

        return view('frontend.index', compact(
            'categories',
            'blogs',
            'reviews',
            'courses',
            'liveLectures',
            'liveNow',
            'ebooks',
            'notifications',
            'instructors',
            'stats'
        ));
    }
}
