@extends('frontend.master')

@section('title', 'Professor AI Code Sandbox & Socratic Lab – Digital Academy')

@section('styles')
<style>
    .sandbox-container {
        min-height: calc(100vh - 120px);
    }
    .code-editor-box {
        background: #090d16;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        font-family: 'Fira Code', 'JetBrains Mono', Consolas, monospace;
        font-size: 0.9rem;
        line-height: 1.6;
        color: #f8fafc;
        padding: 16px;
        width: 100%;
        min-height: 380px;
        resize: vertical;
        outline: none;
        tab-size: 4;
        box-shadow: inset 0 2px 8px rgba(0,0,0,0.4);
    }
    .code-editor-box:focus {
        border-color: var(--primary);
    }
    .tc-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        font-size: 0.8rem;
    }
    .tc-card.passed {
        border-color: var(--accent-emerald);
        background: rgba(16, 185, 129, 0.08);
    }
    .tc-card.failed {
        border-color: var(--accent-rose);
        background: rgba(244, 63, 94, 0.08);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-lg-5 py-4 sandbox-container">

    <!-- Top Sandbox Header -->
    <div class="edu-card-panel mb-4 p-3 p-md-4 shadow-sm">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.15); color: #6366f1; font-size: 1.4rem;">
                    <i class="fas fa-terminal"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-25 small fw-bold">
                            <i class="fas fa-robot me-1"></i> Powered by Professor AI
                        </span>
                        <span class="badge bg-success bg-opacity-15 text-success small">Real-Time Socratic Grader</span>
                    </div>
                    <h2 class="fw-bold mb-0 fs-4">Professor AI Code Sandbox & Socratic Lab</h2>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <select id="challengeSelector" class="form-select form-select-sm" style="width: auto; background: var(--bg-card); color: var(--text-main); border-color: var(--border-color);" onchange="switchChallenge(this.value)">
                    @foreach($challenges as $idx => $c)
                        <option value="{{ $idx }}">{{ $c['title'] }} ({{ $c['difficulty'] }})</option>
                    @endforeach
                </select>

                <select id="langSelector" class="form-select form-select-sm" style="width: auto; background: var(--bg-card); color: var(--text-main); border-color: var(--border-color);">
                    <option value="javascript">JavaScript (V8 Engine)</option>
                    <option value="python">Python 3 (Preview)</option>
                </select>

                <button type="button" class="btn btn-outline-warning btn-sm fw-bold px-3 d-flex align-items-center gap-1.5" onclick="askProfessorAI()" id="btnAskAi">
                    <i class="fas fa-brain text-warning"></i> <span>Ask Professor AI</span>
                </button>

                <button type="button" class="btn-edu-primary btn-sm px-4 fw-bold" onclick="runSandboxCode()" id="btnRunCode">
                    <i class="fas fa-play fa-xs me-1.5"></i> Run & Verify
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Code Editor Viewport -->
        <div class="col-lg-7 col-xl-8">
            <div class="edu-card-panel p-4 shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h5 class="fw-bold mb-0 fs-6" id="challengeTitle">{{ $challenges[0]['title'] }}</h5>
                        <p class="text-muted small mb-0" id="challengeDesc" style="font-size: 0.8rem;">{{ $challenges[0]['description'] }}</p>
                    </div>
                    <button type="button" class="btn btn-edu-secondary btn-sm px-2.5 py-1 text-muted" onclick="resetCurrentCode()" title="Reset to Starter Code">
                        <i class="fas fa-undo fa-xs me-1"></i> Reset Code
                    </button>
                </div>

                <!-- Live Editor Area -->
                <textarea id="sandboxCodeEditor" class="code-editor-box mt-3" spellcheck="false">{{ $challenges[0]['starter_code'] }}</textarea>

                <!-- Unit Test Cases Strip -->
                <div class="mt-3">
                    <div class="fw-bold small text-muted mb-2 text-uppercase" style="font-size: 0.72rem;">Automated Test Assertions:</div>
                    <div class="row g-2" id="testCasesContainer">
                        @foreach($challenges[0]['test_cases'] as $tIdx => $tc)
                            <div class="col-md-4">
                                <div class="tc-card" id="tcCard{{ $tIdx }}">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold text-truncate" style="max-width: 140px;">{{ $tc['label'] }}</span>
                                        <span class="badge bg-secondary" id="tcBadge{{ $tIdx }}">Ready</span>
                                    </div>
                                    <div class="text-muted font-monospace text-truncate" style="font-size: 0.7rem;">Expected: {{ $tc['expected'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Grader Execution Console -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted fw-bold small text-uppercase" style="font-size: 0.72rem;">GRADER TERMINAL:</span>
                        <span class="badge bg-dark text-muted font-monospace small" id="executionTimer">0.0 ms</span>
                    </div>
                    <pre id="sandboxConsole" class="code-output-terminal mb-0">> Click 'Run & Verify' to execute your algorithm against test suites.</pre>
                </div>
            </div>
        </div>

        <!-- Right: Professor AI Socratic Guidance & Big-O Inspector -->
        <div class="col-lg-5 col-xl-4">
            
            <!-- Professor AI Card -->
            <div class="edu-card-panel p-4 shadow-sm mb-4 border border-warning border-opacity-30 position-relative overflow-hidden" style="background: var(--bg-card);">
                <div class="position-absolute top-0 end-0 m-3 text-warning opacity-25">
                    <i class="fas fa-brain fa-3x"></i>
                </div>

                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-secondary border-opacity-15">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(245, 158, 11, 0.15); color: #f59e0b; font-size: 1.3rem; border: 1px solid rgba(245, 158, 11, 0.4);">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 fs-6" style="color: #f59e0b;">Professor Ada</h5>
                        <span class="text-muted small" style="font-size: 0.75rem;">AI Senior Software Architect</span>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Socratic Feedback & Bug Diagnostics:</span>
                    <div class="p-3 rounded font-monospace small" id="aiSocraticPanel" style="background: var(--bg-secondary); border: 1px solid var(--border-color); color: #e2e8f0; min-height: 100px; font-size: 0.82rem; line-height: 1.5;">
                        Click "Ask Professor AI" or run your code. Professor Ada will review your pointer logic, boundary conditions, and algorithmic invariants without giving away the direct answer!
                    </div>
                </div>

                <!-- Big-O & Efficiency Panel -->
                <div class="mb-3">
                    <span class="text-muted small text-uppercase fw-bold d-block mb-2" style="font-size: 0.7rem;">Complexity & Efficiency Rating:</span>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-2.5 rounded text-center" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                <span class="text-muted d-block" style="font-size: 0.68rem;">Time Complexity</span>
                                <strong class="text-primary font-monospace" id="aiTimeLabel">O(log n)</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2.5 rounded text-center" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                <span class="text-muted d-block" style="font-size: 0.68rem;">Space Complexity</span>
                                <strong class="text-info font-monospace" id="aiSpaceLabel">O(1)</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-2.5 rounded bg-warning bg-opacity-10 border border-warning border-opacity-25" id="aiNoteBox">
                    <div class="d-flex align-items-center gap-2 text-warning small fw-bold mb-1">
                        <i class="fas fa-lightbulb"></i> Professor's Production Tip
                    </div>
                    <p class="text-muted small mb-0" id="aiTipText" style="font-size: 0.75rem;">
                        Top engineering interviewers value clarity and invariant preservation. Always test for empty array and single-item boundary conditions.
                    </p>
                </div>
            </div>

            <!-- Quick Sandbox Tips -->
            <div class="edu-card-panel p-3 shadow-sm">
                <h6 class="fw-bold small mb-2"><i class="fas fa-keyboard text-primary me-1"></i> Interactive Pro-Tips</h6>
                <ul class="text-muted small mb-0 ps-3" style="font-size: 0.75rem; line-height: 1.6;">
                    <li>Use <kbd>Tab</kbd> inside editor to indent code.</li>
                    <li>Syntax and infinite loops are safely trapped in sandboxed browser workers.</li>
                    <li>Running your code successfully awards <strong class="text-warning">+75 XP</strong> towards your next Mastery Tier.</li>
                </ul>
            </div>

        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    const challenges = @json($challenges);
    let currentChallengeIdx = 0;

    function switchChallenge(idx) {
        currentChallengeIdx = parseInt(idx);
        const c = challenges[currentChallengeIdx];
        document.getElementById('challengeTitle').textContent = c.title;
        document.getElementById('challengeDesc').textContent = c.description;
        document.getElementById('sandboxCodeEditor').value = c.starter_code;

        // Render test case badges
        const tcContainer = document.getElementById('testCasesContainer');
        tcContainer.innerHTML = '';
        c.test_cases.forEach((tc, i) => {
            const col = document.createElement('div');
            col.className = 'col-md-4';
            col.innerHTML = `
                <div class="tc-card" id="tcCard${i}">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold text-truncate" style="max-width: 140px;">${tc.label}</span>
                        <span class="badge bg-secondary" id="tcBadge${i}">Ready</span>
                    </div>
                    <div class="text-muted font-monospace text-truncate" style="font-size: 0.7rem;">Expected: ${tc.expected}</div>
                </div>
            `;
            tcContainer.appendChild(col);
        });

        document.getElementById('sandboxConsole').textContent = `> Switched to challenge: ${c.title}. Click 'Run & Verify' to test.`;
    }

    function resetCurrentCode() {
        const c = challenges[currentChallengeIdx];
        document.getElementById('sandboxCodeEditor').value = c.starter_code;
        if (window.Dopamine) window.Dopamine.playPop();
    }

    function runSandboxCode() {
        const code = document.getElementById('sandboxCodeEditor').value;
        const consoleEl = document.getElementById('sandboxConsole');
        const timerEl = document.getElementById('executionTimer');
        const c = challenges[currentChallengeIdx];
        const t0 = performance.now();

        try {
            // Safe evaluation of student function
            const studentFn = new Function(`
                ${code}
                if (typeof findTarget === 'function') return findTarget;
                if (typeof twoSum === 'function') return twoSum;
                if (typeof reverseString === 'function') return reverseString;
                throw new Error('Could not find challenge entry function in your code.');
            `)();

            let passed = 0;
            const total = c.test_cases.length;
            let logDetails = [];

            c.test_cases.forEach((tc, i) => {
                const card = document.getElementById(`tcCard${i}`);
                const badge = document.getElementById(`tcBadge${i}`);
                const args = JSON.parse(tc.input);
                const expected = JSON.parse(tc.expected);

                let actual;
                try {
                    actual = studentFn(...args);
                } catch (e) {
                    actual = 'Error: ' + e.message;
                }

                const isMatch = JSON.stringify(actual) === JSON.stringify(expected);
                if (isMatch) {
                    passed++;
                    if (card) card.className = 'tc-card passed';
                    if (badge) { badge.className = 'badge bg-success'; badge.textContent = 'Passed ✅'; }
                    logDetails.push(`✓ Test ${i+1} Passed: ${tc.label}`);
                } else {
                    if (card) card.className = 'tc-card failed';
                    if (badge) { badge.className = 'badge bg-danger'; badge.textContent = 'Failed ❌'; }
                    logDetails.push(`✗ Test ${i+1} Failed: Expected ${tc.expected}, got ${JSON.stringify(actual)}`);
                }
            });

            const elapsed = (performance.now() - t0).toFixed(1);
            timerEl.textContent = `${elapsed} ms`;

            if (passed === total) {
                consoleEl.textContent = `🎉 ALL TESTS PASSED (${passed}/${total}) in ${elapsed}ms!\n` + logDetails.join('\n') + `\n> Status: ACCEPTED ✅`;
                if (window.Dopamine) {
                    window.Dopamine.playSuccessChime();
                    window.Dopamine.fireConfettiCannon();
                    window.Dopamine.showXpGainToast(75, 'Algorithm Challenge Passed (3/3) 💻');
                }
            } else {
                consoleEl.textContent = `❌ ${passed}/${total} Tests Passed in ${elapsed}ms.\n` + logDetails.join('\n') + `\n> Click 'Ask Professor AI' for Socratic guidance!`;
                if (window.Dopamine) window.Dopamine.playGentleFailure();
            }

        } catch (err) {
            const elapsed = (performance.now() - t0).toFixed(1);
            timerEl.textContent = `${elapsed} ms`;
            consoleEl.textContent = `❌ Execution / Syntax Error: ${err.message}\n> Click 'Ask Professor AI' to debug this error!`;
            if (window.Dopamine) window.Dopamine.playGentleFailure();
        }
    }

    async function askProfessorAI() {
        const btn = document.getElementById('btnAskAi');
        const code = document.getElementById('sandboxCodeEditor').value;
        const c = challenges[currentChallengeIdx];
        const socraticEl = document.getElementById('aiSocraticPanel');
        const timeEl = document.getElementById('aiTimeLabel');
        const spaceEl = document.getElementById('aiSpaceLabel');
        const tipEl = document.getElementById('aiTipText');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-warning me-1"></i> Analyzing...';

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
                    task_context: c.title,
                    mode: 'debug'
                })
            });

            const data = await res.json();
            if (data.status === 'success' && data.analysis) {
                const a = data.analysis;
                socraticEl.innerHTML = `<i class="fas fa-comment-dots text-warning me-1"></i> ${a.socratic_hint}`;
                timeEl.textContent = a.time_complexity;
                spaceEl.textContent = a.space_complexity;
                tipEl.textContent = a.professor_note;

                if (window.Dopamine) {
                    window.Dopamine.playPop();
                    window.Dopamine.showXpGainToast(25, 'Professor AI Consultation 🧠');
                }
            }
        } catch (err) {
            alert('AI Debugger Error: ' + err.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-brain text-warning me-1"></i> Ask Professor AI';
        }
    }
</script>
@endsection
