<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show Quiz / Examination Screen.
     */
    public function show($courseId, $quizId = null)
    {
        $course = Course::findOrFail($courseId);
        $user = Auth::user();

        // Scoped Authorization: User must be Course Author, Admin, OR Enrolled Student.
        // Another teacher cannot take exams for a course they do not own unless they enroll.
        $isCourseAuthor = ($user->id === $course->instructor_id);
        $isAdmin = $user->isAdmin();
        $isEnrolledStudent = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if (!$isCourseAuthor && !$isAdmin && !$isEnrolledStudent) {
            return redirect()->route('courses.checkout', $course->id)
                ->with('info', "Taking the official certification exam for '{$course->name}' requires active cohort enrollment. Please enroll below!");
        }

        $quiz = $quizId 
            ? Quiz::with('questions')->where('course_id', $course->id)->find($quizId)
            : Quiz::with('questions')->where('course_id', $course->id)->first();

        if (!$quiz || $quiz->questions->isEmpty()) {
            return redirect()->route('frontend.materials', $course->id)
                ->with('info', "The certification exam for '{$course->name}' is currently being updated by the instructor. Continue reviewing the syllabus below!");
        }

        $previousAttempts = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->get();

        $bestAttempt = $previousAttempts->where('passed', true)->first() ?? $previousAttempts->first();

        return view('frontend.quiz', compact('course', 'quiz', 'previousAttempts', 'bestAttempt'));
    }

    /**
     * Grade Quiz Submission Server-Side.
     */
    public function submit(Request $request, $courseId, $quizId)
    {
        $course = Course::findOrFail($courseId);
        $quiz = Quiz::with('questions')->where('course_id', $course->id)->findOrFail($quizId);
        $user = Auth::user();

        // Enforce authorization before grading
        $isCourseAuthor = ($user->id === $course->instructor_id);
        $isAdmin = $user->isAdmin();
        $isEnrolledStudent = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if (!$isCourseAuthor && !$isAdmin && !$isEnrolledStudent) {
            return redirect()->route('courses.checkout', $course->id);
        }

        $submittedAnswers = $request->input('answers', []);
        $correctCount = 0;
        $totalQuestions = $quiz->questions->count();
        $breakdown = [];

        foreach ($quiz->questions as $index => $q) {
            $studentChoice = isset($submittedAnswers[$q->id]) ? (int) $submittedAnswers[$q->id] : -1;
            $isCorrect = ($studentChoice === (int) $q->correct_option);

            if ($isCorrect) {
                $correctCount++;
            }

            $breakdown[] = [
                'question_id' => $q->id,
                'question' => $q->question,
                'options' => $q->options,
                'student_choice' => $studentChoice,
                'correct_option' => $q->correct_option,
                'is_correct' => $isCorrect,
                'explanation' => $q->explanation,
            ];
        }

        $percentage = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 1) : 0;
        $passed = ($percentage >= $quiz->pass_percentage);

        // Store attempt in database
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $correctCount,
            'total_questions' => $totalQuestions,
            'percentage' => $percentage,
            'passed' => $passed,
            'answers' => $breakdown,
        ]);

        return response()->json([
            'status' => 'success',
            'attempt_id' => $attempt->id,
            'score' => $correctCount,
            'total' => $totalQuestions,
            'percentage' => $percentage,
            'passed' => $passed,
            'pass_percentage' => $quiz->pass_percentage,
            'certificate_url' => $passed ? route('courses.certificate', $course->id) : null,
            'breakdown' => $breakdown,
            'xp_earned' => $passed ? ($percentage >= 90 ? 250 : 150) : 25,
        ]);
    }
}
