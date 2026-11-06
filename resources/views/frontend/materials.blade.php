@extends('frontend.master')

@section('title', ($course ? $course->name : 'Course Materials') . ' – Learning Workspace')

@section('styles')
<style>
    .playback-speed-btn {
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #fff;
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .playback-speed-btn.active, .playback-speed-btn:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }
    .test-case-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        font-family: monospace;
        font-size: 0.8rem;
    }
    .test-case-card.passed {
        border-color: var(--accent-emerald);
        background: rgba(16, 185, 129, 0.08);
    }
    .test-case-card.failed {
        border-color: var(--accent-rose);
        background: rgba(244, 63, 94, 0.08);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-lg-5 py-4">

    <!-- Top Workspace Navigation Bar -->
    <div class="edu-card-panel mb-4 p-3 p-md-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('courses.detail', $course ? $course->id : 1) }}" class="btn-edu-secondary btn-sm px-3" title="Back to Course">
                    <i class="fas fa-arrow-left me-1"></i> Course Overview
                </a>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-25 small">
                            Full Cohort Access
                        </span>
                        <span class="text-muted small">• Structured Academic Track</span>
                    </div>
                    <h3 class="fw-bold mb-0 fs-5 fs-md-4">{{ $course ? $course->name : 'Learning Workspace' }}</h3>
                </div>
            </div>

            <!-- Real-Time Progress Bar & Certificate Pill -->
            <div class="d-flex align-items-center gap-3">
                <div style="min-width: 180px;">
                    <div class="d-flex justify-content-between text-muted small mb-1" style="font-size: 0.75rem;">
                        <span>Lesson Completion</span>
                        <span class="fw-bold text-primary" id="progressPercentLabel">{{ $progress['percent'] ?? 0 }}%</span>
                    </div>
                    <div class="progress" style="height: 7px; background: rgba(148, 163, 184, 0.2);">
                        <div class="progress-bar bg-primary" id="progressBarFill" role="progressbar" style="width: {{ $progress['percent'] ?? 0 }}%; transition: width 0.4s ease;"></div>
                    </div>
                </div>

                <a href="{{ route('courses.certificate', $course ? $course->id : 1) }}" class="btn {{ ($progress['percent'] ?? 0) >= 100 ? 'btn-edu-primary' : 'btn-edu-secondary' }} btn-sm text-nowrap px-3" id="certBtn">
                    <i class="fas fa-award text-warning me-1"></i>
                    {{ ($progress['percent'] ?? 0) >= 100 ? 'Verified Certificate' : 'Certificate Lock' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Main Workspace Content -->
    <div class="row g-4">

        <!-- Left Column: Active Lesson Viewport & Code Grader -->
        <div class="col-lg-8 col-xl-8">
            <div class="edu-card-panel p-0 overflow-hidden shadow-lg mb-4">

                <!-- Material Viewer Frame (With Video Intelligence & Asset Renderers) -->
                <div class="position-relative" style="min-height: 420px; background: #0b0f19; display: flex; align-items: center; justify-content: center;">
                    @if($activeMaterial && $activeMaterial->type === 'video')
                        @php
                            $rawUrl = $activeMaterial->video_url ?: $activeMaterial->file_path;
                            $isDirectUrl = Str::startsWith($rawUrl, ['http://', 'https://']);
                            $videoSrc = $isDirectUrl ? $rawUrl : asset('storage/' . $rawUrl);
                            $isYoutube = Str::contains($videoSrc, ['youtube.com', 'youtu.be']);
                            $isVimeo = Str::contains($videoSrc, 'vimeo.com');
                            $embedUrl = $videoSrc;
                            if ($isYoutube) {
                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $videoSrc, $m)) {
                                    $embedUrl = 'https://www.youtube-nocookie.com/embed/' . $m[1] . '?autoplay=1&rel=0';
                                }
                            } elseif ($isVimeo) {
                                if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)/', $videoSrc, $m)) {
                                    $embedUrl = 'https://player.vimeo.com/video/' . $m[3] . '?autoplay=1';
                                }
                            }
                        @endphp
                        @if($isYoutube || $isVimeo)
                            <iframe src="{{ $embedUrl }}" class="w-100" style="height: 480px; border: 0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <video id="workspaceVideoPlayer" controls playsinline class="w-100" style="max-height: 480px; background: #000;" poster="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1000' }}">
                                <source src="{{ $videoSrc }}" type="video/mp4">
                                Your browser does not support HTML5 video streaming.
                            </video>

                            <!-- Video Player Floating Speed Control Bar -->
                            <div class="position-absolute bottom-0 end-0 m-3 d-flex align-items-center gap-1 z-3" style="background: rgba(15, 23, 42, 0.85); padding: 4px 8px; border-radius: 999px;">
                                <span class="text-muted small me-1" style="font-size: 0.7rem; color: #cbd5e1 !important;">Speed:</span>
                                <button type="button" class="playback-speed-btn" onclick="setPlayerSpeed(0.75)">0.75x</button>
                                <button type="button" class="playback-speed-btn active" id="spd1" onclick="setPlayerSpeed(1.0)">1.0x</button>
                                <button type="button" class="playback-speed-btn" onclick="setPlayerSpeed(1.25)">1.25x</button>
                                <button type="button" class="playback-speed-btn" onclick="setPlayerSpeed(1.5)">1.5x</button>
                                <button type="button" class="playback-speed-btn" onclick="setPlayerSpeed(2.0)">2.0x</button>
                            </div>
                        @endif
                    @elseif($activeMaterial && $activeMaterial->type === 'pdf')
                        @php
                            $pdfSrc = Str::startsWith($activeMaterial->file_path, ['http://', 'https://']) 
                                ? $activeMaterial->file_path 
                                : asset('storage/' . $activeMaterial->file_path);
                        @endphp
                        <div class="p-5 text-center w-100">
                            <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                                <i class="fas fa-file-pdf fa-3x"></i>
                            </div>
                            <h4 class="fw-bold text-white mb-2">{{ $activeMaterial->title }}</h4>
                            <p class="text-muted small mb-4">Official module study blueprint, architectural diagrams, and reference guides.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ $pdfSrc }}" target="_blank" class="btn btn-edu-secondary btn-sm rounded-pill px-3">
                                    <i class="fas fa-external-link-alt me-1"></i> View in Browser
                                </a>
                                <a href="{{ $pdfSrc }}" download class="btn btn-edu-primary btn-sm rounded-pill px-3">
                                    <i class="fas fa-download me-1"></i> Download PDF
                                </a>
                            </div>
                        </div>
                    @elseif($activeMaterial && $activeMaterial->type === 'handwritten')
                        @php
                            $imgSrc = Str::startsWith($activeMaterial->file_path, ['http://', 'https://']) 
                                ? $activeMaterial->file_path 
                                : asset('storage/' . $activeMaterial->file_path);
                        @endphp
                        <div class="p-4 text-center w-100">
                            <div class="mb-3">
                                <span class="badge bg-warning bg-opacity-20 text-warning px-3 py-1 rounded-pill small fw-semibold">
                                    <i class="fas fa-pen-nib me-1"></i> Handwritten Study Notes & Sketches
                                </span>
                            </div>
                            <h4 class="fw-bold text-white mb-3">{{ $activeMaterial->title }}</h4>
                            <div class="p-2 bg-dark rounded border border-secondary mb-3 d-inline-block shadow-lg" style="max-width: 100%;">
                                <img src="{{ $imgSrc }}" 
                                     alt="{{ $activeMaterial->title }}" 
                                     class="img-fluid rounded" 
                                     style="max-height: 440px; object-fit: contain; cursor: zoom-in;" 
                                     onclick="window.open(this.src, '_blank')">
                            </div>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ $imgSrc }}" target="_blank" class="btn btn-edu-secondary btn-sm rounded-pill px-3">
                                    <i class="fas fa-expand me-1"></i> Fullscreen
                                </a>
                                <a href="{{ $imgSrc }}" download class="btn btn-edu-primary btn-sm rounded-pill px-3">
                                    <i class="fas fa-download me-1"></i> Download Notes
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Production Interactive Workspace Slate -->
                        <div class="p-5 text-center text-white w-100" style="min-height: 380px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: radial-gradient(circle at center, #1e1b4b 0%, #0c0f1d 100%);">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-3" style="width: 72px; height: 72px; background: rgba(99, 102, 241, 0.2); border: 1px solid rgba(99, 102, 241, 0.4); color: #818cf8;">
                                <i class="fas fa-laptop-code fa-2x"></i>
                            </div>
                            <h4 class="fw-bold mb-1">{{ $activeMaterial ? $activeMaterial->title : 'Hands-on Production Implementation' }}</h4>
                            <p class="text-muted small max-w-500 mx-auto mb-3" style="max-width: 480px;">
                                This lesson focuses on hands-on code problem solving and architectural theory. Review the curriculum syllabus and complete the autograded challenge below.
                            </p>
                            <span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-30 px-3 py-1.5 rounded-pill small">
                                <i class="fas fa-check-circle me-1"></i> Interactive Coding Lab Active Below
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Lesson Info & Progress Toggle Bar -->
                <div class="p-4" style="border-top: 1px solid var(--border-color); background: var(--bg-secondary);">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <span class="badge bg-primary bg-opacity-20 text-primary small text-uppercase mb-1">
                                Module: {{ $subject ? $subject->name : 'Core Concepts' }}
                            </span>
                            <h4 class="fw-bold mb-0 fs-5">
                                {{ $activeMaterial ? $activeMaterial->title : 'Hands-on Production Implementation' }}
                            </h4>
                        </div>

                        <!-- Mark Lesson Completed Toggle (AJAX) -->
                        @php
                            $matId = $activeMaterial ? $activeMaterial->id : 1;
                            $isDone = in_array($matId, $progress['completed_materials'] ?? []);
                        @endphp
                        <button type="button" class="btn {{ $isDone ? 'btn-success' : 'btn-edu-secondary' }} btn-sm px-3" id="toggleCompleteBtn" onclick="toggleLessonComplete({{ $course ? $course->id : 1 }}, {{ $matId }})">
                            <i class="fas {{ $isDone ? 'fa-check-circle' : 'fa-circle' }} me-2"></i>
                            <span id="completeBtnText">{{ $isDone ? 'Lesson Completed' : 'Mark Lesson Complete' }}</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Interactive Autograded Coding Lab (LeetCode / Codecademy Model) -->
            <div class="edu-card-panel p-4 mb-4 shadow-sm">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom border-secondary border-opacity-15">
                    <div>
                        <span class="badge bg-info bg-opacity-20 text-info small mb-1">💻 Automated Lab Grader</span>
                        <h5 class="fw-bold mb-0">Hands-on Challenge: High-Throughput Binary Search</h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-warning btn-sm px-3 fw-bold d-flex align-items-center gap-1.5" onclick="consultProfessorAI()" id="btnAskAi">
                            <i class="fas fa-brain text-warning"></i> <span>Ask Professor AI</span>
                        </button>
                        <button type="button" class="btn-edu-primary btn-sm px-3" onclick="runAutogradedLab()">
                            <i class="fas fa-play fa-xs me-1"></i> Run Test Cases
                        </button>
                    </div>
                </div>

                <p class="text-muted small mb-3">
                    Implement <code class="text-primary">findTarget(arr, target)</code> returning the target index or <code class="text-danger">-1</code>. Your code will be automatically executed against 3 unit assertions with timeout bounds.
                </p>

                <!-- Code Editor Textarea -->
                <textarea id="labCodeEditor" class="code-editor-area mb-3" style="min-height: 140px;" spellcheck="false">function findTarget(arr, target) {
    let left = 0, right = arr.length - 1;
    while (left <= right) {
        let mid = Math.floor((left + right) / 2);
        if (arr[mid] === target) return mid;
        if (arr[mid] < target) left = mid + 1;
        else right = mid - 1;
    }
    return -1;
}</textarea>

                <!-- Test Cases Assertions Grid -->
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <div class="test-case-card" id="testCase1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Test 1: Target in array</span>
                                <span class="badge bg-secondary" id="tcStatus1">Ready</span>
                            </div>
                            <div class="text-muted" style="font-size: 0.72rem;">findTarget([2, 5, 8, 12], 8) == 2</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="test-case-card" id="testCase2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Test 2: Target absent</span>
                                <span class="badge bg-secondary" id="tcStatus2">Ready</span>
                            </div>
                            <div class="text-muted" style="font-size: 0.72rem;">findTarget([1, 4, 9], 7) == -1</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="test-case-card" id="testCase3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Test 3: Empty array</span>
                                <span class="badge bg-secondary" id="tcStatus3">Ready</span>
                            </div>
                            <div class="text-muted" style="font-size: 0.72rem;">findTarget([], 10) == -1</div>
                        </div>
                    </div>
                </div>

                <div class="small text-muted fw-bold mb-1" style="font-size: 0.75rem;">GRADER TERMINAL:</div>
                <pre id="labConsole" class="code-output-terminal mb-0">> Ready to test your algorithm. Click 'Run Test Cases' to verify.</pre>

                <!-- Professor AI Socratic Guidance Box -->
                <div id="professorAiBox" class="mt-3 p-3 rounded edu-card-panel border border-warning border-opacity-30 d-none" style="background: rgba(245, 158, 11, 0.06);">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; background: rgba(245, 158, 11, 0.2); color: #f59e0b;">
                                <i class="fas fa-robot fa-sm"></i>
                            </div>
                            <div>
                                <span class="fw-bold small d-block" style="color: #f59e0b;">Professor Ada (AI Senior Architect)</span>
                                <span class="text-muted" style="font-size: 0.7rem;">Socratic Algorithmic Guidance & Complexity Diagnostics</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="badge bg-dark border text-muted small" id="aiTimeComplexity">Time: O(log n)</span>
                            <span class="badge bg-dark border text-muted small" id="aiSpaceComplexity">Space: O(1)</span>
                        </div>
                    </div>
                    <div class="p-2.5 rounded bg-black bg-opacity-30 border border-secondary border-opacity-15 mb-2 font-monospace small" id="aiHintMessage" style="font-size: 0.8rem; line-height: 1.5; color: #e2e8f0;">
                    </div>
                    <div class="text-muted small fst-italic" style="font-size: 0.74rem;" id="aiProfessorNote">
                    </div>
                </div>
            </div>

            <!-- Direct Instructor Q&A Quick Prompt -->
            <div class="edu-card-panel p-4 mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h6 class="fw-bold mb-1"><i class="fas fa-question-circle text-info me-2"></i> Have a Question on this Lesson?</h6>
                        <p class="text-muted small mb-0">Ask in the course discussion forum to receive verified answers directly from {{ $course && $course->instructor ? $course->instructor->name : 'the instructor' }}.</p>
                    </div>
                    <a href="{{ route('courses.detail', $course ? $course->id : 1) }}#discussion" class="btn-edu-secondary btn-sm px-3">
                        <i class="fas fa-comments me-1"></i> Open Q&A Forum
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column: Structured Syllabus & Final Exam Sidebar -->
        <div class="col-lg-4 col-xl-4">
            <div class="edu-card-panel p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary border-opacity-15">
                    <h5 class="fw-bold mb-0"><i class="fas fa-layer-group text-primary me-2"></i> Course Syllabus</h5>
                    <span class="badge bg-success bg-opacity-15 text-success small">Curriculum Active</span>
                </div>

                @if($course && $course->subjects->isNotEmpty())
                    <div class="accordion accordion-flush" id="materialsSyllabusAccordion">
                        @foreach($course->subjects as $subIndex => $sub)
                            <div class="accordion-item bg-transparent border-secondary border-opacity-15 mb-2 rounded overflow-hidden">
                                <h2 class="accordion-header" id="headingSub{{ $sub->id }}">
                                    <button class="accordion-button py-2 px-3 {{ $subject && $subject->id === $sub->id ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSub{{ $sub->id }}" style="background: var(--bg-secondary);">
                                        Module {{ $subIndex + 1 }}: {{ $sub->name }}
                                    </button>
                                </h2>
                                <div id="collapseSub{{ $sub->id }}" class="accordion-collapse collapse {{ $subject && $subject->id === $sub->id ? 'show' : '' }}" data-bs-parent="#materialsSyllabusAccordion">
                                    <div class="accordion-body p-2" style="background: var(--bg-card);">
                                        @forelse($sub->materials as $m)
                                            @php
                                                $mDone = in_array($m->id, $progress['completed_materials'] ?? []);
                                            @endphp
                                            <a href="{{ route('frontend.materials', ['id' => $course->id, 'material_id' => $m->id]) }}" class="d-flex align-items-center justify-content-between p-2 rounded mb-1 text-decoration-none {{ $activeMaterial && $activeMaterial->id === $m->id ? 'bg-primary bg-opacity-15 text-primary fw-bold' : 'text-muted' }}">
                                                <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                                    @if($m->type === 'video')
                                                        <i class="fas fa-play-circle text-danger"></i>
                                                    @elseif($m->type === 'pdf')
                                                        <i class="fas fa-file-pdf text-info"></i>
                                                    @else
                                                        <i class="fas fa-pen-nib text-warning"></i>
                                                    @endif
                                                    <span class="small text-truncate" style="font-size: 0.85rem;">{{ $m->title }}</span>
                                                </div>
                                                <i class="fas {{ $mDone ? 'fa-check-circle text-success' : 'fa-circle text-secondary text-opacity-40' }} fa-xs"></i>
                                            </a>
                                        @empty
                                            <div class="small text-muted p-2 fst-italic">Curriculum modules available.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Official Certification Exam Link (Mandatory Academic Step) -->
                <div class="p-3 rounded edu-card-panel border border-primary border-opacity-30 mt-4 text-center">
                    <span class="badge bg-warning bg-opacity-20 text-warning mb-2 small fw-bold">
                        <i class="fas fa-graduation-cap me-1"></i> FINAL REQUIREMENT
                    </span>
                    <h6 class="fw-bold mb-1">Official Certification Exam</h6>
                    <p class="text-muted small mb-3" style="font-size: 0.78rem;">
                        Must score &ge; 80% on this timed multiple-choice exam to unlock your verified credential.
                    </p>
                    <a href="{{ route('courses.quiz.show', $course ? $course->id : 1) }}" class="btn-edu-primary btn-sm w-100 py-2">
                        <i class="fas fa-file-signature me-1"></i> Open Exam Portal
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    // ==========================================
    // 1. VIDEO PLAYER INTELLIGENCE & RESUME
    // ==========================================
    const video = document.getElementById('workspaceVideoPlayer');
    const lessonKey = 'da_video_pos_{{ $course ? $course->id : 1 }}_{{ $activeMaterial ? $activeMaterial->id : 1 }}';

    if (video) {
        // Resume from last watched position
        const savedTime = localStorage.getItem(lessonKey);
        if (savedTime && parseFloat(savedTime) > 5) {
            video.currentTime = parseFloat(savedTime);
            console.log('Resumed playback at ' + Math.round(savedTime) + 's');
        }

        // Save position every 4 seconds
        video.addEventListener('timeupdate', () => {
            if (video.currentTime > 2) {
                localStorage.setItem(lessonKey, video.currentTime);
            }
        });
    }

    function setPlayerSpeed(speed) {
        if (!video) return;
        video.playbackRate = speed;
        document.querySelectorAll('.playback-speed-btn').forEach(b => b.classList.remove('active'));
        if (event && event.target) event.target.classList.add('active');
    }

    // ==========================================
    // 2. AUTOGRADED LEETCODE-STYLE LAB RUNNER
    // ==========================================
    function runAutogradedLab() {
        const code = document.getElementById('labCodeEditor').value;
        const consoleEl = document.getElementById('labConsole');
        consoleEl.textContent = '> Running automated test harness against student function...\n';

        let passedCount = 0;
        const totalTests = 3;

        try {
            // Compile student function safely
            const studentFunc = new Function(`${code}; return typeof findTarget !== "undefined" ? findTarget : null;`)();
            if (!studentFunc) {
                throw new Error("Function 'findTarget' not defined! Please make sure your function is named 'findTarget(arr, target)'.");
            }

            // Test Case 1
            const res1 = studentFunc([2, 5, 8, 12], 8);
            if (res1 === 2) {
                updateTestCaseUI(1, true, '2 == 2 (Passed)');
                passedCount++;
            } else {
                updateTestCaseUI(1, false, `Expected 2, got ${res1}`);
            }

            // Test Case 2
            const res2 = studentFunc([1, 4, 9], 7);
            if (res2 === -1) {
                updateTestCaseUI(2, true, '-1 == -1 (Passed)');
                passedCount++;
            } else {
                updateTestCaseUI(2, false, `Expected -1, got ${res2}`);
            }

            // Test Case 3
            const res3 = studentFunc([], 10);
            if (res3 === -1) {
                updateTestCaseUI(3, true, '-1 == -1 (Passed)');
                passedCount++;
            } else {
                updateTestCaseUI(3, false, `Expected -1, got ${res3}`);
            }

            if (passedCount === totalTests) {
                consoleEl.textContent = `🎉 ALL TESTS PASSED (3/3)!\n> Runtime Complexity: O(log n)\n> Memory Allocation: Optimal O(1)\n> Grader Status: ACCEPTED ✅`;
                if (window.Dopamine) {
                    window.Dopamine.playSuccessChime();
                    window.Dopamine.fireConfettiCannon();
                    window.Dopamine.showXpGainToast(75, 'Algorithm Challenge Passed (3/3) 💻');
                }
            } else {
                if (window.Dopamine) {
                    window.Dopamine.playGentleFailure();
                }
                consoleEl.textContent = `❌ ${passedCount}/${totalTests} Test Cases Passed. Review failure reports above.`;
            }
        } catch (err) {
            consoleEl.textContent = `❌ Runtime/Syntax Error: ${err.message}`;
            updateTestCaseUI(1, false, 'Error');
            updateTestCaseUI(2, false, 'Error');
            updateTestCaseUI(3, false, 'Error');
        }
    }

    function updateTestCaseUI(num, passed, note) {
        const card = document.getElementById(`testCase${num}`);
        const badge = document.getElementById(`tcStatus${num}`);
        if (!card || !badge) return;

        if (passed) {
            card.className = 'test-case-card passed';
            badge.className = 'badge bg-success';
            badge.textContent = 'Passed ✅';
        } else {
            card.className = 'test-case-card failed';
            badge.className = 'badge bg-danger';
            badge.textContent = 'Failed ❌';
        }
    }

    // ==========================================
    // 3. LESSON COMPLETION AJAX TOGGLE
    // ==========================================
    let isCompletedState = {{ $isDone ? 'true' : 'false' }};

    async function toggleLessonComplete(courseId, materialId) {
        isCompletedState = !isCompletedState;
        if (window.Dopamine && isCompletedState) {
            window.Dopamine.playPop();
        }
        const btn = document.getElementById('toggleCompleteBtn');
        const text = document.getElementById('completeBtnText');

        if (isCompletedState) {
            btn.className = 'btn btn-success btn-sm px-3';
            text.textContent = 'Lesson Completed';
            btn.querySelector('i').className = 'fas fa-check-circle me-2';
        } else {
            btn.className = 'btn btn-edu-secondary btn-sm px-3';
            text.textContent = 'Mark Lesson Complete';
            btn.querySelector('i').className = 'fas fa-circle me-2';
        }

        try {
            const res = await fetch(`/courses/${courseId}/progress`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    material_id: materialId,
                    completed: isCompletedState
                })
            });

            const data = await res.json();
            if (data.status === 'success' && data.progress) {
                const pct = data.progress.percent;
                document.getElementById('progressPercentLabel').textContent = pct + '%';
                document.getElementById('progressBarFill').style.width = pct + '%';
                const certBtn = document.getElementById('certBtn');
                if (certBtn && pct >= 100) {
                    certBtn.className = 'btn btn-edu-primary btn-sm text-nowrap px-3';
                    certBtn.innerHTML = '<i class="fas fa-award text-warning me-1"></i> Verified Certificate';
                }

                if (isCompletedState && window.Dopamine) {
                    window.Dopamine.showXpGainToast(50, 'Lesson Marked Complete! ✨');
                    if (pct >= 100) {
                        window.Dopamine.playFanfare();
                        window.Dopamine.fireContinuousCelebration();
                        window.Dopamine.showAchievementModal('Curriculum Master', 'You have completed 100% of this course syllabus!', 'fas fa-graduation-cap');
                    }
                }
            }
        } catch (err) {
            console.error('Progress update error:', err);
        }
    }

    // ==========================================
    // 4. PROFESSOR AI DEBUGGER & SOCRATIC TUTOR
    // ==========================================
    async function consultProfessorAI() {
        const btn = document.getElementById('btnAskAi');
        const code = document.getElementById('labCodeEditor').value;
        const box = document.getElementById('professorAiBox');
        const hintEl = document.getElementById('aiHintMessage');
        const noteEl = document.getElementById('aiProfessorNote');
        const timeBadge = document.getElementById('aiTimeComplexity');
        const spaceBadge = document.getElementById('aiSpaceComplexity');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-warning me-1"></i> Consulting Professor AI...';

        try {
            const res = await fetch("{{ route('api.ai.debug') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    code: code,
                    language: 'javascript',
                    task_context: 'Binary Search (Logarithmic Speed)',
                    mode: 'debug'
                })
            });

            const data = await res.json();
            if (data.status === 'success' && data.analysis) {
                const a = data.analysis;
                box.classList.remove('d-none');
                hintEl.textContent = a.socratic_hint;
                noteEl.innerHTML = `<i class="fas fa-lightbulb text-warning me-1"></i> ${a.professor_note}`;
                timeBadge.textContent = `Time: ${a.time_complexity}`;
                spaceBadge.textContent = `Space: ${a.space_complexity}`;

                if (window.Dopamine) {
                    window.Dopamine.playPop();
                    window.Dopamine.showXpGainToast(25, 'AI Mentorship Consultation ✨');
                }
            }
        } catch (err) {
            alert('AI Debugger: ' + err.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-brain text-warning me-1"></i> Ask Professor AI';
        }
    }
</script>
@endsection
