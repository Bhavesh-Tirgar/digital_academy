<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\User;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Show checkout page or redirect guest to login.
     */
    public function checkout(Request $request, $id)
    {
        $course = Course::with(['instructor', 'subjects'])->findOrFail($id);

        // Core Requirement: If guest tries to purchase/enroll, redirect to login
        if (!Auth::check()) {
            session(['url.intended' => route('courses.checkout', $course->id)]);
            return redirect()->route('login')->with('info', 'Please sign in or create an account to enroll in ' . $course->name);
        }

        $user = Auth::user();

        // Check if already enrolled
        $existing = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return redirect()->route('courses.detail', $course->id)
                ->with('info', 'You are already enrolled in this course! Access your learning materials below.');
        }

        $plan = $request->query('plan', 'All-Access');
        $price = $course->price > 0 ? $course->price : 49.00;

        // Platform fee breakdown: 20% platform fee, 80% instructor share
        $platformFeePercent = 20;
        $platformFee = round($price * ($platformFeePercent / 100), 2);
        $instructorShare = round($price - $platformFee, 2);

        return view('frontend.checkout', compact('course', 'plan', 'price', 'platformFee', 'instructorShare'));
    }

    /**
     * Process enrollment and credit teacher (net of platform fees).
     */
    public function process(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $course = Course::findOrFail($id);
        $user = Auth::user();

        $plan = $request->input('plan', 'Premium');
        $amountPaid = $course->price > 0 ? $course->price : 49.00;
        $paymentMethod = $request->input('payment_method', 'card');
        $currency = $request->input('currency', 'USD');

        // Process charge through Payment Gateway Service
        $charge = PaymentGatewayService::processCharge($paymentMethod, $amountPaid, $currency, [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        if (!$charge['success']) {
            return back()->with('error', 'Payment authorization failed: ' . ($charge['error'] ?? 'Please check payment details.'));
        }

        // 20% platform commission fee, 80% instructor net earning
        $platformFee = round($amountPaid * 0.20, 2);
        $instructorEarning = round($amountPaid - $platformFee, 2);

        // Anti-Fraud Escrow Rule:
        // If course curriculum is already 100% verified & completed by teacher, funds are released directly.
        // Otherwise, funds are held safely in platform escrow until the teacher finishes the course.
        $isCourseCompleted = (bool) $course->is_completed;
        $payoutStatus = $isCourseCompleted ? 'released_to_instructor' : 'escrow_held';

        // 1. Create Enrollment record
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'plan' => 'Direct Enrollment',
            'amount_paid' => $amountPaid,
            'platform_fee' => $platformFee,
            'instructor_earning' => $instructorEarning,
            'status' => 'active',
            'payout_status' => $payoutStatus,
            'escrow_released_at' => $isCourseCompleted ? now() : null,
        ]);

        // 2. Increment student enrollment counter on the course
        $course->increment('enrolled_count');

        // 3. Credit Instructor via Anti-Fraud Escrow Engine
        if ($course->instructor_id) {
            $instructor = User::find($course->instructor_id);
            if ($instructor) {
                $instructor->increment('earnings', $instructorEarning);

                if ($isCourseCompleted) {
                    // Course verified complete -> direct into withdrawable balance
                    $instructor->increment('balance', $instructorEarning);
                    $notifMsg = "{$user->name} enrolled in '{$course->name}'. \${$instructorEarning} has been added to your withdrawable wallet.";
                    $notifTitle = '💰 New Student Enrollment (Paid)!';
                } else {
                    // Course in progress -> locked in anti-fraud escrow
                    $instructor->increment('escrow_balance', $instructorEarning);
                    $notifMsg = "{$user->name} enrolled in '{$course->name}'. \${$instructorEarning} is held in Anti-Fraud Escrow and will be released to your wallet once you complete the course curriculum!";
                    $notifTitle = '🛡️ Enrollment Payment Held in Escrow';
                }

                Notification::create([
                    'user_id' => $instructor->id,
                    'title' => $notifTitle,
                    'message' => $notifMsg,
                    'type' => 'payment',
                    'link' => route('instructor.dashboard'),
                ]);
            }
        }

        // 4. Send notification to Student
        Notification::create([
            'user_id' => $user->id,
            'title' => '🚀 Enrollment Confirmed!',
            'message' => "You have successfully enrolled in '{$course->name}'. Your materials and live schedule are ready.",
            'type' => 'course',
            'link' => route('courses.detail', $course->id),
        ]);

        return redirect()->route('student.dashboard')
            ->with('success', "Congratulations {$user->name}! You are now enrolled in {$course->name}. Track your progress and start learning below!");
    }
}
