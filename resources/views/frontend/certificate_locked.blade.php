@extends('frontend.master')

@section('title', '🔒 Certificate Locked: ' . $course->name . ' – Digital Academy')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <div class="edu-card-panel p-4 p-md-5 text-center shadow-lg position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-25 px-3 py-1 rounded-pill small fw-bold">
                        Academic Rigor Policy
                    </span>
                </div>

                <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-4 mb-3" style="background: rgba(245, 158, 11, 0.12); color: var(--accent-amber); width: 80px; height: 80px;">
                    <i class="fas fa-lock fa-2x"></i>
                </div>

                <h2 class="fw-bold mb-2">Verified Certificate Locked</h2>
                <p class="text-muted small max-w-500 mx-auto mb-4" style="max-width: 480px;">
                    Digital Academy completion certificates are strictly verified credentials. To protect the academic reputation of your credential, two criteria must be satisfied:
                </p>

                <!-- Academic Requirement Criteria Cards -->
                <div class="d-flex flex-column gap-3 mb-4 text-start">
                    <!-- Criterion 1: Lesson Progress -->
                    <div class="p-3 rounded edu-card-panel d-flex align-items-center justify-content-between" style="background: var(--bg-secondary);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-4 {{ $progress['percent'] >= 100 ? 'text-success' : 'text-warning' }}">
                                <i class="fas {{ $progress['percent'] >= 100 ? 'fa-check-circle' : 'fa-hourglass-half' }}"></i>
                            </div>
                            <div>
                                <div class="fw-bold small">1. Complete 100% of Course Curriculum</div>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    {{ $progress['completed'] }} of {{ $progress['total'] }} lessons finished ({{ $progress['percent'] }}%)
                                </div>
                            </div>
                        </div>
                        <span class="badge {{ $progress['percent'] >= 100 ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2 rounded-pill small">
                            {{ $progress['percent'] >= 100 ? 'Completed ✅' : 'In Progress (' . $progress['percent'] . '%)' }}
                        </span>
                    </div>

                    <!-- Criterion 2: Academic Exam Score >= 80% -->
                    <div class="p-3 rounded edu-card-panel d-flex align-items-center justify-content-between" style="background: var(--bg-secondary);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-4 {{ $passedExam ? 'text-success' : 'text-danger' }}">
                                <i class="fas {{ $passedExam ? 'fa-graduation-cap' : 'fa-file-signature' }}"></i>
                            </div>
                            <div>
                                <div class="fw-bold small">2. Pass Final Certification Exam (&ge; 80%)</div>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    @if($lastAttempt)
                                        Last score: {{ $lastAttempt->percentage }}% ({{ $lastAttempt->score }}/{{ $lastAttempt->total_questions }}) – {{ $lastAttempt->passed ? 'PASSED' : 'NEEDS 80% TO PASS' }}
                                    @else
                                        Official multiple-choice timed assessment not yet taken
                                    @endif
                                </div>
                            </div>
                        </div>
                        <span class="badge {{ $passedExam ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill small">
                            {{ $passedExam ? 'Exam Passed ✅' : 'Required ❌' }}
                        </span>
                    </div>
                </div>

                <!-- Action CTAs -->
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @if($finalQuiz)
                        <a href="{{ route('courses.quiz.show', ['id' => $course->id, 'quizId' => $finalQuiz->id]) }}" class="btn-edu-primary px-4 py-3 fw-bold">
                            <i class="fas fa-file-signature me-2"></i> Take Certification Exam Now
                        </a>
                    @endif

                    <a href="{{ route('frontend.materials', ['id' => $course->id]) }}" class="btn-edu-secondary px-4 py-3">
                        <i class="fas fa-book-open me-2"></i> Review Course Lessons
                    </a>
                </div>

                <div class="mt-4 text-muted small" style="font-size: 0.75rem;">
                    <i class="fas fa-shield-alt text-success me-1"></i> Cryptographically signed with unique Certificate ID & QR Verification upon passing.
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
