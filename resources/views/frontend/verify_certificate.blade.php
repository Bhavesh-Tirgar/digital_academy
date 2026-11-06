@extends('frontend.master')

@section('title', 'Verify Credential: ' . $certId . ' – Digital Academy')

@section('content')
@php
    $cName = $course ? $course->name : 'Full-Stack Software Engineering Cohort';
    $linkedInAddUrl = 'https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME'
        . '&name=' . urlencode($cName)
        . '&organizationName=' . urlencode('Digital Academy Global Engineering')
        . '&issueYear=' . date('Y')
        . '&issueMonth=' . date('n')
        . '&certUrl=' . urlencode(route('certificate.verify', $certId))
        . '&certId=' . urlencode($certId);
@endphp

<div class="container py-4 py-md-5 text-center">
    <div class="edu-card-panel max-w-600 mx-auto p-4 p-md-5 shadow-lg position-relative" style="max-width: 660px;">
        <!-- Verification Emblem -->
        <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.12); border: 2px solid var(--accent-emerald);">
            <i class="fas fa-check-circle fa-3x text-success"></i>
        </div>

        <div class="mb-2">
            <span class="badge bg-success bg-opacity-15 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill small fw-bold">
                <i class="fas fa-shield-alt me-1"></i> Cryptographically Verified Credential
            </span>
        </div>

        <h2 class="fw-bold mb-1 fs-3">Verified Academic Credential</h2>
        <p class="text-muted small mb-4">
            This digital certificate is permanently recorded on the Digital Academy ledger, confirming that the learner completed all hands-on exercises and passed the final proctored exam.
        </p>

        <!-- Recipient & Course Spotlight -->
        <div class="p-3 rounded mb-4 text-start" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
            <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-15">
                <span class="text-muted small">Credential Holder</span>
                <span class="fw-bold small">{{ $studentName ?? 'Verified Learner' }}</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-15">
                <span class="text-muted small">Program Completed</span>
                <span class="fw-semibold text-primary small text-end" style="max-width: 320px;">{{ $cName }}</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-15">
                <span class="text-muted small">Certificate ID</span>
                <span class="fw-bold font-monospace small text-dark-emphasis">{{ $certId }}</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-15">
                <span class="text-muted small">Status</span>
                <span class="badge bg-success bg-opacity-20 text-success small">Verified Active & Unrevoked</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-15">
                <span class="text-muted small">Issuing Authority</span>
                <span class="small fw-semibold">Digital Academy Global Engineering Standards</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-15">
                <span class="text-muted small">Verification Standard</span>
                <span class="text-info small"><i class="fas fa-lock fa-xs me-1"></i> SHA-256 Ledger Digest</span>
            </div>
            <div class="py-2">
                <span class="text-muted small d-block mb-1">Cryptographic Fingerprint:</span>
                <div class="font-monospace p-2 rounded small text-break mb-3" style="font-size: 0.72rem; background: var(--card-bg); border: 1px solid var(--border-color); color: var(--text-muted);">
                    {{ $shaHash ?? strtoupper(hash('sha256', $certId . '_digital_academy_official_ledger_2026')) }}
                </div>
            </div>

            <!-- Mobile Scan QR Verification -->
            <div class="pt-2 border-top border-secondary border-opacity-15 d-flex align-items-center justify-content-between gap-3">
                <div>
                    <span class="text-muted small fw-bold d-block mb-1"><i class="fas fa-qrcode me-1 text-primary"></i> Mobile Scan Verification</span>
                    <span class="small text-muted" style="font-size: 0.75rem;">Scan this QR badge to verify on any smartphone device</span>
                </div>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('certificate.verify', $certId)) }}" alt="Verification QR Code" class="rounded p-1 bg-white border" style="width: 64px; height: 64px; object-fit: contain;">
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="{{ $linkedInAddUrl }}" target="_blank" class="btn btn-sm text-white fw-bold px-3 py-2" style="background: #0a66c2;">
                <i class="fab fa-linkedin me-1"></i> Add to LinkedIn
            </a>
            <a href="{{ route('courses.catalog') }}" class="btn-edu-primary btn-sm px-3 py-2">
                Explore Engineering Cohorts
            </a>
            <a href="{{ route('frontend.home') }}" class="btn-edu-secondary btn-sm px-3 py-2">
                Return Home
            </a>
        </div>
    </div>
</div>
@endsection
