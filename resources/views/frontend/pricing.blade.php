@extends('frontend.master')

@section('title', 'Platform Pricing & Anti-Fraud Guarantee – Digital Academy')

@section('content')
<div class="container py-4 py-md-5">

    <!-- Header Section -->
    <div class="text-center mb-5 mx-auto" style="max-width: 720px;">
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-3 py-1 rounded-pill small fw-bold mb-2">
            TRANSPARENT CREATOR MARKETPLACE
        </span>
        <h1 class="edu-section-title mb-3">Simple, Honest Platform Pricing</h1>
        <p class="edu-section-sub mb-4">
            Students join 100% free with zero subscription traps—buy only the courses you want. Instructors keep 80% net revenue protected by our Anti-Fraud Escrow guarantee.
        </p>

        <!-- Instructor Billing Toggle (Monthly vs Annual) -->
        <div class="d-inline-flex align-items-center gap-2 p-1 rounded-pill mt-2 edu-card-panel" style="border: 1px solid var(--border-color);">
            <button type="button" class="btn btn-sm px-4 rounded-pill fw-bold" id="billingMonthlyBtn" onclick="setBillingCycle('monthly')" style="background: var(--primary); color: #fff;">
                Monthly
            </button>
            <button type="button" class="btn btn-sm px-4 rounded-pill fw-bold text-muted" id="billingYearlyBtn" onclick="setBillingCycle('yearly')">
                Yearly <span class="badge bg-success bg-opacity-25 text-success ms-1" style="font-size: 0.65rem;">Save ~40%</span>
            </button>
        </div>
    </div>

    <!-- Pricing Cards Grid -->
    <div class="row g-4 justify-content-center mb-5">

        <!-- TIER 1: Student Free Account (No Subscriptions) -->
        <div class="col-lg-5 col-xl-5">
            <div class="edu-card-panel h-100 p-4 p-md-5 d-flex flex-column justify-content-between position-relative shadow-lg" style="border-top: 3px solid var(--primary);">
                <div class="position-absolute top-0 end-0 m-4">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1 rounded-pill small fw-bold">
                        100% Free Account
                    </span>
                </div>

                <div>
                    <div class="d-inline-flex p-3 rounded-4 mb-3" style="background: var(--primary-light); color: var(--primary);">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-1 fs-4">Student Account</h3>
                    <p class="text-muted small mb-4">Zero subscription fees. Simply create your account, browse courses, and purchase lifetime access directly from verified instructors.</p>

                    <div class="d-flex align-items-baseline gap-2 mb-4">
                        <span class="display-4 fw-bold" style="color: var(--text-primary);">$0</span>
                        <span class="text-muted">/ Free Forever (Pay per course)</span>
                    </div>

                    <div class="border-top pt-4 mb-4" style="border-color: var(--border-color) !important;">
                        <div class="small fw-bold mb-3 text-uppercase letter-spacing-1">What Every Student Gets:</div>
                        <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>No Subscription Trap:</strong> Never get charged recurring monthly fees. You own your enrolled courses for life.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>Anti-Fraud Escrow Protection:</strong> Your payment is held safely until the teacher completes the full curriculum.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>Smart Video Player:</strong> Automatic playback resume from last timestamp + 0.75x–2.0x speed selector.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>Interactive Coding Labs:</strong> Autograded unit test assertions in browser (LeetCode style).</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>Live Cohort Masterclasses:</strong> Join in-browser live lectures with 1-click Google / Apple calendar sync.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>Verified Digital Certificates:</strong> Accredited certificates issued once you pass the final exam (≥ 80%).</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pt-3">
                    <a href="{{ route('courses.catalog') }}" class="btn-edu-primary w-100 justify-content-center py-2.5 fs-6 rounded-pill">
                        <i class="fas fa-compass me-2"></i> Browse Course Catalog
                    </a>
                    <div class="text-center text-muted small mt-2" style="font-size: 0.75rem;">
                        No credit card required to sign up.
                    </div>
                </div>
            </div>
        </div>

        <!-- TIER 2: Instructor / Creator Pro -->
        <div class="col-lg-5 col-xl-5">
            <div class="edu-card-panel h-100 p-4 p-md-5 d-flex flex-column justify-content-between position-relative shadow-lg" style="border-top: 3px solid var(--accent-emerald);">
                <div class="position-absolute top-0 end-0 m-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-3 py-1 rounded-pill small fw-bold">
                        For Course Creators
                    </span>
                </div>

                <div>
                    <div class="d-inline-flex p-3 rounded-4 mb-3" style="background: rgba(16, 185, 129, 0.15); color: var(--accent-emerald);">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-1 fs-4">Instructor Creator Studio</h3>
                    <p class="text-muted small mb-4">For engineers and educators looking to publish technical courses, broadcast live masterclasses, and monetize knowledge.</p>

                    <div class="d-flex align-items-baseline gap-2 mb-4">
                        <span class="display-4 fw-bold" id="instructorPriceLabel" data-price-usd="49">$49</span>
                        <span class="text-muted" id="instructorBillingPeriod">/ month</span>
                    </div>

                    <div class="border-top pt-4 mb-4" style="border-color: var(--border-color) !important;">
                        <div class="small fw-bold mb-3 text-uppercase letter-spacing-1">Creator Privileges:</div>
                        <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>Publish Unlimited Courses:</strong> Create cohorts, upload video lectures, notes, and worksheets.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>Native In-Browser Live Studio:</strong> Broadcast with webcam and screen sharing (No Zoom or Google Meet needed!).</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>Keep 80% Net Revenue:</strong> You keep 80% of every course sale. Platform fee is only 20%.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>Anti-Fraud Milestone Payouts:</strong> Automated escrow release to your withdrawable wallet once you complete the curriculum.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>Exam & Pop Quiz Builder:</strong> Create final certification exams with custom passing thresholds and answer explanations.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 small text-secondary">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span><strong>Verified Instructor Badge:</strong> Green verified instructor badge across discussion Q&A and community forums.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pt-3">
                    <a href="{{ route('subscription.checkout', ['tier' => 'instructor', 'billing' => 'monthly']) }}" id="instructorCtaBtn" class="btn btn-outline-success w-100 justify-content-center py-2.5 fs-6 rounded-pill fw-bold">
                        <i class="fas fa-chalkboard-teacher me-2"></i> Join as Instructor Pro
                    </a>
                    <div class="text-center text-muted small mt-2" style="font-size: 0.75rem;">
                        Keep 80% of student enrollments • Anti-fraud guaranteed payouts.
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- FAQ Accordion -->
    <div class="mx-auto mt-5" style="max-width: 740px;">
        <h3 class="fw-bold text-center mb-4 fs-4">Frequently Asked Questions</h3>

        <div class="accordion modern-accordion" id="pricingFaqAccordion">
            <div class="accordion-item edu-card-panel mb-3 overflow-hidden p-0 border">
                <h2 class="accordion-header" id="faq1">
                    <button class="accordion-button collapsed py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" style="background: var(--bg-card); color: var(--text-primary);">
                        Do students need to pay a subscription?
                    </button>
                </h2>
                <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#pricingFaqAccordion">
                    <div class="accordion-body text-secondary small px-4 pb-4">
                        <strong>No, never!</strong> Digital Academy does not charge students recurring subscriptions. Students create a 100% free account and only pay for the individual courses they want to take, with lifetime access, coding lab sandboxes, and verified completion certificates.
                    </div>
                </div>
            </div>

            <div class="accordion-item edu-card-panel mb-3 overflow-hidden p-0 border">
                <h2 class="accordion-header" id="faq2">
                    <button class="accordion-button collapsed py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" style="background: var(--bg-card); color: var(--text-primary);">
                        How does the Anti-Fraud Escrow System protect students and teachers?
                    </button>
                </h2>
                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#pricingFaqAccordion">
                    <div class="accordion-body text-secondary small px-4 pb-4">
                        When a student buys a course, the instructor's 80% share is securely placed into <strong>Platform Escrow</strong>. The funds are only unlocked and transferred to the instructor's withdrawable wallet once the teacher marks the course curriculum <strong>100% complete</strong> with uploaded lessons and a final certification exam. This guarantees students get what they paid for, prevents abandoned courses, and ensures honest instructors get paid reliably!
                    </div>
                </div>
            </div>

            <div class="accordion-item edu-card-panel mb-3 overflow-hidden p-0 border">
                <h2 class="accordion-header" id="faq3">
                    <button class="accordion-button collapsed py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" style="background: var(--bg-card); color: var(--text-primary);">
                        How does the 80/20 Instructor Revenue Split work?
                    </button>
                </h2>
                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#pricingFaqAccordion">
                    <div class="accordion-body text-secondary small px-4 pb-4">
                        When a student enrolls in your course, 80% of the price is credited to your instructor escrow balance. The platform takes 20% to cover HD video CDN streaming, server infrastructure, payment processing fees, and student support. Once you mark the course complete, funds move to your withdrawable wallet.
                    </div>
                </div>
            </div>

            <div class="accordion-item edu-card-panel mb-3 overflow-hidden p-0 border">
                <h2 class="accordion-header" id="faq4">
                    <button class="accordion-button collapsed py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" style="background: var(--bg-card); color: var(--text-primary);">
                        Do I need Google Meet or Zoom for live lectures?
                    </button>
                </h2>
                <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#pricingFaqAccordion">
                    <div class="accordion-body text-secondary small px-4 pb-4">
                        No! Digital Academy features a 100% native in-platform Live Virtual Classroom. Instructors can broadcast their webcam, share their screen, launch live pop quizzes, and chat with students directly in the browser.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function setBillingCycle(cycle) {
        const monthlyBtn = document.getElementById('billingMonthlyBtn');
        const yearlyBtn = document.getElementById('billingYearlyBtn');
        const priceLabel = document.getElementById('instructorPriceLabel');
        const periodLabel = document.getElementById('instructorBillingPeriod');
        const ctaBtn = document.getElementById('instructorCtaBtn');

        if (cycle === 'yearly') {
            monthlyBtn.style.background = 'transparent';
            monthlyBtn.style.color = 'var(--text-muted)';
            yearlyBtn.style.background = 'var(--primary)';
            yearlyBtn.style.color = '#fff';

            priceLabel.textContent = '$29';
            priceLabel.setAttribute('data-price-usd', '29');
            periodLabel.textContent = '/ month (billed annually)';
            ctaBtn.href = "{{ route('subscription.checkout', ['tier' => 'instructor', 'billing' => 'yearly']) }}";
        } else {
            yearlyBtn.style.background = 'transparent';
            yearlyBtn.style.color = 'var(--text-muted)';
            monthlyBtn.style.background = 'var(--primary)';
            monthlyBtn.style.color = '#fff';

            priceLabel.textContent = '$49';
            priceLabel.setAttribute('data-price-usd', '49');
            periodLabel.textContent = '/ month';
            ctaBtn.href = "{{ route('subscription.checkout', ['tier' => 'instructor', 'billing' => 'monthly']) }}";
        }
    }
</script>
@endsection
