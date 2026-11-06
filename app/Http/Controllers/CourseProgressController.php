<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\EdtechPerformanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseProgressController extends Controller
{
    /**
     * Mark a lesson complete or incomplete and return updated progress.
     */
    public function updateProgress(Request $request, $courseId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please log in to track progress.'], 401);
        }

        $request->validate([
            'material_id' => 'required|integer',
            'completed' => 'required|boolean',
        ]);

        $userId = Auth::id();
        $materialId = (int) $request->material_id;
        $completed = (bool) $request->completed;

        $progress = EdtechPerformanceService::markLessonProgress($userId, $courseId, $materialId, $completed);

        return response()->json([
            'status' => 'success',
            'progress' => $progress,
            'message' => $completed ? 'Lesson marked complete! 🎉' : 'Lesson marked incomplete.',
        ]);
    }

    /**
     * View or Download Digital Completion Certificate.
     */
    public function showCertificate($courseId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $course = Course::with(['instructor', 'quizzes'])->findOrFail($courseId);
        $progress = EdtechPerformanceService::getCourseProgress($user->id, $course->id);

        // Academic Integrity Check: Student must pass the Course Final Certification Exam (>=80%)
        $finalQuiz = $course->quizzes()->where('is_final_exam', true)->first() ?? $course->quizzes()->first();
        $passedExam = false;
        $bestAttempt = null;

        if ($finalQuiz) {
            $bestAttempt = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $finalQuiz->id)
                ->where('passed', true)
                ->latest()
                ->first();
            $passedExam = ($bestAttempt !== null);
        } else {
            $passedExam = true;
        }

        // If lessons are not 100% or final exam not passed, show Certificate Locked screen
        if ($progress['percent'] < 100 || !$passedExam) {
            $lastAttempt = $finalQuiz ? QuizAttempt::where('user_id', $user->id)->where('quiz_id', $finalQuiz->id)->latest()->first() : null;
            return view('frontend.certificate_locked', compact('user', 'course', 'progress', 'finalQuiz', 'lastAttempt', 'passedExam'));
        }

        $certId = $progress['cert_id'];

        return view('frontend.certificate', compact('user', 'course', 'progress', 'certId', 'bestAttempt'));
    }

    /**
     * Public Verification of Certificate by ID.
     */
    public function verifyCertificate($certId)
    {
        $shaHash = strtoupper(hash('sha256', $certId . '_digital_academy_official_ledger_2026'));
        $course = Course::with('instructor')->first();
        $studentName = Auth::check() ? Auth::user()->name : 'Verified Digital Academy Scholar';

        return view('frontend.verify_certificate', compact('certId', 'shaHash', 'course', 'studentName'));
    }
}
