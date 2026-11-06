@extends('frontend.master')

@section('title', 'Create Your Learning Account – Digital Academy')

@section('content')
<div class="auth-split-wrapper py-4 py-md-5">
    <div class="container">
        <div class="row justify-content-center align-items-center g-4 g-lg-5">
            <!-- Left Side / Feature Checklist (Hidden on mobile) -->
            <div class="col-lg-6 d-none d-lg-block">
                <div class="pe-lg-4">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-4" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3);">
                        <i class="fas fa-rocket text-success"></i>
                        <span class="text-success fw-bold small">Join Digital Academy Today</span>
                    </div>
                    <h1 class="display-5 fw-bold mb-3">Where Ambition Meets World-Class Tech Education</h1>
                    <p class="text-muted fs-6 mb-4">
                        Learn directly from top engineers or launch your own technical cohorts. Join a high-performing community dedicated to building production systems.
                    </p>

                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(37, 99, 235, 0.15); color: var(--primary);">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Live Interactive Cohorts</h6>
                                <p class="text-muted small mb-0">Attend real-time masterclasses with senior architects, ask questions live and get instant answers.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(16, 185, 129, 0.15); color: var(--accent-emerald);">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Instructor Revenue Marketplace (80/20)</h6>
                                <p class="text-muted small mb-0">Instructors keep 80% of all student enrollments with direct automated payout schedules.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(6, 182, 212, 0.15); color: var(--accent-cyan);">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Full Curriculum & Free E-Book Library</h6>
                                <p class="text-muted small mb-0">Download high-res architectural cheat sheets, system design blueprints, and verified course certificates.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side / Register Card -->
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="auth-card p-4 p-sm-5 shadow-lg">
                    <!-- Brand Icon & Header -->
                    <div class="text-center mb-4">
                        <div class="auth-brand-badge mx-auto">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Create Your Account</h3>
                        <p class="text-muted small" id="authRoleSubtitle">Select your role to personalize your platform experience</p>
                    </div>

                    <!-- Role Selector Pill: Student vs Instructor -->
                    <div class="auth-role-selector" id="roleSelector">
                        <div class="auth-role-option active" data-role="student" onclick="selectRole('student')">
                            <i class="fas fa-user-graduate me-1"></i> I'm a Student
                        </div>
                        <div class="auth-role-option" data-role="instructor" onclick="selectRole('instructor')">
                            <i class="fas fa-chalkboard-teacher me-1"></i> I'm an Instructor
                        </div>
                    </div>

                    <!-- Registration Form -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Hidden Role Field -->
                        <input type="hidden" name="role" id="userRoleInput" value="student">

                        <!-- Full Name -->
                        <div class="auth-input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input id="name" type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" 
                                   placeholder="Full Name (e.g. Alex Johnson)" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback d-block mt-1"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="auth-input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   placeholder="Email address (e.g. alex@example.com)" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback d-block mt-1"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="auth-input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" placeholder="Create Password (min. 8 chars)" required autocomplete="new-password">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" aria-label="Toggle password visibility">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password')
                                <span class="invalid-feedback d-block mt-1"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="auth-input-group">
                            <i class="fas fa-check-double input-icon"></i>
                            <input id="password-confirm" type="password" 
                                   class="form-control" 
                                   name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password-confirm', this)" aria-label="Toggle password confirmation visibility">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <!-- Terms info -->
                        <div class="small text-muted mb-4">
                            By creating an account, you agree to our 
                            <a href="{{ route('pricing.index') }}" class="text-primary text-decoration-none">Terms of Service</a> & 
                            <a href="{{ route('pricing.index') }}" class="text-primary text-decoration-none">Anti-Fraud Guarantee</a>.
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-edu-primary w-100 justify-content-center py-2.5 fs-6 mb-3" id="submitBtn">
                            <i class="fas fa-check-circle me-2"></i> Join as Student
                        </button>

                        <!-- Switch to Login -->
                        <div class="text-center text-muted small mt-4">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none ms-1">
                                Sign in here <i class="fas fa-arrow-right fa-xs ms-1"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function selectRole(role) {
        document.getElementById('userRoleInput').value = role;
        const options = document.querySelectorAll('.auth-role-option');
        options.forEach(opt => {
            if (opt.getAttribute('data-role') === role) {
                opt.classList.add('active');
            } else {
                opt.classList.remove('active');
            }
        });

        const subtitle = document.getElementById('authRoleSubtitle');
        const submitBtn = document.getElementById('submitBtn');

        if (role === 'instructor') {
            subtitle.textContent = "Start sharing your knowledge, hosting live cohorts, and earn 80% revenue split";
            submitBtn.innerHTML = '<i class="fas fa-chalkboard-teacher me-2"></i> Register as Instructor';
        } else {
            subtitle.textContent = "Access cohort courses, live masterclasses, study guides & certificates";
            submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Join as Student';
        }
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }
</script>
@endsection
