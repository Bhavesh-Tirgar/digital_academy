@extends('frontend.master')

@section('title', 'Instructor Registration – Digital Academy')

@section('content')
<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">

            <div class="text-center mb-4">
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1 mb-2 small fw-bold">
                    <i class="fas fa-chalkboard-teacher me-1"></i> 80% Payout / 20% Fee Split
                </span>
                <h2 class="edu-section-title mb-1">Become an Instructor</h2>
                <p class="edu-section-sub mb-0">Register to create courses, schedule live cohorts, and teach thousands of global students.</p>
            </div>

            <div class="edu-card-panel p-4 p-md-5 shadow-lg">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 small mb-4" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('instructor.register') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold mb-1">Full Name:</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Dr. Jane Smith" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold mb-1">Email Address:</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="jane@example.com" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1">Password:</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1">Confirm Password:</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold mb-1">Professional Headline / Title:</label>
                        <input type="text" name="headline" class="form-control" value="{{ old('headline') }}" placeholder="e.g. Senior Machine Learning Engineer at Anthropic" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-semibold mb-1">Instructor Biography & What You Teach:</label>
                        <textarea name="bio" rows="4" class="form-control" placeholder="Briefly describe your industry background and what subjects you plan to teach..." required>{{ old('bio') }}</textarea>
                    </div>

                    <div class="p-3 rounded-3 mb-4 small text-muted" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                        <i class="fas fa-info-circle text-primary me-1"></i> By registering, you agree that Digital Academy deducts a transparent 20% platform service fee on course sales to maintain video hosting, live rooms, and student support. You keep 80% net take-home pay.
                    </div>

                    <button type="submit" class="btn-edu-primary w-100 justify-content-center py-2.5 fs-6">
                        Complete Teacher Registration <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="text-center mt-4 text-muted small">
                    Already have an instructor account? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">Sign in here</a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
