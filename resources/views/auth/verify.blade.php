@extends('frontend.master')

@section('title', 'Verify Your Email Address – Digital Academy')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="edu-card-panel p-4 p-md-5 text-center shadow-lg">
                <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: var(--primary-light); color: var(--primary);">
                    <i class="fas fa-envelope-open-text fa-2x"></i>
                </div>

                <h3 class="fw-bold mb-2">{{ __('Verify Your Email Address') }}</h3>

                @if (session('resent'))
                    <div class="alert alert-success border-0 small mb-4" role="alert" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                        <i class="fas fa-check-circle me-1"></i> {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                <p class="text-muted small mb-4">
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email, click the button below to request another.') }}
                </p>

                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn-edu-primary btn-sm px-4">
                        <i class="fas fa-paper-plane me-1"></i> {{ __('Resend Verification Email') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
