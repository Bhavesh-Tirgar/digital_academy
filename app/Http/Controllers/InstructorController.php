<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\LiveLecture;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstructorController extends Controller
{
    /**
     * Become an Instructor Landing Page with Platform Fee Calculator.
     */
    public function landing()
    {
        $featuredInstructors = User::where('role', 'instructor')
            ->orWhere('role_id', 3)
            ->take(3)
            ->get();

        return view('frontend.become_instructor', compact('featuredInstructors'));
    }

    /**
     * Show Instructor Registration Form.
     */
    public function showRegister()
    {
        return view('frontend.instructor_register');
    }

    /**
     * Handle Instructor Registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'headline' => 'required|string|max:255',
            'bio' => 'required|string|min:30',
        ]);

        $instructor = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'instructor',
            'role_id' => 3,
            'headline' => $request->headline,
            'bio' => $request->bio,
            'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&auto=format&fit=crop&q=80',
            'balance' => 0.00,
            'earnings' => 0.00,
        ]);

        Auth::login($instructor);

        return redirect()->route('instructor.dashboard')
            ->with('success', 'Welcome to the Digital Academy Instructor Community! Your instructor account is ready.');
    }

    /**
     * Instructor Dashboard & Portal.
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Ensure user is an instructor or admin
        if (!$user->isInstructor() && !$user->isAdmin()) {
            return redirect()->route('student.dashboard')
                ->with('info', 'You are currently signed in as a student. The Instructor Portal is reserved for verified course creators. To teach and earn 80% revenue, register as an instructor!');
        }

        $courses = Course::with(['category', 'enrollments', 'subjects.materials', 'quizzes'])
            ->where('instructor_id', $user->id)
            ->latest()
            ->get();

        $lectures = LiveLecture::where('instructor_id', $user->id)
            ->orderBy('start_time', 'asc')
            ->get();

        $enrollments = Enrollment::with(['user', 'course'])
            ->whereIn('course_id', $courses->pluck('id'))
            ->latest()
            ->take(15)
            ->get();

        $totalStudents = $courses->sum('enrolled_count');
        $totalNetEarnings = $user->earnings ?? 0;
        $escrowBalance = $user->escrow_balance ?? 0.00;
        $withdrawableBalance = $user->balance ?? 0.00;

        // Platform fee is 20% on gross, teacher keeps 80% (Gross = Earnings / 0.8)
        $grossSales = $totalNetEarnings > 0 ? round($totalNetEarnings / 0.80, 2) : 0;
        $totalPlatformFee = round($grossSales * 0.20, 2);

        $categories = Category::all();

        return view('frontend.instructor_dashboard', compact(
            'courses',
            'lectures',
            'enrollments',
            'totalStudents',
            'totalNetEarnings',
            'escrowBalance',
            'withdrawableBalance',
            'totalPlatformFee',
            'grossSales',
            'categories'
        ));
    }

    /**
     * Anti-Fraud Escrow Release:
     * When teacher completes the course curriculum (modules + final quiz),
     * verify academic integrity and release all escrowed enrollment funds to withdrawable balance.
     */
    public function markCourseComplete(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $course = Course::with(['subjects.materials', 'quizzes'])->findOrFail($id);

        if ($course->instructor_id !== $user->id && !$user->isAdmin()) {
            return back()->with('error', 'Unauthorized: You are not the instructor of this course.');
        }

        // Anti-Fraud Academic Integrity Check
        $totalMaterials = \App\Models\Material::whereHas('subject', function ($q) use ($course) {
            $q->where('course_id', $course->id);
        })->count();

        if ($totalMaterials === 0) {
            return back()->with('error', 'Anti-Fraud Alert: You must upload at least one video or study material before marking this course complete.');
        }

        $totalQuizzes = \App\Models\Quiz::where('course_id', $course->id)->count();
        if ($totalQuizzes === 0) {
            return back()->with('error', 'Anti-Fraud Alert: You must create a final certification exam before marking the curriculum complete.');
        }

        // Mark course completed
        $course->is_completed = true;
        $course->completed_at = now();
        $course->save();

        // Release all escrowed enrollment funds for this course
        $escrowEnrollments = Enrollment::where('course_id', $course->id)
            ->where('payout_status', 'escrow_held')
            ->get();

        $releasedTotal = $escrowEnrollments->sum('instructor_earning');

        if ($releasedTotal > 0) {
            Enrollment::where('course_id', $course->id)
                ->where('payout_status', 'escrow_held')
                ->update([
                    'payout_status' => 'released_to_instructor',
                    'escrow_released_at' => now(),
                ]);

            // Transfer from escrow_balance to withdrawable balance
            $user->escrow_balance = max(0, (float)$user->escrow_balance - (float)$releasedTotal);
            $user->balance = (float)$user->balance + (float)$releasedTotal;
            $user->save();

            Notification::create([
                'user_id' => $user->id,
                'title' => '🎉 Escrow Released: Funds Available!',
                'message' => "Congratulations! '{$course->name}' verified complete. \${$releasedTotal} has been transferred from Anti-Fraud Escrow to your Withdrawable Wallet.",
                'type' => 'payment',
                'link' => route('instructor.dashboard'),
            ]);

            return back()->with('success', "Course verified complete! Anti-fraud escrow released: \${$releasedTotal} has been credited to your withdrawable balance.");
        }

        return back()->with('success', "Course verified complete! Any new student enrollments will be credited immediately to your withdrawable balance.");
    }

    /**
     * Store new course created by instructor.
     */
    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'level' => 'required|string',
            'duration' => 'required|string',
            'demo_video_url' => 'nullable|url',
            'what_you_will_learn' => 'nullable|string',
        ]);

        $course = Course::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . rand(100, 999),
            'instructor_id' => Auth::id(),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'level' => $request->level,
            'duration' => $request->duration,
            'demo_video_url' => $request->demo_video_url ?: null,
            'thumbnail' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop&q=80',
            'enrolled_count' => 0,
            'rating' => 5.0,
            'what_you_will_learn' => $request->what_you_will_learn,
        ]);

        return redirect()->route('instructor.dashboard')
            ->with('success', "Course '{$course->name}' published successfully! Students can now enroll.");
    }

    /**
     * Store new live lecture scheduled by instructor.
     */
    public function storeLecture(Request $request)
    {
        $request->validate([
            'course_id' => 'nullable|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'status' => 'required|in:scheduled,live,completed',
            'is_demo' => 'nullable|boolean',
            'stream_url' => 'nullable|url',
        ]);

        $lecture = LiveLecture::create([
            'instructor_id' => Auth::id(),
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'stream_url' => $request->stream_url,
            'status' => $request->status,
            'is_demo' => $request->has('is_demo'),
            'active_viewers' => 120,
        ]);

        // Automatically assign native in-platform classroom link (No Google Meet / Zoom needed!)
        $lecture->meeting_link = route('live.classroom', $lecture->id);
        $lecture->save();

        return redirect()->route('instructor.dashboard')
            ->with('success', "Live lecture '{$request->title}' scheduled in your native in-browser Live Studio!");
    }
}
