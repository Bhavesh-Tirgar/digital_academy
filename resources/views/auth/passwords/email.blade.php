@extends('frontend.master')

@section('title', 'Reset Your Password – Digital Academy')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="edu-card-panel p-4 p-md-5 shadow-lg">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: var(--primary-light); color: var(--primary);">
                        <i class="fas fa-key fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ __('Reset Password') }}</h3>
                    <p class="text-muted small">Enter your email and we'll send you a secure link to reset your password.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success border-0 small mb-4" role="alert" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                        <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label text-muted small fw-semibold">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-edu-primary w-100 justify-content-center py-2.5 mb-3">
                        <i class="fas fa-paper-plane me-1"></i> {{ __('Send Reset Link') }}
                    </button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i> Back to Sign In
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
