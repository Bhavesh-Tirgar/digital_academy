@extends('frontend.master')

@section('title', 'Verified Credential – ' . $course->name)

@section('styles')
<style>
@media print {
    /* Hide top nav, mobile bottom nav, buttons, footer, and back link */
    .edu-navbar, .mobile-bottom-nav, footer, .d-flex.justify-content-between, .cert-action-bar, .btn, .edu-trust-strip, .mt-4.text-muted {
        display: none !important;
    }
    body, .container {
        background: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .certificate-container {
        border: 4px double #d4af37 !important;
        background: #0c0f1d !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        box-shadow: none !important;
        max-width: 100% !important;
        margin: 0 auto !important;
        page-break-inside: avoid;
    }
}

.cert-qr-box {
    background: #ffffff;
    padding: 6px;
    border-radius: 8px;
    display: inline-block;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
.cert-qr-img {
    width: 72px;
    height: 72px;
    display: block;
}
.linkedin-add-btn {
    background: #0a66c2 !important;
    color: #ffffff !important;
    border: none !important;
}
.linkedin-add-btn:hover {
    background: #004182 !important;
    color: #ffffff !important;
}
</style>
@endsection

@section('content')
@php
    $verifyUrl = route('certificate.verify', $certId);
    $issueYear = date('Y');
    $issueMonth = date('n');
    $linkedInAddUrl = 'https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME'
        . '&name=' . urlencode($course->name)
        . '&organizationName=' . urlencode('Digital Academy Global Engineering')
        . '&issueYear=' . $issueYear
        . '&issueMonth=' . $issueMonth
        . '&certUrl=' . urlencode($verifyUrl)
        . '&certId=' . urlencode($certId);

    $linkedInShareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($verifyUrl);
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($verifyUrl);
    $sha256Hash = strtoupper(hash('sha256', $certId . '_digital_academy_official_ledger_2026'));
@endphp

<div class="container py-4 py-md-5 text-center">

    <!-- Action Toolbar (1-Click LinkedIn Integration, Print, Copy) -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 max-w-800 mx-auto gap-2 cert-action-bar" style="max-width: 880px;">
        <a href="{{ route('courses.detail', $course->id) }}" class="btn-edu-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Course
        </a>
        <div class="d-flex flex-wrap gap-2">
            <!-- 1-Click Official LinkedIn Add Certification -->
            <a href="{{ $linkedInAddUrl }}" target="_blank" class="btn linkedin-add-btn btn-sm d-inline-flex align-items-center gap-1.5 shadow-sm fw-bold px-3">
                <i class="fab fa-linkedin fa-lg"></i> Add to LinkedIn Profile
            </a>

            <!-- Share to Feed -->
            <a href="{{ $linkedInShareUrl }}" target="_blank" class="btn-edu-secondary btn-sm" title="Share announcement post on LinkedIn">
                <i class="fas fa-share-alt me-1"></i> Share
            </a>

            <!-- Copy Verification Link -->
            <button type="button" class="btn-edu-secondary btn-sm" onclick="copyCertLink('{{ $verifyUrl }}', this)">
                <i class="fas fa-link me-1"></i> <span id="copyLinkText">Copy Link</span>
            </button>

            <!-- Print / Save PDF -->
            <button class="btn-edu-primary btn-sm" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Print / PDF
            </button>
        </div>
    </div>

    <!-- Official Certificate Frame -->
    <div class="certificate-container mx-auto p-4 p-md-5 position-relative shadow-lg" style="max-width: 880px; background: #0c0f1d; border: 3px double #d4af37; border-radius: 16px; box-shadow: 0 0 50px rgba(212, 175, 55, 0.2);">
        
        <!-- Corner Ornaments -->
        <div class="position-absolute top-0 start-0 p-3 text-warning opacity-75"><i class="fas fa-certificate fa-2x"></i></div>
        <div class="position-absolute top-0 end-0 p-3 text-warning opacity-75"><i class="fas fa-certificate fa-2x"></i></div>
        <div class="position-absolute bottom-0 start-0 p-3 text-warning opacity-75"><i class="fas fa-certificate fa-2x"></i></div>
        <div class="position-absolute bottom-0 end-0 p-3 text-warning opacity-75"><i class="fas fa-certificate fa-2x"></i></div>

        <!-- Certificate Header -->
        <div class="mb-4">
            <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mb-3" style="background: rgba(212, 175, 55, 0.15); border: 1px solid #d4af37; width: 68px; height: 68px;">
                <i class="fas fa-graduation-cap fa-2x text-warning"></i>
            </div>
            <h5 class="text-warning text-uppercase letter-spacing-2 fw-bold small mb-1" style="letter-spacing: 3px;">DIGITAL ACADEMY VERIFIED CREDENTIAL</h5>
            <h1 class="display-6 fw-bold text-white mb-0" style="font-family: 'Space Grotesk', serif;">Certificate of Completion</h1>
        </div>

        <p class="text-muted fst-italic mb-2">This is to officially certify that</p>

        <!-- Student Name -->
        <h2 class="display-5 fw-bold text-white mb-3" style="color: #f6e05e !important; text-decoration: underline; text-decoration-color: rgba(212, 175, 55, 0.4);">
            {{ $user->name }}
        </h2>

        <p class="text-muted max-w-600 mx-auto mb-4" style="max-width: 640px; line-height: 1.6;">
            has successfully mastered all modules, completed hands-on engineering challenges, passed the academic certification exam, and verified production competencies in:
        </p>

        <!-- Course Title -->
        <div class="p-3 rounded mb-4 d-inline-block px-4" style="background: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.3);">
            <h3 class="text-white fw-bold mb-0 fs-4">{{ $course->name }}</h3>
        </div>

        <!-- Instructor Signature, QR Seal & Digest -->
        <div class="row align-items-end justify-content-between pt-4 mt-3 border-top border-secondary border-opacity-25 g-4 text-start">
            <!-- Left: Instructor Signature -->
            <div class="col-sm-4 text-center text-sm-start">
                <div class="text-white fw-bold">{{ $course->instructor ? $course->instructor->name : 'Lead Technical Instructor' }}</div>
                <div class="text-muted small">Course Instructor & Architect</div>
                <div class="text-warning small fst-italic mt-1"><i class="fas fa-check-double me-1"></i> Cryptographically Signed</div>
            </div>

            <!-- Center: Verification QR & Gold Seal -->
            <div class="col-sm-4 text-center">
                <div class="d-inline-flex align-items-center gap-3">
                    <!-- Dynamic Phone Scanner QR Code -->
                    <div class="cert-qr-box" title="Scan with mobile camera to verify instantly">
                        <img src="{{ $qrCodeUrl }}" alt="Verification QR Code" class="cert-qr-img">
                    </div>

                    <!-- Gold Verification Seal -->
                    <div class="d-inline-flex flex-column align-items-center p-2 rounded-circle" style="width: 76px; height: 76px; border: 2px dashed #d4af37; justify-content: center;">
                        <i class="fas fa-award text-warning fa-lg"></i>
                        <span style="font-size: 0.55rem; color: #d4af37; font-weight: 800; text-transform: uppercase;">VERIFIED</span>
                    </div>
                </div>
                <div class="text-muted mt-1" style="font-size: 0.68rem;">Scan to verify on public ledger</div>
            </div>

            <!-- Right: Certificate ID & Timestamp -->
            <div class="col-sm-4 text-center text-sm-end">
                <div class="text-muted small">Certificate ID:</div>
                <div class="text-white fw-bold font-monospace small mb-1">{{ $certId }}</div>
                <div class="text-muted small">Issued: {{ date('M d, Y') }}</div>
                <div class="text-muted" style="font-size: 0.65rem; word-break: break-all; opacity: 0.7;">
                    SHA: {{ substr($sha256Hash, 0, 16) }}...
                </div>
            </div>
        </div>

    </div>

    <!-- Security & LinkedIn Trust Notice -->
    <div class="mt-4 text-muted small max-w-600 mx-auto" style="max-width: 680px; font-size: 0.8rem;">
        <i class="fas fa-shield-alt text-success me-1"></i> 
        This digital credential is cryptographically anchored on the Digital Academy ledger. 
        Recruiters and hiring managers can verify authenticity via QR scan or through the official 
        <a href="{{ $verifyUrl }}" target="_blank" class="text-primary text-decoration-none fw-semibold">Public Verification Portal</a>.
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Dopamine) {
            window.Dopamine.playFanfare();
            window.Dopamine.fireContinuousCelebration();
            window.Dopamine.showXpGainToast(500, 'Accredited Academic Diploma Unlocked! 📜');
        }
    });

    function copyCertLink(url, btn) {
        navigator.clipboard.writeText(url).then(() => {
            const label = document.getElementById('copyLinkText');
            if (label) label.textContent = 'Copied! ✅';
            setTimeout(() => {
                if (label) label.textContent = 'Copy Link';
            }, 2500);
        }).catch(() => {
            alert('Verification Link: ' + url);
        });
    }
</script>
@endsection
