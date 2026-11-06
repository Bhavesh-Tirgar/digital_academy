@extends('frontend.master')

@section('title', 'Set New Password – Digital Academy')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="edu-card-panel p-4 p-md-5 shadow-lg">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: var(--primary-light); color: var(--primary);">
                        <i class="fas fa-lock-open fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ __('Set New Password') }}</h3>
                    <p class="text-muted small">Choose a strong password to secure your learning account.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="email" class="form-label text-muted small fw-semibold">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-muted small fw-semibold">{{ __('New Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Min. 8 characters" required autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label text-muted small fw-semibold">{{ __('Confirm New Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Repeat new password" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn-edu-primary w-100 justify-content-center py-2.5 mb-3">
                        <i class="fas fa-check-circle me-1"></i> {{ __('Reset Password') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
