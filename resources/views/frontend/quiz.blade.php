@extends('frontend.master')

@section('title', 'Academic Assessment: ' . $quiz->title . ' – Digital Academy')

@section('styles')
<style>
    .quiz-option-card {
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        color: var(--text-main);
        border-radius: var(--radius-md);
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .quiz-option-card:hover {
        border-color: var(--primary);
        background: var(--primary-light);
        transform: translateY(-2px);
    }
    .quiz-option-card.selected {
        border-color: var(--primary);
        background: var(--primary-light);
    }
    .quiz-option-card.correct-highlight {
        border-color: var(--accent-emerald) !important;
        background: rgba(16, 185, 129, 0.15) !important;
    }
    .quiz-option-card.wrong-highlight {
        border-color: var(--accent-rose) !important;
        background: rgba(244, 63, 94, 0.15) !important;
    }
</style>
@endsection

@section('content')
<div class="container py-4 py-md-5">

    <!-- Top Assessment Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom" style="border-color: var(--border-color) !important;">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('courses.detail', $course->id) }}" class="btn btn-edu-secondary btn-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Back to Course
                </a>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-2 py-1 rounded-pill small fw-semibold">
                    🎓 Academic Certification Exam
                </span>
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 small">Passing Score: {{ $quiz->pass_percentage }}%</span>
            </div>
            <h2 class="fw-bold mb-1 fs-4 fs-md-3">{{ $quiz->title }}</h2>
            <p class="text-muted small mb-0">{{ $course->name }} • {{ $quiz->questions->count() }} Questions</p>
        </div>

        <!-- Timer & Attempts -->
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 px-3 rounded-pill edu-card-panel d-flex align-items-center gap-2 border border-warning border-opacity-25">
                <i class="far fa-clock text-warning"></i>
                <span class="fw-bold small" id="examTimer">15:00</span>
            </div>

            @if($bestAttempt)
                <div class="p-2 px-3 rounded-pill edu-card-panel small">
                    <span class="text-muted">Best Score:</span>
                    <strong class="{{ $bestAttempt->passed ? 'text-success' : 'text-warning' }}">{{ $bestAttempt->percentage }}%</strong>
                </div>
            @endif
        </div>
    </div>

    <!-- Exam Form -->
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            <!-- Result Alert Banner (Hidden initially) -->
            <div id="quizResultBanner" class="alert d-none mb-4 p-4 shadow-sm" role="alert" style="border-radius: var(--radius-md);">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div id="resultIcon" class="fs-2"></div>
                    <div>
                        <h4 class="fw-bold mb-1" id="resultTitle"></h4>
                        <p class="mb-0 small" id="resultSubtitle"></p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-secondary border-opacity-15 d-flex gap-2" id="resultActionArea"></div>
            </div>

            <form id="quizForm" onsubmit="submitAcademicQuiz(event)">
                @csrf

                @foreach($quiz->questions as $qIndex => $question)
                    <div class="edu-card-panel p-4 mb-4 shadow-sm" id="questionCard_{{ $question->id }}">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-secondary bg-opacity-20 text-primary fw-bold">
                                Question {{ $qIndex + 1 }} of {{ $quiz->questions->count() }}
                            </span>
                            <span class="text-muted small">Single Choice</span>
                        </div>

                        <h5 class="fw-bold mb-4 fs-6 fs-md-5">{{ $question->question }}</h5>

                        <div class="d-flex flex-column gap-3 mb-3">
                            @foreach($question->options as $optIndex => $optText)
                                <label class="quiz-option-card" id="optionLabel_{{ $question->id }}_{{ $optIndex }}" onclick="selectOption({{ $question->id }}, {{ $optIndex }})">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $optIndex }}" class="form-check-input mt-0" style="display: none;" required>
                                    <span class="badge bg-secondary bg-opacity-25 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 26px; height: 26px;">
                                        {{ chr(65 + $optIndex) }}
                                    </span>
                                    <span class="fw-medium small flex-grow-1">{{ $optText }}</span>
                                    <i class="far fa-circle text-muted" id="radioCheck_{{ $question->id }}_{{ $optIndex }}"></i>
                                </label>
                            @endforeach
                        </div>

                        <!-- Explanation Box (Appears after submission) -->
                        <div class="p-3 rounded edu-card-panel d-none mt-3" id="explanation_{{ $question->id }}" style="background: var(--bg-secondary);">
                            <div class="fw-bold text-info small mb-1"><i class="fas fa-lightbulb me-1"></i> Explanation:</div>
                            <p class="text-muted small mb-0">{{ $question->explanation ?? 'Understanding this core concept is critical for verified certification mastery.' }}</p>
                        </div>
                    </div>
                @endforeach

                <!-- Submit Bar -->
                <div class="edu-card-panel p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-3 shadow-sm">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle text-primary me-1"></i> Make sure all questions are answered before submitting.
                    </div>
                    <button type="submit" class="btn-edu-primary px-5 py-3 fw-bold" id="btnSubmitQuiz">
                        <i class="fas fa-paper-plane me-2"></i> Submit Exam for Grading
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Option selection visual toggle
    function selectOption(qId, optIdx) {
        if (window.Dopamine) {
            window.Dopamine.playPop();
        }
        const labels = document.querySelectorAll(`[id^="optionLabel_${qId}_"]`);
        labels.forEach(l => {
            l.classList.remove('selected');
            const icon = l.querySelector('i');
            if (icon) {
                icon.className = 'far fa-circle text-muted';
            }
        });

        const activeLabel = document.getElementById(`optionLabel_${qId}_${optIdx}`);
        if (activeLabel) {
            activeLabel.classList.add('selected');
            const radio = activeLabel.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
            const icon = activeLabel.querySelector('i');
            if (icon) {
                icon.className = 'fas fa-check-circle text-primary';
            }
        }
    }

    // 15-Minute Countdown Timer
    let timeLeft = {{ $quiz->time_limit_minutes * 60 }};
    const timerEl = document.getElementById('examTimer');
    const timerInterval = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            timerEl.textContent = '00:00';
            alert('Time is up! Submitting your answers now.');
            document.getElementById('quizForm').dispatchEvent(new Event('submit'));
            return;
        }
        timeLeft--;
        const mins = String(Math.floor(timeLeft / 60)).padStart(2, '0');
        const secs = String(timeLeft % 60).padStart(2, '0');
        timerEl.textContent = `${mins}:${secs}`;
    }, 1000);

    // Form Submission & Instant AJAX Grading
    async function submitAcademicQuiz(e) {
        e.preventDefault();
        clearInterval(timerInterval);

        const form = document.getElementById('quizForm');
        const formData = new FormData(form);
        const submitBtn = document.getElementById('btnSubmitQuiz');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Grading Exam Server-Side...';

        const answers = {};
        for (let [key, val] of formData.entries()) {
            const match = key.match(/answers\[(\d+)\]/);
            if (match) {
                answers[match[1]] = val;
            }
        }

        try {
            const res = await fetch("{{ route('courses.quiz.submit', ['id' => $course->id, 'quizId' => $quiz->id]) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ answers: answers })
            });

            const data = await res.json();
            if (data.status === 'success') {
                renderExamResults(data);
            }
        } catch (err) {
            alert('Grading error: ' + err.message);
            submitBtn.disabled = false;
        }
    }

    function renderExamResults(data) {
        window.scrollTo({ top: 0, behavior: 'smooth' });

        const banner = document.getElementById('quizResultBanner');
        const icon = document.getElementById('resultIcon');
        const title = document.getElementById('resultTitle');
        const sub = document.getElementById('resultSubtitle');
        const actions = document.getElementById('resultActionArea');

        banner.classList.remove('d-none');

        if (data.passed) {
            if (window.Dopamine) {
                window.Dopamine.playFanfare();
                window.Dopamine.fireContinuousCelebration();
                window.Dopamine.showXpGainToast(data.xp_earned || 150, 'Academic Certification Exam Passed! 🏆');
            }
            banner.className = 'alert alert-success mb-4 p-4 shadow-sm border-0';
            banner.style.background = 'rgba(16, 185, 129, 0.15)';
            icon.innerHTML = '<i class="fas fa-award text-success"></i>';
            title.textContent = `🎉 Congratulations! You Passed (${data.percentage}%)`;
            sub.textContent = `You scored ${data.score} out of ${data.total} questions. Your Verified Digital Completion Certificate has been officially unlocked!`;
            actions.innerHTML = `
                <a href="${data.certificate_url}" class="btn-edu-primary px-4 py-2 fw-bold">
                    <i class="fas fa-certificate me-2"></i> Claim & Download Certificate
                </a>
                <a href="{{ route('student.dashboard') }}" class="btn-edu-secondary px-4 py-2">
                    Back to Student Dashboard
                </a>
            `;
        } else {
            if (window.Dopamine) {
                window.Dopamine.playGentleFailure();
            }
            banner.className = 'alert alert-danger mb-4 p-4 shadow-sm border-0';
            banner.style.background = 'rgba(244, 63, 94, 0.15)';
            icon.innerHTML = '<i class="fas fa-times-circle text-danger"></i>';
            title.textContent = `Score: ${data.percentage}% (Passing Grade: ${data.pass_percentage}%)`;
            sub.textContent = `You scored ${data.score} out of ${data.total} questions. Review the question explanations below and retake the exam to earn your certificate.`;
            actions.innerHTML = `
                <button type="button" class="btn-edu-primary px-4 py-2 fw-bold" onclick="window.location.reload()">
                    <i class="fas fa-redo me-2"></i> Retake Exam
                </button>
                <a href="{{ route('courses.detail', $course->id) }}" class="btn-edu-secondary px-4 py-2">
                    Review Lecture Lessons
                </a>
            `;
        }

        // Highlight correct and wrong answers on the cards
        data.breakdown.forEach(item => {
            const expBox = document.getElementById(`explanation_${item.question_id}`);
            if (expBox) expBox.classList.remove('d-none');

            // Highlight correct option in emerald
            const correctLabel = document.getElementById(`optionLabel_${item.question_id}_${item.correct_option}`);
            if (correctLabel) {
                correctLabel.classList.add('correct-highlight');
                const i = correctLabel.querySelector('i');
                if (i) i.className = 'fas fa-check-circle text-success';
            }

            // If student answered wrongly, highlight wrong option in rose
            if (!item.is_correct && item.student_choice !== -1) {
                const wrongLabel = document.getElementById(`optionLabel_${item.question_id}_${item.student_choice}`);
                if (wrongLabel) {
                    wrongLabel.classList.add('wrong-highlight');
                    const i = wrongLabel.querySelector('i');
                    if (i) i.className = 'fas fa-times-circle text-danger';
                }
            }
        });

        // Hide submit button
        document.getElementById('btnSubmitQuiz').style.display = 'none';
    }
</script>
@endsection
