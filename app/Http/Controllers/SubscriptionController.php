<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display the transparent Two-Tier Membership Pricing Page.
     * Tier 1: Student Platform Subscription ($29/mo or $199/yr)
     * Tier 2: Instructor / Creator Pro ($49/mo or $399/yr)
     */
    public function index()
    {
        return view('frontend.pricing');
    }

    /**
     * Show subscription checkout screen for instructors (Students have 100% free accounts & pay per course).
     */
    public function checkout(Request $request, $tier = 'instructor')
    {
        // Students do not have subscriptions; they enroll directly per course
        if ($tier === 'student') {
            return redirect()->route('courses.catalog')
                ->with('info', 'Students do not need a subscription! Create your free account and purchase whichever courses you want with lifetime access.');
        }

        if (!Auth::check()) {
            session(['url.intended' => route('subscription.checkout', ['tier' => 'instructor'])]);
            return redirect()->route('login')
                ->with('info', 'Please sign in or create an account to activate your Instructor Creator Pro membership.');
        }

        $tier = 'instructor';
        $billing = $request->query('billing', 'monthly');

        $pricingConfig = [
            'instructor' => [
                'name' => 'Instructor Creator Pro',
                'monthly' => 49.00,
                'yearly' => 399.00,
                'badge' => 'For Teachers & Mentors',
                'features' => [
                    'Publish unlimited technical cohorts & curriculum modules',
                    'In-browser Live Studio broadcasting (Webcam & Screen Sharing)',
                    'Launch real-time 30-sec Pop Quizzes & Polls with telemetry',
                    'Keep 80% of all student enrollments with Anti-Fraud Escrow payouts',
                    'Verified Instructor Answer Badge in community discussions',
                    'Advanced creator analytics, telemetry & student CRM',
                ],
            ],
        ];

        $planDetails = $pricingConfig[$tier];
        $price = $billing === 'yearly' ? $planDetails['yearly'] : $planDetails['monthly'];

        return view('frontend.subscription_checkout', compact('tier', 'billing', 'planDetails', 'price'));
    }

    /**
     * Process subscription activation.
     */
    public function process(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'tier' => 'required|in:instructor',
            'billing' => 'required|in:monthly,yearly',
        ]);

        $user = Auth::user();
        $tier = 'instructor';
        $billing = $request->billing;
        $paymentMethod = $request->input('payment_method', 'card');

        $price = ($billing === 'yearly' ? 399.00 : 49.00);

        // Process charge through Payment Gateway Service
        $charge = PaymentGatewayService::processCharge($paymentMethod, $price, 'USD', [
            'user_id' => $user->id,
            'tier' => $tier,
            'billing' => $billing,
        ]);

        if (!$charge['success']) {
            return back()->with('error', 'Subscription payment failed: ' . ($charge['error'] ?? 'Transaction declined.'));
        }

        $months = $billing === 'yearly' ? 12 : 1;
        $expiry = now()->addMonths($months);

        $user->subscription_type = $tier;
        $user->subscribed_until = $expiry;

        if ($tier === 'instructor') {
            $user->role = 'instructor';
            $user->role_id = 3;
            if (empty($user->headline)) {
                $user->headline = 'Verified Instructor at Digital Academy';
            }
        }

        $user->save();

        Notification::create([
            'user_id' => $user->id,
            'title' => '🎉 ' . ucfirst($tier) . ' Membership Activated!',
            'message' => "Welcome! Your {$tier} subscription is active until {$expiry->format('M d, Y')}.",
            'type' => 'enrollment',
            'link' => $tier === 'instructor' ? route('instructor.dashboard') : route('courses.catalog'),
        ]);

        $redirectRoute = $tier === 'instructor' ? route('instructor.dashboard') : route('student.dashboard');

        return redirect($redirectRoute)
            ->with('success', "Congratulations! Your " . ucfirst($tier) . " Membership is now active. Enjoy full platform privileges!");
    }
}
