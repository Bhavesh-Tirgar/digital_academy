@extends('frontend.master')

@section('title', 'Sign In to Your Learning Account – Digital Academy')

@section('content')
<div class="auth-split-wrapper py-4 py-md-5">
    <div class="container">
        <div class="row justify-content-center align-items-center g-4 g-lg-5">
            <!-- Left Side / Feature Highlight (Hidden on mobile) -->
            <div class="col-lg-6 d-none d-lg-block">
                <div class="pe-lg-4">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-4" style="background: var(--primary-light); border: 1px solid var(--primary-border);">
                        <i class="fas fa-sparkles text-primary"></i>
                        <span class="text-primary fw-bold small">Next-Generation Ed-Tech Platform</span>
                    </div>
                    <h1 class="display-5 fw-bold mb-3">Accelerate Your Engineering Career</h1>
                    <p class="text-muted fs-6 mb-4">
                        Access real-world software cohorts, weekly live masterclasses with industry leads, downloadable e-books, and direct mentor Q&A forums.
                    </p>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="edu-card-panel p-3">
                                <div class="text-primary fw-bold fs-4 mb-1">18,500+</div>
                                <div class="text-muted small">Active global learners</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="edu-card-panel p-3">
                                <div class="text-success fw-bold fs-4 mb-1">98.4%</div>
                                <div class="text-muted small">Course completion rate</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100" class="rounded-circle border border-2 border-primary" style="width: 36px; height: 36px; object-fit: cover;" alt="Avatar">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100" class="rounded-circle border border-2 border-primary ms-n2" style="width: 36px; height: 36px; object-fit: cover; margin-left: -10px;" alt="Avatar">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100" class="rounded-circle border border-2 border-primary ms-n2" style="width: 36px; height: 36px; object-fit: cover; margin-left: -10px;" alt="Avatar">
                        </div>
                        <div class="text-muted small">
                            Joined by developers from Google, Amazon, and Stripe.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side / Login Card -->
            <div class="col-12 col-md-8 col-lg-5 col-xl-5">
                <div class="auth-card p-4 p-sm-5 shadow-lg">
                    <!-- Brand Icon & Header -->
                    <div class="text-center mb-4">
                        <div class="auth-brand-badge mx-auto">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Welcome Back</h3>
                        <p class="text-muted small">Sign in to resume your lectures, tasks, and cohorts</p>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address Input -->
                        <div class="auth-input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   placeholder="Email address" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback d-block mt-1"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Password Input with Toggle -->
                        <div class="auth-input-group">
                            <i class="fas fa-key input-icon"></i>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" placeholder="Password" required autocomplete="current-password">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" aria-label="Toggle password visibility">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password')
                                <span class="invalid-feedback d-block mt-1"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted small" for="remember">
                                    Remember me
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-primary small text-decoration-none fw-semibold" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-edu-primary w-100 justify-content-center py-2.5 fs-6 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i> Sign In to Account
                        </button>

                        <!-- Switch to Register -->
                        <div class="text-center text-muted small mt-4">
                            Don't have an account yet? 
                            <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none ms-1">
                                Sign up for free <i class="fas fa-arrow-right fa-xs ms-1"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
