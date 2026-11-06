@extends('frontend.master')

@section('title', 'Confirm Your Password – Digital Academy')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="edu-card-panel p-4 p-md-5 shadow-lg">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: var(--primary-light); color: var(--primary);">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ __('Confirm Password') }}</h3>
                    <p class="text-muted small">{{ __('Please confirm your password before continuing to this protected area.') }}</p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="password" class="form-label text-muted small fw-semibold">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-edu-primary w-100 justify-content-center py-2.5 mb-3">
                        <i class="fas fa-unlock-alt me-1"></i> {{ __('Confirm Password') }}
                    </button>

                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a class="text-primary small text-decoration-none" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
