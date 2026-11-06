<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CourseCatalogController extends Controller
{
    /**
     * Display course catalog with filtering.
     */
    public function index(Request $request)
    {
        $categorySlug = $request->input('category');
        $search = $request->input('search');

        $query = Course::with(['instructor', 'category', 'subjects']);

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $query->paginate(9);
        $categories = Cache::remember('ui_categories', 1800, function () {
            return Category::all();
        });

        return view('frontend.courses', compact('courses', 'categories'));
    }

    /**
     * Show detailed view of a course with discussion comments & instructor replies.
     */
    public function show($id)
    {
        $course = Course::with([
            'instructor',
            'category',
            'subjects.materials',
            'liveLectures' => function ($q) {
                $q->orderBy('start_time', 'asc');
            },
            'reviews.user',
            'comments.user',
            'comments.replies.user'
        ])->findOrFail($id);

        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->exists();
        }

        $relatedCourses = Course::where('id', '!=', $course->id)
            ->where(function ($q) use ($course) {
                $q->where('category_id', $course->category_id)
                  ->orWhere('instructor_id', $course->instructor_id);
            })
            ->take(3)
            ->get();

        return view('frontend.course_detail', compact('course', 'isEnrolled', 'relatedCourses'));
    }

    /**
     * Legacy route redirect to transparent, full course catalog.
     */
    public function selectPlan($plan = null)
    {
        return redirect()->route('courses.catalog');
    }
}
