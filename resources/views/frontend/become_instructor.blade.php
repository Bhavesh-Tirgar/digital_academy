@extends('frontend.master')

@section('title', 'Teach on Digital Academy – Earn 80% Revenue Share with 20% Platform Fee')

@section('content')
<div class="container py-4 py-md-5">

    <!-- Hero Banner -->
    <div class="row align-items-center g-4 g-lg-5 mb-5 py-3">
        <div class="col-lg-7">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3);">
                <i class="fas fa-hand-holding-usd text-success"></i>
                <span class="text-success fw-bold small">80% Instructor Net Payout • 20% Platform Fee</span>
            </div>
            <h1 class="display-4 fw-bold mb-3">
                Teach What You Love. <br class="d-none d-md-block">
                Build a <span style="color: var(--primary);">High-Impact Academy</span>.
            </h1>
            <p class="text-muted fs-6 mb-4">
                Digital Academy allows software engineers, cloud architects, researchers, and tech leads to create live cohorts, sell structured video courses, and host interactive workshops with zero upfront technical overhead.
            </p>
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('instructor.register.form') }}" class="btn-edu-primary py-2.5 px-4 fs-6">
                    <i class="fas fa-chalkboard-teacher me-2"></i> Register as Instructor
                </a>
                <a href="#calculator" class="btn-edu-secondary py-2.5 px-4">
                    <i class="fas fa-calculator me-2"></i> Earnings Calculator
                </a>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="edu-card-panel p-4 shadow-lg" style="border-top: 3px solid var(--accent-emerald);">
                <h4 class="fw-bold mb-3"><i class="fas fa-chart-line text-success me-2"></i> Why Teach with Us?</h4>
                <div class="d-flex gap-3 mb-3">
                    <div class="text-success fs-4"><i class="fas fa-percentage"></i></div>
                    <div>
                        <div class="fw-bold">80% Instructor Revenue Share</div>
                        <div class="text-muted small">You keep 80% of every enrollment sale deposited directly into your balance.</div>
                    </div>
                </div>
                <div class="d-flex gap-3 mb-3">
                    <div class="text-primary fs-4"><i class="fas fa-server"></i></div>
                    <div>
                        <div class="fw-bold">20% Transparent Platform Fee</div>
                        <div class="text-muted small">Covers high-speed video hosting, live streaming rooms, payment gateways, and student customer support.</div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <div class="text-warning fs-4"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="fw-bold">Built-In Global Audience</div>
                        <div class="text-muted small">Instant exposure to thousands of ambitious engineering students and professionals.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Earnings Calculator -->
    <div id="calculator" class="edu-card-panel p-4 p-md-5 mb-5 shadow-lg" style="border-top: 3px solid var(--primary);">
        <div class="text-center mx-auto mb-5" style="max-width: 680px;">
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1 mb-2 small fw-bold">Platform Fee & Revenue Calculator</span>
            <h2 class="edu-section-title mb-2">How Much Can You Earn?</h2>
            <p class="edu-section-sub mb-0">Simulate your monthly revenue based on your course price and student cohort size.</p>
        </div>

        <div class="row g-4 g-lg-5 align-items-center">
            <div class="col-lg-6">
                <!-- Price Slider -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between fw-bold mb-2">
                        <span>Course Enrollment Price</span>
                        <span id="priceDisplay" class="text-primary fs-5">$69</span>
                    </div>
                    <input type="range" class="form-range" id="priceRange" min="20" max="250" step="5" value="69" oninput="calculateEarnings()">
                    <div class="d-flex justify-content-between text-muted small">
                        <span>$20</span>
                        <span>$250</span>
                    </div>
                </div>

                <!-- Students Slider -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between fw-bold mb-2">
                        <span>Estimated Students per Month</span>
                        <span id="studentsDisplay" class="text-info fs-5">75 Students</span>
                    </div>
                    <input type="range" class="form-range" id="studentsRange" min="10" max="500" step="5" value="75" oninput="calculateEarnings()">
                    <div class="d-flex justify-content-between text-muted small">
                        <span>10 Students</span>
                        <span>500 Students</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 rounded-4" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                    <div class="text-muted small text-uppercase fw-bold mb-3">Estimated Monthly Breakdown</div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Gross Sales:</span>
                        <span id="grossDisplay" class="fw-bold">$5,175.00</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Platform Fee (20%):</span>
                        <span id="feeDisplay">-$1,035.00</span>
                    </div>

                    <div class="d-flex justify-content-between py-3 border-top align-items-baseline" style="border-color: var(--border-color) !important;">
                        <div>
                            <span class="text-success fw-bold fs-6">Your Net Take-Home (80%):</span>
                            <div class="text-muted" style="font-size: 0.75rem;">Paid out automatically to your account</div>
                        </div>
                        <span id="netDisplay" class="text-success display-6 fw-bold">+$4,140.00</span>
                    </div>

                    <a href="{{ route('instructor.register.form') }}" class="btn-edu-primary w-100 justify-content-center mt-3 py-2.5">
                        Start Teaching & Earn This <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructor Testimonials -->
    <div class="my-5">
        <div class="text-center mb-4">
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-3 py-1 mb-2 small fw-bold">Teacher Stories</span>
            <h2 class="edu-section-title mb-1">Hear From Top Instructors</h2>
        </div>

        <div class="row g-4">
            @foreach($featuredInstructors as $inst)
                <div class="col-md-4">
                    <div class="edu-card-panel h-100 shadow-sm p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ $inst->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" alt="{{ $inst->name }}" class="rounded-circle border border-primary" style="width: 48px; height: 48px; object-fit: cover;">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $inst->name }}</h6>
                                <div class="text-muted small">{{ $inst->headline ?? 'Senior Lead Mentor' }}</div>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">"{{ Str::limit($inst->bio, 140) }}"</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Interactive Calculator Script -->
<script>
    function calculateEarnings() {
        const price = parseFloat(document.getElementById('priceRange').value);
        const students = parseInt(document.getElementById('studentsRange').value);

        document.getElementById('priceDisplay').textContent = '$' + price;
        document.getElementById('studentsDisplay').textContent = students + ' Students';

        const gross = price * students;
        const fee = gross * 0.20;
        const net = gross * 0.80;

        document.getElementById('grossDisplay').textContent = '$' + gross.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('feeDisplay').textContent = '-$' + fee.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('netDisplay').textContent = '+$' + net.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
</script>
@endsection
