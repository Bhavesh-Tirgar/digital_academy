@extends('frontend.master')

@section('title', '🔴 Live Classroom: ' . $lecture->title . ' – Digital Academy')

@section('styles')
<style>
    /* Scoped Classroom Design System */
    .mobile-bottom-nav {
        display: none !important;
    }
    .classroom-wrapper {
        min-height: calc(100vh - 80px);
    }
    .unmute-banner {
        position: absolute;
        top: 60px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(15, 23, 42, 0.9);
        color: #ffffff;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(239, 68, 68, 0.5);
        border-radius: 999px;
        padding: 8px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        z-index: 25;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        gap: 8px;
        transition: transform 0.2s ease, background 0.2s ease;
    }
    .unmute-banner:hover {
        background: rgba(225, 29, 72, 0.95);
        transform: translateX(-50%) scale(1.03);
    }
    .chat-bubble-text {
        color: var(--text-primary);
        font-size: 0.86rem;
        line-height: 1.45;
        word-break: break-word;
    }
    .classroom-player-card {
        background: #0b0f19;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    .classroom-video-viewport {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        background: #000000;
        overflow: hidden;
    }
    .classroom-video-viewport video,
    .classroom-video-viewport canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .classroom-video-viewport,
    .classroom-video-viewport .badge,
    .classroom-video-viewport button,
    .classroom-video-viewport span {
        color: #ffffff !important;
    }
    .live-player-overlay-top {
        position: absolute;
        top: 14px;
        left: 14px;
        right: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 20;
        pointer-events: auto;
    }
    .live-broadcast-announcement-banner {
        position: absolute;
        top: 60px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(16, 185, 129, 0.95);
        color: #ffffff;
        padding: 6px 18px;
        border-radius: var(--radius-full);
        font-size: 0.82rem;
        font-weight: 700;
        z-index: 22;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
        display: none;
        align-items: center;
        gap: 8px;
    }
    .floating-reactions-tray {
        position: absolute;
        bottom: 16px;
        right: 16px;
        display: flex;
        gap: 6px;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        padding: 6px 10px;
        border-radius: var(--radius-full);
        border: 1px solid rgba(255, 255, 255, 0.15);
        z-index: 20;
    }
    .btn-emoji-reaction {
        background: transparent;
        border: none;
        font-size: 1.15rem;
        cursor: pointer;
        transition: transform 0.15s ease;
        padding: 0 2px;
    }
    .btn-emoji-reaction:hover {
        transform: scale(1.3);
    }
    .flying-emoji {
        position: absolute;
        bottom: 60px;
        font-size: 1.8rem;
        animation: flyUpAndFade 2.2s ease-out forwards;
        pointer-events: none;
        z-index: 30;
    }
    @keyframes flyUpAndFade {
        0% { opacity: 1; transform: translateY(0) scale(0.8); }
        50% { opacity: 0.9; transform: translateY(-100px) scale(1.2); }
        100% { opacity: 0; transform: translateY(-220px) scale(1.5); }
    }
    .studio-toolbar {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 14px 20px;
        margin-top: 14px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        box-shadow: var(--shadow-sm);
    }
    .interactive-panel {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        height: 680px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    .interactive-tabs-header {
        display: flex;
        background: var(--bg-secondary);
        border-bottom: 1px solid var(--border-color);
    }
    .interactive-tab-btn {
        flex: 1;
        padding: 12px 6px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        border-bottom: 2px solid transparent;
        text-align: center;
    }
    .interactive-tab-btn.active {
        color: var(--primary);
        font-weight: 700;
        background: var(--bg-card);
        border-bottom-color: var(--primary);
    }
    .interactive-tab-content {
        padding: 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }
    .chat-messages-container {
        flex-grow: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding-right: 4px;
    }
    .chat-bubble {
        padding: 10px 14px;
        border-radius: var(--radius-md);
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
    }
    .chat-bubble.is-instructor {
        background: rgba(16, 185, 129, 0.08);
        border-left: 3px solid var(--accent-emerald);
    }
    .canvas-hidden {
        display: none !important;
    }
    .doubt-queue-card {
        background: rgba(245, 158, 11, 0.06);
        border: 1px solid rgba(245, 158, 11, 0.25);
        border-radius: var(--radius-md);
        padding: 10px 14px;
        margin-bottom: 12px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-lg-5 py-3 py-md-4 classroom-wrapper">

    <!-- Top Classroom Header Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3 pb-3 border-bottom border-secondary border-opacity-15">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('schedule.index') }}" class="btn-edu-secondary btn-sm px-3" title="Back to Schedule">
                <i class="fas fa-arrow-left me-1"></i> <span class="d-none d-sm-inline">Schedule</span>
            </a>
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    @if($isInstructorOrAdmin)
                        <span class="badge bg-danger text-uppercase px-2 py-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                            <i class="fas fa-satellite-dish me-1"></i> HOST BROADCAST STUDIO
                        </span>
                        <span class="badge bg-success bg-opacity-15 text-success border border-success border-opacity-25 small fw-bold">
                            Teacher Host (You)
                        </span>
                    @else
                        <span class="badge bg-primary text-uppercase px-2 py-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                            <i class="fas fa-graduation-cap me-1"></i> STUDENT LEARNING CONSOLE
                        </span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 small">
                            Connected to Host: {{ $lecture->instructor->name }}
                        </span>
                    @endif
                    <span class="badge bg-secondary bg-opacity-10 text-primary small">⚡ 0.8s Low-Latency</span>
                </div>
                <h2 class="fw-bold mb-0 fs-5 fs-md-4">{{ $lecture->title }}</h2>
            </div>
        </div>

        <!-- Instructor Badge & Live Counter -->
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 px-3 py-1 rounded-pill" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25);">
                <i class="fas fa-users text-danger"></i>
                <span class="fw-bold small"><span id="liveViewerCounter">{{ $lecture->active_viewers ?? 148 }}</span> Watching</span>
            </div>

            <div class="d-none d-sm-flex align-items-center gap-2">
                <img src="{{ $lecture->instructor->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" alt="{{ $lecture->instructor->name }}" class="rounded-circle border border-primary" style="width: 36px; height: 36px; object-fit: cover;">
                <div>
                    <div class="small fw-bold">{{ $lecture->instructor->name }}</div>
                    <div class="text-muted" style="font-size: 0.7rem;">Host Instructor</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Live Classroom Layout -->
    <div class="row g-4">

        <!-- Left Column: Video Stream Viewport & Broadcast Studio -->
        <div class="col-lg-8 col-xl-8">
            <div class="classroom-player-card">
                <!-- Video Screen Viewport -->
                <div class="classroom-video-viewport" id="videoViewport">
                    <!-- HTML5 Video Player -->
                    <video id="liveVideoPlayer" autoplay muted playsinline controls poster="{{ $lecture->course->thumbnail ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200' }}">
                        @if(!empty($lecture->stream_url))
                            <source src="{{ $lecture->stream_url }}" type="video/mp4">
                        @endif
                    </video>

                    <!-- Real-Time Shared Screen & Canvas Relay Viewport (for instant frame display) -->
                    <canvas id="studentStreamCanvas" width="1280" height="720" class="canvas-hidden"></canvas>

                    <!-- Hidden Whiteboard Canvas for Simulator -->
                    <canvas id="whiteboardCanvas" width="1280" height="720" class="canvas-hidden"></canvas>

                    <!-- Hidden Offscreen Canvas for Teacher Screen Frame Capture -->
                    <canvas id="captureRelayCanvas" width="1280" height="720" class="canvas-hidden"></canvas>

                    <!-- Unmute Prompt (Auto-appears if muted) -->
                    <div id="unmutePrompt" class="unmute-banner" onclick="window.unmuteLiveStream()">
                        <i class="fas fa-volume-mute text-danger"></i>
                        <span>Click to Unmute Live Audio</span>
                    </div>

                    <!-- Live Teacher Broadcast Announcement Banner (Visible to Students) -->
                    <div id="liveBroadcastBanner" class="live-broadcast-announcement-banner">
                        <i class="fas fa-desktop" id="broadcastBannerIcon"></i>
                        <span id="broadcastBannerText">Teacher Dr. Sarah Jenkins is sharing her screen</span>
                    </div>

                    <!-- Top Stream Overlay -->
                    <div class="live-player-overlay-top">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge-live-pulse" style="background: rgba(239, 68, 68, 0.2);"><i class="fas fa-circle fa-2xs"></i> LIVE</span>
                            <span class="badge bg-dark bg-opacity-75 text-white border border-secondary border-opacity-25" id="streamQualityBadge">1080p FHD</span>
                            <span class="badge bg-success bg-opacity-75 text-white" id="streamSourceBadge">
                                {{ $isInstructorOrAdmin ? 'Host Studio Ready' : 'CDN Primary' }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            @if($isInstructorOrAdmin)
                                <!-- Stream Source Switcher for Host -->
                                <div class="dropdown">
                                    <button class="btn btn-dark btn-sm py-1 px-2 border border-secondary border-opacity-50 text-white dropdown-toggle" type="button" data-bs-toggle="dropdown" style="font-size: 0.75rem;">
                                        <i class="fas fa-sliders-h me-1"></i> Stream Source
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" style="font-size: 0.8rem;">
                                        <li><a class="dropdown-item active" href="javascript:void(0)" onclick="window.switchStreamSource('cdn1')">CDN Stream 1 (Primary HD)</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="window.switchStreamSource('cdn2')">CDN Stream 2 (Mirror)</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="window.switchStreamSource('simulator')">🎨 Interactive Tech Whiteboard</a></li>
                                    </ul>
                                </div>
                            @endif

                            <!-- Stream Quality Picker -->
                            <div class="dropdown">
                                <button class="btn btn-dark btn-sm py-1 px-2 border border-secondary border-opacity-50 text-white dropdown-toggle" type="button" data-bs-toggle="dropdown" style="font-size: 0.75rem;">
                                    <i class="fas fa-cog me-1"></i> Quality
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" style="font-size: 0.8rem;">
                                    <li><a class="dropdown-item active" href="javascript:void(0)" onclick="window.setStreamQuality('1080p FHD')">1080p FHD (Auto)</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="window.setStreamQuality('720p HD')">720p HD</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="window.setStreamQuality('480p SD')">480p SD (Data Saver)</a></li>
                                </ul>
                            </div>

                            <button class="btn btn-dark btn-sm py-1 px-2 border border-secondary border-opacity-50 text-white" onclick="window.toggleFullScreen()" title="Fullscreen">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Floating Emoji Reactions Tray -->
                    <div class="floating-reactions-tray">
                        <button class="btn-emoji-reaction" onclick="window.spawnReaction('🔥')" title="Fire">🔥</button>
                        <button class="btn-emoji-reaction" onclick="window.spawnReaction('👏')" title="Clap">👏</button>
                        <button class="btn-emoji-reaction" onclick="window.spawnReaction('💡')" title="Insightful">💡</button>
                        <button class="btn-emoji-reaction" onclick="window.spawnReaction('❤️')" title="Love">❤️</button>
                        <button class="btn-emoji-reaction" onclick="window.spawnReaction('🚀')" title="Rocket">🚀</button>
                    </div>

                    <!-- Floating Container for Spawned Flying Emojis -->
                    <div id="flyingEmojisContainer"></div>
                </div>
            </div>

            <!-- ROLE-SPECIFIC TOOLBAR (TEACHER HOST CONTROLS VS STUDENT LEARNER BAR) -->
            @if($isInstructorOrAdmin)
                <!-- ============================================== -->
                <!-- 1. TEACHER / HOST BROADCAST STUDIO TOOLBAR      -->
                <!-- ============================================== -->
                <div class="studio-toolbar">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger text-uppercase px-2 py-1" style="font-size: 0.72rem;">
                            <i class="fas fa-broadcast-tower me-1"></i> Studio Controls
                        </span>
                        <span class="text-muted small d-none d-sm-inline fw-semibold">Broadcast Live to Cohort:</span>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <!-- Camera Broadcast Toggle -->
                        <button type="button" class="btn-edu-secondary btn-sm" id="btnToggleCam" onclick="window.toggleWebcamBroadcast()">
                            <i class="fas fa-video me-1 text-danger"></i> <span id="camBtnText">Start Webcam</span>
                        </button>

                        <!-- Screen Share Toggle -->
                        <button type="button" class="btn-edu-secondary btn-sm" id="btnToggleScreen" onclick="window.toggleScreenShareBroadcast()">
                            <i class="fas fa-desktop me-1 text-info"></i> <span id="screenBtnText">Share Screen</span>
                        </button>

                        <!-- Live Whiteboard Toggle -->
                        <button type="button" class="btn-edu-secondary btn-sm" id="btnToggleWhiteboard" onclick="window.switchStreamSource('simulator')">
                            <i class="fas fa-paint-brush me-1 text-warning"></i> Whiteboard
                        </button>

                        <!-- Mic Toggle -->
                        <button type="button" class="btn-edu-secondary btn-sm" id="btnToggleMic" onclick="window.toggleMic()">
                            <i class="fas fa-microphone me-1 text-success"></i> <span id="micBtnText">Mute Mic</span>
                        </button>

                        <!-- Launch Live Poll Button -->
                        <button type="button" class="btn-edu-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newPollModal">
                            <i class="fas fa-poll-h me-1"></i> Launch Quiz
                        </button>
                    </div>
                </div>
            @else
                <!-- ============================================== -->
                <!-- 2. STUDENT LEARNER INTERACTIVE BAR              -->
                <!-- ZERO BROADCAST CONTROLS - PURE VIEWER CONTROLS -->
                <!-- ============================================== -->
                <div class="studio-toolbar" style="background: var(--bg-card);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary text-uppercase px-2 py-1" style="font-size: 0.72rem;">
                            <i class="fas fa-user-graduate me-1"></i> Student Tools
                        </span>
                        <span class="text-muted small d-none d-sm-inline">Classroom Interactivity:</span>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <!-- Raise Hand Button (Only for Students!) -->
                        <button type="button" class="btn-edu-secondary btn-sm" id="raiseHandBtn" onclick="window.toggleRaiseHand()">
                            <i class="fas fa-hand-paper text-warning me-1"></i> <span id="raiseHandText">Raise Hand (Ask Doubt)</span>
                        </button>

                        <button type="button" class="btn-edu-secondary btn-sm" onclick="window.syncWithTeacherStream()" title="Re-sync with Teacher Stream">
                            <i class="fas fa-sync-alt me-1 text-primary"></i> Sync Stream
                        </button>

                        <button type="button" class="btn-edu-secondary btn-sm" onclick="window.copyClassroomLink()">
                            <i class="fas fa-share-alt me-1 text-info"></i> Share
                        </button>

                        <button type="button" class="btn-edu-secondary btn-sm" onclick="window.testAudioTone()">
                            <i class="fas fa-volume-up me-1 text-success"></i> Audio Test
                        </button>
                    </div>
                </div>
            @endif

            <!-- Lecture Info Card Below Player -->
            <div class="edu-card-panel mt-4 p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h4 class="fw-bold mb-1 fs-5">About this Live Session</h4>
                        <p class="text-muted small mb-0">{{ $lecture->description ?? 'Deep dive into computer science fundamentals, system architecture patterns, and live code reviews.' }}</p>
                    </div>
                    @if($isInstructorOrAdmin)
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 small">
                            <i class="fas fa-shield-alt me-1"></i> Host Privileges Active
                        </span>
                    @else
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 small">
                            <i class="fas fa-check-circle me-1"></i> Enrolled Learner
                        </span>
                    @endif
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-sm-4">
                        <div class="p-3 rounded" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                            <div class="text-muted small" style="font-size: 0.72rem;">HOST INSTRUCTOR</div>
                            <div class="fw-bold">{{ $lecture->instructor->name }}</div>
                            <div class="text-primary small">{{ $lecture->instructor->headline ?? 'Lead Architect' }}</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 rounded" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                            <div class="text-muted small" style="font-size: 0.72rem;">STREAM PROTOCOL</div>
                            <div class="fw-bold">WebRTC & Dual-Channel</div>
                            <div class="text-success small">Real-Time Peer Streaming</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 rounded" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                            <div class="text-muted small" style="font-size: 0.72rem;">INTERACTIVITY</div>
                            <div class="fw-bold">Live Q&A + Code Runner</div>
                            <div class="text-primary small">Instant Mentor Feedback</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Interactive Multi-Tab Command Center -->
        <div class="col-lg-4 col-xl-4">
            <div class="interactive-panel shadow-sm">

                <!-- Interactive Tab Navigation Header -->
                <div class="interactive-tabs-header">
                    <button class="interactive-tab-btn active" id="tabBtnChat" onclick="window.switchClassroomTab('chat')">
                        <i class="fas fa-comments me-1"></i> Live Chat
                    </button>
                    <button class="interactive-tab-btn" id="tabBtnPolls" onclick="window.switchClassroomTab('polls')">
                        <i class="fas fa-poll-h me-1"></i> Live Polls
                    </button>
                    <button class="interactive-tab-btn" id="tabBtnCode" onclick="window.switchClassroomTab('code')">
                        <i class="fas fa-code me-1"></i> Live Code
                    </button>
                    <button class="interactive-tab-btn" id="tabBtnNotes" onclick="window.switchClassroomTab('notes')">
                        <i class="fas fa-book-reader me-1"></i> Notes
                    </button>
                </div>

                <!-- TAB 1: Real-Time Live Chat Stream -->
                <div class="interactive-tab-content" id="tabContentChat">

                    <!-- TEACHER ONLY: Raised Hands Doubt Queue Header -->
                    @if($isInstructorOrAdmin)
                        <div class="doubt-queue-card" id="teacherDoubtQueueCard">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold small text-warning">
                                    <i class="fas fa-hand-paper me-1"></i> Raised Hands Queue (<span id="handCountBadge">0</span>)
                                </span>
                                <span class="badge bg-warning bg-opacity-20 text-warning" style="font-size: 0.65rem;">Host Priority</span>
                            </div>
                            <div id="raisedHandsListContainer" class="d-flex flex-column gap-1" style="max-height: 110px; overflow-y: auto;">
                                <div class="text-muted small fst-italic py-1" id="noHandsPlaceholder" style="font-size: 0.75rem;">No raised hands currently in queue.</div>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom border-secondary border-opacity-15">
                        <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">
                            <i class="fas fa-circle text-success fa-2xs me-1"></i> Cohort Live Chat
                        </span>
                        <span class="text-muted small" style="font-size: 0.7rem;">Auto-scrolling</span>
                    </div>

                    <!-- Messages Scroll Feed -->
                    <div class="chat-messages-container" id="chatFeed">
                        @forelse($lecture->comments as $msg)
                            <div class="chat-bubble {{ $msg->is_instructor_reply ? 'is-instructor' : '' }}">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold {{ $msg->is_instructor_reply ? 'text-success' : 'text-primary' }}" style="font-size: 0.78rem;">
                                        @if($msg->is_instructor_reply)
                                            <i class="fas fa-chalkboard-teacher me-1"></i> {{ $msg->user ? $msg->user->name : 'Instructor' }}
                                        @else
                                            {{ $msg->user ? $msg->user->name : 'Learner' }}
                                        @endif
                                    </span>
                                    <span class="text-muted" style="font-size: 0.68rem;">{{ $msg->created_at->format('h:i A') }}</span>
                                </div>
                                <div class="chat-bubble-text">{{ $msg->comment }}</div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small" id="noChatPlaceholder">
                                <i class="far fa-comments fa-2x mb-2 d-block text-secondary"></i>
                                Live chat stream active. Ask questions or discuss code with your cohort!
                            </div>
                        @endforelse
                    </div>

                    <!-- Chat Input Box -->
                    <form onsubmit="window.sendLiveChatMessage(event)" class="mt-3">
                        <div class="input-group">
                            <input type="text" id="liveChatInput" class="form-control" placeholder="{{ $isInstructorOrAdmin ? 'Post instructor announcement or answer...' : 'Ask a doubt or share thoughts with cohort...' }}" maxlength="500" required>
                            <button type="submit" class="btn-edu-primary px-3">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: Live Polls / Pop Quizzes -->
                <div class="interactive-tab-content d-none" id="tabContentPolls">
                    <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom border-secondary border-opacity-15">
                        <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">
                            <i class="fas fa-poll-h text-primary me-1"></i> Live Knowledge Check
                        </span>
                        @if($isInstructorOrAdmin)
                            <button type="button" class="btn-edu-primary btn-sm py-1 px-2" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#newPollModal">
                                + New Poll
                            </button>
                        @endif
                    </div>

                    <div id="activePollContainer" class="p-3 rounded mb-3" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                        <h6 class="fw-bold mb-3 fs-6" id="pollQuestionText">{{ $poll['question'] ?? 'No active poll right now' }}</h6>
                        <div class="d-flex flex-column gap-2" id="pollOptionsContainer">
                            @if(isset($poll['options']))
                                @foreach($poll['options'] as $opt)
                                    @php
                                        $pct = ($poll['total_votes'] ?? 0) > 0 ? round(($opt['votes'] / $poll['total_votes']) * 100) : 0;
                                    @endphp
                                    <div class="p-2 rounded cursor-pointer border position-relative overflow-hidden poll-option-row" onclick="window.voteLivePoll({{ $opt['id'] }})" style="background: var(--bg-card); border-color: var(--border-color) !important;">
                                        <div class="poll-progress-fill" style="position: absolute; left: 0; top: 0; bottom: 0; width: {{ $pct }}%; background: rgba(37, 99, 235, 0.15); transition: width 0.4s ease; pointer-events: none;"></div>
                                        <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                                            <span class="small fw-semibold">{{ $opt['text'] }}</span>
                                            <span class="text-primary small fw-bold">{{ $pct }}% ({{ $opt['votes'] }})</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="text-muted small mt-2 d-flex justify-content-between" style="font-size: 0.72rem;">
                            <span>Click an option to cast your vote</span>
                            <span>Total Votes: <strong id="pollTotalVotesCount">{{ $poll['total_votes'] ?? 0 }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: In-Browser Code Runner -->
                <div class="interactive-tab-content d-none" id="tabContentCode">
                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom border-secondary border-opacity-15">
                        <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">
                            <i class="fas fa-terminal text-info me-1"></i> Interactive JavaScript Sandbox
                        </span>
                        <button type="button" class="btn-edu-primary btn-sm py-1 px-3" style="font-size: 0.75rem;" onclick="window.runScratchpadCode()">
                            <i class="fas fa-play me-1"></i> Run Code
                        </button>
                    </div>

                    <div class="flex-grow-1 d-flex flex-column gap-2">
                        <textarea id="liveCodeEditor" class="form-control font-monospace p-3 flex-grow-1" style="font-size: 0.82rem; background: #0f172a; color: #38bdf8; border: 1px solid var(--border-color); resize: none;" placeholder="// Test lecture code snippets live here...
function binarySearch(arr, target) {
    let left = 0, right = arr.length - 1;
    while (left <= right) {
        let mid = Math.floor((left + right) / 2);
        if (arr[mid] === target) return mid;
        if (arr[mid] < target) left = mid + 1;
        else right = mid - 1;
    }
    return -1;
}

console.log('Target found at index:', binarySearch([10, 20, 30, 40, 50], 40));
"></textarea>

                        <div class="p-2 rounded font-monospace" style="background: #070a12; border: 1px solid var(--border-color); height: 120px; overflow-y: auto; font-size: 0.78rem; color: #10b981;" id="codeOutputConsole">
                            > Output will appear here after clicking "Run Code"...
                        </div>
                    </div>
                </div>

                <!-- TAB 4: Personal Learner Notes -->
                <div class="interactive-tab-content d-none" id="tabContentNotes">
                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom border-secondary border-opacity-15">
                        <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">
                            <i class="fas fa-sticky-note text-warning me-1"></i> Scratchpad Notes (Auto-saved)
                        </span>
                        <button type="button" class="btn-edu-secondary btn-sm py-1 px-2" style="font-size: 0.75rem;" onclick="window.copyMyNotes()">
                            <i class="far fa-copy me-1"></i> Copy
                        </button>
                    </div>

                    <textarea id="studentPersonalNotes" class="form-control p-3 flex-grow-1" style="font-size: 0.85rem; background: var(--bg-secondary); border: 1px solid var(--border-color); resize: none;" placeholder="Jot down key points, timestamp takeaways, or questions for office hours..."></textarea>
                    <div class="text-muted small mt-2" style="font-size: 0.72rem;">
                        <i class="fas fa-check text-success me-1"></i> Stored in local browser memory. Retained across refreshes.
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Modal: Launch Live Poll (Instructor Only) -->
<div class="modal fade" id="newPollModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title fw-bold"><i class="fas fa-poll-h text-primary me-2"></i> Launch Pop Quiz to Cohort</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form onsubmit="window.submitNewPoll(event)">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="text-muted small fw-semibold mb-1">Question / Prompt:</label>
                        <input type="text" id="pollQuestionInput" class="form-control" placeholder="e.g. Which data structure is used in BFS?" required>
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small fw-semibold mb-1">Option 1:</label>
                        <input type="text" id="pollOpt1" class="form-control form-control-sm" placeholder="e.g. Queue" required>
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small fw-semibold mb-1">Option 2:</label>
                        <input type="text" id="pollOpt2" class="form-control form-control-sm" placeholder="e.g. Stack" required>
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small fw-semibold mb-1">Option 3 (Optional):</label>
                        <input type="text" id="pollOpt3" class="form-control form-control-sm" placeholder="e.g. Heap">
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small fw-semibold mb-1">Option 4 (Optional):</label>
                        <input type="text" id="pollOpt4" class="form-control form-control-sm" placeholder="e.g. Hash Table">
                    </div>
                </div>
                <div class="modal-footer p-3" style="border-top: 1px solid var(--border-color); background: var(--bg-secondary);">
                    <button type="button" class="btn-edu-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-edu-primary">Launch Poll to Cohort</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // =========================================================================
    // DIGITAL ACADEMY LIVE CLASSROOM DUAL-ROLE ENGINE
    // Pure Global Scope Implementation - Guaranteed Zero ReferenceError
    // =========================================================================

    const isHost = {{ $isInstructorOrAdmin ? 'true' : 'false' }};
    const lectureId = {{ $lecture->id }};
    const currentUserName = "{{ Auth::user()->name ?? 'Learner' }}";
    const currentUserId = {{ Auth::id() ?? 0 }};

    const messagesUrl = "{{ route('live.messages', $lecture->id) }}";
    const postMessageUrl = "{{ route('live.messages.store', $lecture->id) }}";
    const signalSendUrl = "{{ route('live.signal.send', $lecture->id) }}";
    const signalGetUrl = "{{ route('live.signal.get', $lecture->id) }}";
    const raiseHandUrl = "{{ route('live.hand.raise', $lecture->id) }}";

    const video = document.getElementById('liveVideoPlayer');
    const studentCanvas = document.getElementById('studentStreamCanvas');
    const studentCtx = studentCanvas ? studentCanvas.getContext('2d') : null;
    const captureCanvas = document.getElementById('captureRelayCanvas');
    const captureCtx = captureCanvas ? captureCanvas.getContext('2d') : null;
    const unmutePrompt = document.getElementById('unmutePrompt');
    const liveBroadcastBanner = document.getElementById('liveBroadcastBanner');
    const broadcastBannerText = document.getElementById('broadcastBannerText');

    let canvasAnimationId = null;
    let frameRelayInterval = null;
    let mediaStream = null;
    let studentHandRaised = false;
    let micMuted = false;

    // WebRTC Peer Configuration (STUN Google Servers)
    const rtcConfig = {
        iceServers: [
            { urls: 'stun:stun.l.google.com:19302' },
            { urls: 'stun:stun1.l.google.com:19302' }
        ]
    };
    let peerConnection = null;

    // Fast 0ms cross-tab signaling channel
    const liveChannel = new BroadcastChannel('da_live_room_' + lectureId);

    // =========================================================================
    // 1. DUAL-CHANNEL REAL-TIME SIGNALING & STREAM LISTENER
    // =========================================================================
    liveChannel.onmessage = function (ev) {
        handleIncomingSignal(ev.data);
    };

    function handleIncomingSignal(data) {
        if (!data) return;

        // A. Chat Message Broadcast
        if (data.type === 'chat_message' && data.message) {
            appendChatMessage(data.message);
        }
        // B. Stream State Update (Teacher Webcam / Screen Share / Whiteboard)
        else if (data.type === 'stream_update') {
            applyStreamState(data);
        }
        // C. Live Frame Data (Instant high-FPS visual frame relay to student canvas)
        else if (data.type === 'screen_frame' && data.frame_data) {
            renderStudentRelayFrame(data.frame_data, data.stream_type);
        }
        // D. Raise Hand (Student notifies Teacher)
        else if (data.type === 'raise_hand') {
            if (isHost) {
                showLiveToast('✋ ' + (data.student || 'A student') + ' raised their hand!', 'hand-paper', 'warning');
                fetchAndRenderRaisedHands();
            }
        }
        // E. Hand Dismissed (Teacher answered)
        else if (data.type === 'hand_dismissed') {
            if (data.user_id === currentUserId) {
                resetStudentRaiseHandButton();
                showLiveToast('Your question was addressed by the instructor!', 'check-circle', 'success');
            }
            if (isHost) {
                fetchAndRenderRaisedHands();
            }
        }
        // F. WebRTC Signaling (SDP Offer / Answer / ICE)
        else if (data.type === 'webrtc_signal') {
            handleWebRTCSignal(data);
        }
    }

    // Apply Stream State to Student Player
    function applyStreamState(data) {
        const badge = document.getElementById('streamSourceBadge');
        if (badge) {
            badge.textContent = data.source_label || (data.is_live ? 'Live Broadcast' : 'CDN Primary');
            badge.className = data.is_live ? 'badge bg-danger text-white' : 'badge bg-success bg-opacity-75 text-white';
        }

        if (!isHost) {
            if (data.is_live && (data.stream_type === 'screen' || data.stream_type === 'webcam')) {
                // Show announcement banner to student
                if (liveBroadcastBanner) {
                    liveBroadcastBanner.style.display = 'flex';
                    broadcastBannerText.textContent = (data.stream_type === 'screen' ? '🖥️ ' : '📹 ') + (data.instructor_name || 'Teacher') + ' is broadcasting live (' + data.stream_type + ')';
                }
                showLiveToast('Instructor broadcast active: ' + (data.stream_type === 'screen' ? 'Screen Share' : 'Webcam'), 'broadcast-tower', 'danger');
            } else {
                if (liveBroadcastBanner) liveBroadcastBanner.style.display = 'none';
                // Switch back to masterclass video
                if (studentCanvas) studentCanvas.classList.add('canvas-hidden');
                if (video) {
                    video.style.display = 'block';
                    if (!video.srcObject) {
                        video.play().catch(e => console.log('Video resume:', e));
                    }
                }
            }
        }
    }

    // Render frame relay on student canvas (guarantees cross-browser visual transmission)
    function renderStudentRelayFrame(base64Data, streamType) {
        if (isHost) return; // Host already sees their own source

        if (studentCanvas && studentCtx) {
            studentCanvas.classList.remove('canvas-hidden');
            if (video) video.style.display = 'none'; // Canvas takes focus

            const img = new Image();
            img.onload = function () {
                studentCtx.clearRect(0, 0, studentCanvas.width, studentCanvas.height);
                studentCtx.drawImage(img, 0, 0, studentCanvas.width, studentCanvas.height);
            };
            img.src = base64Data;

            if (liveBroadcastBanner && liveBroadcastBanner.style.display !== 'flex') {
                liveBroadcastBanner.style.display = 'flex';
                broadcastBannerText.textContent = (streamType === 'screen' ? '🖥️ Screen Share Active' : '📹 Live Camera Feed');
            }
        }
    }

    // Adaptive Server Background Polling for State & Cross-Browser Frame Sync
    let activePollTimer = null;
    let isBroadcastingActive = false;

    async function pollServerSignal() {
        try {
            const res = await fetch(signalGetUrl);
            const data = await res.json();
            if (data.status === 'success') {
                if (data.stream_state) {
                    applyStreamState(data.stream_state);
                    isBroadcastingActive = Boolean(data.stream_state.is_live);
                }

                if (!isHost && data.frame && data.frame.frame) {
                    renderStudentRelayFrame(data.frame.frame, data.frame.type);
                }

                if (isHost && data.raised_hands) {
                    renderTeacherRaisedHands(data.raised_hands);
                }

                // Check WebRTC offers for student
                if (!isHost && data.signals && data.signals.length > 0) {
                    const latestOffer = data.signals.find(s => s.signal_type === 'offer' && s.sender_id !== currentUserId);
                    if (latestOffer && (!peerConnection || peerConnection.signalingState === 'stable')) {
                        handleWebRTCOffer(latestOffer.sdp);
                    }
                }
            }
        } catch (e) {
            // Background polling ignore
        } finally {
            // Adaptive polling: 600ms when teacher is streaming live (fast cross-browser screen sync), 2500ms when idle
            const nextInterval = (!isHost && isBroadcastingActive) ? 600 : 2500;
            if (activePollTimer) clearTimeout(activePollTimer);
            activePollTimer = setTimeout(pollServerSignal, nextInterval);
        }
    }
    // Initial poll
    activePollTimer = setTimeout(pollServerSignal, 1000);

    // =========================================================================
    // 2. GLOBAL WEBRTC ENGINE (PEER-TO-PEER VIDEO TRACK STREAMING)
    // =========================================================================
    async function initWebRTCOffer(stream, streamType) {
        try {
            if (peerConnection) {
                peerConnection.close();
            }
            peerConnection = new RTCPeerConnection(rtcConfig);

            stream.getTracks().forEach(track => {
                peerConnection.addTrack(track, stream);
            });

            peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    const candData = {
                        type: 'webrtc_signal',
                        signal_type: 'candidate',
                        candidate: event.candidate,
                        sender_id: currentUserId
                    };
                    liveChannel.postMessage(candData);
                    fetch(signalSendUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(candData)
                    }).catch(e => {});
                }
            };

            const offer = await peerConnection.createOffer();
            await peerConnection.setLocalDescription(offer);

            const offerData = {
                type: 'webrtc_signal',
                signal_type: 'offer',
                sdp: offer,
                stream_type: streamType,
                sender_id: currentUserId
            };

            liveChannel.postMessage(offerData);
            fetch(signalSendUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(offerData)
            }).catch(e => {});

        } catch (err) {
            console.warn('WebRTC offer error (falling back to 60FPS frame relay):', err);
        }
    }

    async function handleWebRTCSignal(data) {
        if (!data || data.sender_id === currentUserId) return;

        if (data.signal_type === 'offer' && !isHost) {
            await handleWebRTCOffer(data.sdp);
        } else if (data.signal_type === 'answer' && isHost && peerConnection) {
            if (peerConnection.signalingState !== 'stable') {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(data.sdp));
            }
        } else if (data.signal_type === 'candidate' && peerConnection) {
            try {
                await peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
            } catch (e) {}
        }
    }

    async function handleWebRTCOffer(sdp) {
        try {
            if (peerConnection) {
                peerConnection.close();
            }
            peerConnection = new RTCPeerConnection(rtcConfig);

            peerConnection.ontrack = (event) => {
                console.log('⚡ WebRTC remote stream track received from teacher!', event.streams[0]);
                if (video) {
                    video.srcObject = event.streams[0];
                    video.muted = false;
                    video.play().catch(e => console.log('Remote play error:', e));
                    if (studentCanvas) studentCanvas.classList.add('canvas-hidden');
                    video.style.display = 'block';
                }
            };

            peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    const candData = {
                        type: 'webrtc_signal',
                        signal_type: 'candidate',
                        candidate: event.candidate,
                        sender_id: currentUserId
                    };
                    liveChannel.postMessage(candData);
                    fetch(signalSendUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(candData)
                    }).catch(e => {});
                }
            };

            await peerConnection.setRemoteDescription(new RTCSessionDescription(sdp));
            const answer = await peerConnection.createAnswer();
            await peerConnection.setLocalDescription(answer);

            const answerData = {
                type: 'webrtc_signal',
                signal_type: 'answer',
                sdp: answer,
                sender_id: currentUserId
            };

            liveChannel.postMessage(answerData);
            fetch(signalSendUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(answerData)
            }).catch(e => {});

        } catch (err) {
            console.warn('WebRTC answer error (frame relay active):', err);
        }
    }

    // =========================================================================
    // 3. TEACHER / HOST STUDIO BROADCAST CONTROLS (GLOBAL EXPOSURE)
    // =========================================================================
    window.toggleWebcamBroadcast = async function () {
        const btn = document.getElementById('btnToggleCam');
        const text = document.getElementById('camBtnText');

        if (mediaStream && mediaStream._type === 'webcam') {
            window.stopBroadcasting();
            if (text) text.textContent = 'Start Webcam';
            if (btn) btn.classList.remove('btn-danger');
            window.switchStreamSource('cdn1');
        } else {
            try {
                window.stopBroadcasting();
                mediaStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                mediaStream._type = 'webcam';

                video.srcObject = mediaStream;
                video.muted = true; // Prevent feedback loop on host
                await video.play();

                if (text) text.textContent = 'Stop Webcam';
                if (btn) btn.classList.add('btn-danger');
                document.getElementById('streamSourceBadge').textContent = 'Webcam (Live Broadcast)';
                document.getElementById('streamSourceBadge').className = 'badge bg-danger text-white';

                broadcastStreamState('webcam', true, 'Webcam Broadcast (Host)');
                startFrameRelayLoop('webcam');
                initWebRTCOffer(mediaStream, 'webcam');
                showLiveToast('Webcam Broadcast Started Live', 'video', 'success');
            } catch (err) {
                showLiveToast('Camera access cancelled or not granted: ' + err.message, 'video-slash', 'warning');
            }
        }
    };

    window.toggleScreenShareBroadcast = async function () {
        const btn = document.getElementById('btnToggleScreen');
        const text = document.getElementById('screenBtnText');

        if (mediaStream && mediaStream._type === 'screen') {
            window.stopBroadcasting();
            if (text) text.textContent = 'Share Screen';
            if (btn) btn.classList.remove('btn-danger');
            window.switchStreamSource('cdn1');
        } else {
            try {
                window.stopBroadcasting();
                mediaStream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: true });
                mediaStream._type = 'screen';

                video.srcObject = mediaStream;
                await video.play();

                if (text) text.textContent = 'Stop Screen';
                if (btn) btn.classList.add('btn-danger');
                document.getElementById('streamSourceBadge').textContent = 'Screen Share (Live Broadcast)';
                document.getElementById('streamSourceBadge').className = 'badge bg-danger text-white';

                broadcastStreamState('screen', true, 'Instructor Screen Share (Live)');
                startFrameRelayLoop('screen');
                initWebRTCOffer(mediaStream, 'screen');
                showLiveToast('Screen Share Broadcast Started Live', 'desktop', 'info');

                mediaStream.getVideoTracks()[0].onended = function () {
                    window.stopBroadcasting();
                    if (text) text.textContent = 'Share Screen';
                    if (btn) btn.classList.remove('btn-danger');
                    window.switchStreamSource('cdn1');
                };
            } catch (err) {
                showLiveToast('Screen sharing was cancelled. Pick a window or screen to broadcast.', 'info-circle', 'warning');
            }
        }
    };

    let lastServerFrameSent = 0;
    function startFrameRelayLoop(streamType) {
        if (frameRelayInterval) clearInterval(frameRelayInterval);

        frameRelayInterval = setInterval(() => {
            if (!mediaStream || !captureCanvas || !captureCtx) return;

            try {
                captureCanvas.width = 1280;
                captureCanvas.height = 720;
                captureCtx.drawImage(video, 0, 0, captureCanvas.width, captureCanvas.height);
                const frameData = captureCanvas.toDataURL('image/jpeg', 0.55);

                // Instant frame delivery to tabs in the same browser profile
                liveChannel.postMessage({
                    type: 'screen_frame',
                    frame_data: frameData,
                    stream_type: streamType
                });

                // Cross-browser server relay every 250ms (clean 4 FPS without random packet drops)
                const now = Date.now();
                if (now - lastServerFrameSent > 250) {
                    lastServerFrameSent = now;
                    fetch(signalSendUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            frame_data: frameData,
                            stream_type: streamType
                        })
                    }).catch(e => {});
                }
            } catch (e) {}
        }, 100);
    }

    window.stopBroadcasting = function () {
        if (frameRelayInterval) {
            clearInterval(frameRelayInterval);
            frameRelayInterval = null;
        }

        if (mediaStream) {
            mediaStream.getTracks().forEach(t => t.stop());
            mediaStream = null;
        }

        if (video) video.srcObject = null;
        broadcastStreamState('cdn', false, 'CDN Primary Stream');
    };

    function broadcastStreamState(streamType, isLive, label) {
        const payload = {
            type: 'stream_update',
            stream_type: streamType,
            is_live: isLive,
            source_label: label,
            instructor_name: currentUserName
        };

        liveChannel.postMessage(payload);

        fetch(signalSendUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        }).catch(e => console.error('Signal post error:', e));
    }

    window.toggleMic = function () {
        micMuted = !micMuted;
        const text = document.getElementById('micBtnText');
        if (mediaStream) {
            mediaStream.getAudioTracks().forEach(t => t.enabled = !micMuted);
        }
        if (text) text.textContent = micMuted ? 'Unmute Mic' : 'Mute Mic';
        showLiveToast(micMuted ? 'Microphone muted' : 'Microphone active', 'microphone', micMuted ? 'warning' : 'success');
    };

    window.dismissRaisedHand = async function (handId) {
        try {
            const res = await fetch(`/live-classroom/${lectureId}/dismiss-hand/${handId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.status === 'success') {
                renderTeacherRaisedHands(data.raised_hands);
                liveChannel.postMessage({
                    type: 'hand_dismissed',
                    hand_id: handId
                });
            }
        } catch (e) {
            console.error('Dismiss hand error:', e);
        }
    };

    function renderTeacherRaisedHands(hands) {
        const container = document.getElementById('raisedHandsListContainer');
        const badge = document.getElementById('handCountBadge');
        if (!container) return;

        if (badge) badge.textContent = hands.length;

        if (!hands || hands.length === 0) {
            container.innerHTML = '<div class="text-muted small fst-italic py-1" style="font-size: 0.75rem;">No raised hands currently in queue.</div>';
            return;
        }

        container.innerHTML = '';
        hands.forEach(h => {
            const row = document.createElement('div');
            row.className = 'd-flex justify-content-between align-items-center p-2 rounded border border-warning border-opacity-25 mb-1';
            row.style.background = 'var(--bg-card)';
            row.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <span class="text-warning">✋</span>
                    <div>
                        <div class="fw-bold small" style="font-size: 0.78rem;">${escapeHtml(h.user_name)}</div>
                        <div class="text-muted" style="font-size: 0.68rem;">Raised at ${h.raised_at || 'Just now'}</div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-success btn-sm py-0 px-2" style="font-size: 0.7rem;" onclick="window.dismissRaisedHand('${h.id}')">
                    Mark Answered
                </button>
            `;
            container.appendChild(row);
        });
    }

    async function fetchAndRenderRaisedHands() {
        try {
            const res = await fetch(signalGetUrl);
            const data = await res.json();
            if (data.status === 'success' && data.raised_hands) {
                renderTeacherRaisedHands(data.raised_hands);
            }
        } catch (e) {}
    }

    // =========================================================================
    // 4. STUDENT LEARNER ACTIONS (GLOBAL EXPOSURE)
    // =========================================================================
    window.toggleRaiseHand = async function () {
        studentHandRaised = !studentHandRaised;
        const btn = document.getElementById('raiseHandBtn');
        const txt = document.getElementById('raiseHandText');
        const action = studentHandRaised ? 'raise' : 'lower';

        try {
            const res = await fetch(raiseHandUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    action: action,
                    question: 'Student raised hand to ask doubt in live classroom'
                })
            });

            const data = await res.json();
            if (data.status === 'success') {
                if (studentHandRaised) {
                    if (btn) btn.className = 'btn btn-warning btn-sm';
                    if (txt) txt.textContent = '✋ Hand Raised (Teacher Notified)';
                    showLiveToast('Hand raised! Dr. Sarah Jenkins has been notified in studio.', 'hand-paper', 'warning');

                    liveChannel.postMessage({
                        type: 'raise_hand',
                        student: currentUserName,
                        user_id: currentUserId
                    });
                } else {
                    resetStudentRaiseHandButton();
                    showLiveToast('Hand lowered.', 'info-circle', 'info');
                }
            }
        } catch (e) {
            console.error('Raise hand error:', e);
        }
    };

    function resetStudentRaiseHandButton() {
        studentHandRaised = false;
        const btn = document.getElementById('raiseHandBtn');
        const txt = document.getElementById('raiseHandText');
        if (btn) btn.className = 'btn-edu-secondary btn-sm';
        if (txt) txt.textContent = 'Raise Hand (Ask Doubt)';
    }

    window.syncWithTeacherStream = function () {
        pollServerSignal();
        if (video) video.play().catch(e => console.log('Sync play:', e));
        showLiveToast('Re-synchronizing video stream with host...', 'sync-alt', 'info');
    };

    // =========================================================================
    // 5. COMMON UTILITIES & VIDEO CONTROLS (GLOBAL EXPOSURE)
    // =========================================================================
    window.checkMuteState = function () {
        if (video && video.muted) {
            if (unmutePrompt) unmutePrompt.style.display = 'flex';
        } else {
            if (unmutePrompt) unmutePrompt.style.display = 'none';
        }
    };

    window.unmuteLiveStream = function () {
        if (video) {
            video.muted = false;
            video.play().catch(e => console.log('Playback error:', e));
        }
        if (unmutePrompt) unmutePrompt.style.display = 'none';
    };

    window.testAudioTone = function () {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, ctx.currentTime);
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.6);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.6);
            showLiveToast('Audio test tone generated successfully (587Hz)', 'volume-up', 'success');
        } catch (e) {
            console.log('Audio test error:', e);
        }
    };

    window.setStreamQuality = function (q) {
        const badge = document.getElementById('streamQualityBadge');
        if (badge) badge.textContent = q;
        showLiveToast('Stream resolution set to ' + q, 'sliders-h', 'info');
    };

    window.toggleFullScreen = function () {
        const vp = document.getElementById('videoViewport');
        if (!document.fullscreenElement) {
            vp.requestFullscreen().catch(err => alert(err.message));
        } else {
            document.exitFullscreen();
        }
    };

    window.copyClassroomLink = function () {
        navigator.clipboard.writeText(window.location.href);
        alert('Live classroom URL copied to clipboard!');
    };

    // Whiteboard / Stream Source Switcher
    window.switchStreamSource = function (type) {
        if (canvasAnimationId) {
            cancelAnimationFrame(canvasAnimationId);
            canvasAnimationId = null;
        }

        if (studentCanvas) studentCanvas.classList.add('canvas-hidden');
        if (video) video.style.display = 'block';

        if (type === 'simulator') {
            startWhiteboardSimulator();
        } else if (type === 'cdn1') {
            if (video && "{{ $lecture->stream_url ?? '' }}") {
                video.srcObject = null;
                video.src = "{{ $lecture->stream_url ?? '' }}";
                video.play();
            }
            document.getElementById('streamSourceBadge').textContent = 'Stream Primary';
            document.getElementById('streamSourceBadge').className = 'badge bg-success text-white';
            if (isHost) broadcastStreamState('cdn', false, 'Stream Primary');
        } else if (type === 'cdn2') {
            if (video && "{{ $lecture->stream_url ?? '' }}") {
                video.srcObject = null;
                video.src = "{{ $lecture->stream_url ?? '' }}";
                video.play();
            }
            document.getElementById('streamSourceBadge').textContent = 'Stream Mirror';
            document.getElementById('streamSourceBadge').className = 'badge bg-info text-white';
            if (isHost) broadcastStreamState('cdn', false, 'Stream Mirror');
        }
    };

    function startWhiteboardSimulator() {
        const canvas = document.getElementById('whiteboardCanvas');
        const ctx = canvas.getContext('2d');
        let frame = 0;

        function renderFrame() {
            frame++;
            ctx.fillStyle = '#0b0f19';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.strokeStyle = 'rgba(37, 99, 235, 0.08)';
            ctx.lineWidth = 1;
            for (let x = 0; x < canvas.width; x += 40) {
                ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, canvas.height); ctx.stroke();
            }
            for (let y = 0; y < canvas.height; y += 40) {
                ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(canvas.width, y); ctx.stroke();
            }

            ctx.fillStyle = 'rgba(15, 23, 42, 0.95)';
            ctx.fillRect(0, 0, canvas.width, 70);

            ctx.fillStyle = '#ef4444';
            ctx.beginPath(); ctx.arc(35, 35, 7, 0, Math.PI * 2); ctx.fill();

            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 20px "Plus Jakarta Sans", sans-serif';
            ctx.fillText('LIVE STREAM: {{ addslashes($lecture->title) }}', 55, 42);

            ctx.fillStyle = '#10b981';
            ctx.font = '14px monospace';
            ctx.fillText('● 1080p 60FPS | TEACHER WHITEBOARD', canvas.width - 320, 42);

            ctx.fillStyle = 'rgba(30, 41, 59, 0.7)';
            ctx.roundRect ? ctx.roundRect(40, 100, 680, 560, 12) : ctx.fillRect(40, 100, 680, 560);
            ctx.fill();

            ctx.fillStyle = '#1e293b';
            ctx.fillRect(40, 100, 680, 40);
            ctx.fillStyle = '#94a3b8';
            ctx.font = '14px monospace';
            ctx.fillText('📁 distributed_worker_pool.go  •  Dr. Sarah Jenkins', 60, 126);

            const codeLines = [
                'package main',
                '',
                'import (',
                '    "context"',
                '    "fmt"',
                '    "sync"',
                ')',
                '',
                '// Worker pool concurrency with non-blocking buffer',
                'func Worker(ctx context.Context, id int, jobs <-chan int) {',
                '    for job := range jobs {',
                '        fmt.Printf("[Worker %d] Processing batch #%d\\n", id, job)',
                '    }',
                '}',
                '',
                '// Real-time synchronization active: 148 connected students'
            ];

            ctx.font = '15px "Consolas", monospace';
            codeLines.forEach((line, idx) => {
                const y = 175 + (idx * 28);
                ctx.fillStyle = '#64748b';
                ctx.fillText((idx + 1).toString().padStart(2, ' '), 55, y);

                if (line.startsWith('//')) ctx.fillStyle = '#10b981';
                else if (line.startsWith('package') || line.startsWith('import') || line.startsWith('func')) ctx.fillStyle = '#ef4444';
                else if (line.includes('fmt.') || line.includes('"')) ctx.fillStyle = '#38bdf8';
                else ctx.fillStyle = '#f8fafc';

                ctx.fillText(line, 95, y);
            });

            ctx.fillStyle = 'rgba(30, 41, 59, 0.7)';
            ctx.roundRect ? ctx.roundRect(750, 100, 490, 560, 12) : ctx.fillRect(750, 100, 490, 560);
            ctx.fill();

            ctx.fillStyle = '#1e293b';
            ctx.fillRect(750, 100, 490, 40);
            ctx.fillStyle = '#94a3b8';
            ctx.font = '14px monospace';
            ctx.fillText('📊 Architecture Whiteboard: In-Memory Ring Buffer', 770, 126);

            drawWhiteboardNode(ctx, 790, 180, 180, 60, 'Student Requests', '#2563eb');
            drawWhiteboardArrow(ctx, 880, 240, 880, 290);
            drawWhiteboardNode(ctx, 790, 290, 180, 60, 'Go Channel Buffer', '#10b981');
            drawWhiteboardArrow(ctx, 970, 320, 1040, 320);
            drawWhiteboardNode(ctx, 1040, 290, 180, 60, 'Worker Queues', '#f59e0b');
            drawWhiteboardArrow(ctx, 880, 350, 880, 400);
            drawWhiteboardNode(ctx, 790, 400, 180, 60, 'Postgres Shard', '#0284c7');

            ctx.fillStyle = '#ef4444';
            for (let i = 0; i < 30; i++) {
                const waveH = Math.sin((frame * 0.1) + i) * 16 + 20;
                ctx.fillRect(790 + (i * 15), 620 - waveH, 8, waveH);
            }
            ctx.fillStyle = '#94a3b8';
            ctx.font = '12px "Plus Jakarta Sans", sans-serif';
            ctx.fillText('🎙 Mentor Voice Stream Active (48kHz Stereo)', 880, 640);

            canvasAnimationId = requestAnimationFrame(renderFrame);
        }

        renderFrame();

        try {
            const stream = canvas.captureStream(30);
            if (video) {
                video.srcObject = stream;
                video.play().catch(e => console.log('Whiteboard stream error:', e));
            }
            document.getElementById('streamSourceBadge').textContent = 'Live Whiteboard';
            document.getElementById('streamSourceBadge').className = 'badge bg-primary text-white';

            if (isHost) {
                broadcastStreamState('whiteboard', true, 'Interactive Tech Whiteboard');
            }
        } catch (e) {
            console.error('Canvas captureStream error:', e);
        }
    }

    function drawWhiteboardNode(ctx, x, y, w, h, text, color) {
        ctx.fillStyle = 'rgba(15, 23, 42, 0.95)';
        ctx.strokeStyle = color;
        ctx.lineWidth = 2;
        ctx.roundRect ? ctx.roundRect(x, y, w, h, 8) : ctx.fillRect(x, y, w, h);
        ctx.fill(); ctx.stroke();

        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 14px "Plus Jakarta Sans", sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(text, x + (w / 2), y + (h / 2) + 5);
        ctx.textAlign = 'left';
    }

    function drawWhiteboardArrow(ctx, fromX, fromY, toX, toY) {
        ctx.strokeStyle = '#94a3b8'; ctx.lineWidth = 2;
        ctx.beginPath(); ctx.moveTo(fromX, fromY); ctx.lineTo(toX, toY); ctx.stroke();
    }

    // =========================================================================
    // 6. CHAT STREAMING, EMOJIS & TABS (GLOBAL EXPOSURE)
    // =========================================================================
    window.sendLiveChatMessage = async function (e) {
        if (e) e.preventDefault();
        const input = document.getElementById('liveChatInput');
        const text = input ? input.value.trim() : '';
        if (!text) return;
        if (input) input.value = '';

        try {
            const res = await fetch(postMessageUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });

            const data = await res.json();
            if (data.status === 'success') {
                appendChatMessage(data.message);
                liveChannel.postMessage({
                    type: 'chat_message',
                    message: data.message
                });
            }
        } catch (err) {
            console.error('Chat send error:', err);
        }
    };

    function appendChatMessage(msg) {
        const feed = document.getElementById('chatFeed');
        const placeholder = document.getElementById('noChatPlaceholder');
        if (placeholder) placeholder.remove();

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble ' + (msg.is_instructor ? 'is-instructor' : '');
        bubble.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-bold ${msg.is_instructor ? 'text-success' : 'text-primary'}" style="font-size: 0.78rem;">
                    ${msg.is_instructor ? '<i class="fas fa-chalkboard-teacher me-1"></i> ' : ''}${escapeHtml(msg.user_name)}
                </span>
                <span class="text-muted" style="font-size: 0.68rem;">${msg.time || 'Just now'}</span>
            </div>
            <div class="chat-bubble-text">${escapeHtml(msg.comment)}</div>
        `;
        if (feed) {
            feed.appendChild(bubble);
            feed.scrollTop = feed.scrollHeight;
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    let lastMsgCount = {{ $lecture->comments->count() }};
    async function pollLiveClassroomChat() {
        try {
            const res = await fetch(messagesUrl);
            const data = await res.json();
            if (data.status === 'success') {
                if (data.viewers) {
                    const counter = document.getElementById('liveViewerCounter');
                    if (counter) counter.textContent = data.viewers;
                }
                if (data.messages && data.messages.length > lastMsgCount) {
                    const feed = document.getElementById('chatFeed');
                    if (feed) feed.innerHTML = '';
                    data.messages.forEach(m => appendChatMessage(m));
                    lastMsgCount = data.messages.length;
                }
            }
        } catch (e) {}
    }
    setInterval(pollLiveClassroomChat, 3000);

    window.switchClassroomTab = function (tabName) {
        const tabs = ['chat', 'polls', 'code', 'notes'];
        tabs.forEach(t => {
            const btn = document.getElementById('tabBtn' + capitalize(t));
            const content = document.getElementById('tabContent' + capitalize(t));
            if (btn && content) {
                if (t === tabName) {
                    btn.classList.add('active');
                    content.classList.remove('d-none');
                } else {
                    btn.classList.remove('active');
                    content.classList.add('d-none');
                }
            }
        });
    };

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    window.spawnReaction = function (emoji) {
        const container = document.getElementById('flyingEmojisContainer');
        if (!container) return;
        const span = document.createElement('span');
        span.className = 'flying-emoji';
        span.textContent = emoji;
        const randomX = Math.floor(Math.random() * 80) - 40;
        span.style.right = (40 + randomX) + 'px';
        container.appendChild(span);
        setTimeout(() => span.remove(), 2100);
    };

    function showLiveToast(msg, icon = 'info-circle', color = 'info') {
        let box = document.getElementById('liveToastBox');
        if (!box) {
            box = document.createElement('div');
            box.id = 'liveToastBox';
            box.style.cssText = 'position: fixed; bottom: 24px; left: 24px; z-index: 99999; display: flex; flex-direction: column; gap: 8px;';
            document.body.appendChild(box);
        }
        const t = document.createElement('div');
        t.className = 'p-3 shadow-lg rounded-pill small fw-bold d-flex align-items-center gap-2 border border-' + color;
        t.style.cssText = 'background: rgba(15, 23, 42, 0.95); color: #fff;';
        t.innerHTML = `<i class="fas fa-${icon} text-${color}"></i> ${escapeHtml(msg)}`;
        box.appendChild(t);
        setTimeout(() => t.remove(), 4000);
    }

    // =========================================================================
    // 7. POLLS & CODE RUNNER & NOTES (GLOBAL EXPOSURE)
    // =========================================================================
    window.voteLivePoll = async function (optionId) {
        try {
            const res = await fetch("{{ route('live.poll.vote', $lecture->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ option_id: optionId })
            });
            const data = await res.json();
            if (data.status === 'success' && data.poll) {
                renderPoll(data.poll);
            }
        } catch (e) {
            console.error('Vote error:', e);
        }
    };

    function renderPoll(poll) {
        const qEl = document.getElementById('pollQuestionText');
        const vEl = document.getElementById('pollTotalVotesCount');
        const container = document.getElementById('pollOptionsContainer');
        if (qEl) qEl.textContent = poll.question;
        if (vEl) vEl.textContent = poll.total_votes;
        if (!container) return;

        container.innerHTML = '';
        poll.options.forEach(opt => {
            const pct = poll.total_votes > 0 ? Math.round((opt.votes / poll.total_votes) * 100) : 0;
            const div = document.createElement('div');
            div.className = 'p-2 rounded cursor-pointer border position-relative overflow-hidden poll-option-row';
            div.style.background = 'var(--bg-card)';
            div.onclick = () => window.voteLivePoll(opt.id);
            div.innerHTML = `
                <div class="poll-progress-fill" style="position: absolute; left: 0; top: 0; bottom: 0; width: ${pct}%; background: rgba(37, 99, 235, 0.15); transition: width 0.4s ease; pointer-events: none;"></div>
                <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                    <span class="small fw-semibold">${escapeHtml(opt.text)}</span>
                    <span class="text-primary small fw-bold">${pct}% (${opt.votes})</span>
                </div>
            `;
            container.appendChild(div);
        });
    }

    window.submitNewPoll = async function (e) {
        if (e) e.preventDefault();
        const question = document.getElementById('pollQuestionInput').value;
        const opts = [];
        ['pollOpt1', 'pollOpt2', 'pollOpt3', 'pollOpt4'].forEach(id => {
            const el = document.getElementById(id);
            const val = el ? el.value.trim() : '';
            if (val) opts.push(val);
        });

        try {
            const res = await fetch("{{ route('live.poll.create', $lecture->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ question: question, options: opts })
            });
            const data = await res.json();
            if (data.status === 'success' && data.poll) {
                renderPoll(data.poll);
                const modalEl = document.getElementById('newPollModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
                window.switchClassroomTab('polls');
                showLiveToast('Pop quiz published to cohort!', 'poll-h', 'success');
            } else if (data.error) {
                alert(data.error);
            }
        } catch (err) {
            alert('Failed to launch poll: ' + err.message);
        }
    };

    window.runScratchpadCode = function () {
        const code = document.getElementById('liveCodeEditor').value;
        const consoleEl = document.getElementById('codeOutputConsole');
        consoleEl.textContent = '> Executing sandbox script...\n';

        try {
            let output = [];
            const originalLog = console.log;
            console.log = function(...args) {
                output.push(args.map(a => typeof a === 'object' ? JSON.stringify(a) : a).join(' '));
                originalLog.apply(console, args);
            };

            const result = new Function(code)();
            console.log = originalLog;

            if (output.length > 0) {
                consoleEl.textContent = output.join('\n');
            } else if (result !== undefined) {
                consoleEl.textContent = '> Result: ' + JSON.stringify(result);
            } else {
                consoleEl.textContent = '> Finished with code 0 (no errors).';
            }
        } catch (err) {
            consoleEl.textContent = '❌ Syntax/Runtime Error: ' + err.message;
        }
    };

    const notesKey = 'da_lecture_notes_' + lectureId;
    const notesEl = document.getElementById('studentPersonalNotes');
    if (notesEl) {
        notesEl.value = localStorage.getItem(notesKey) || '';
        notesEl.addEventListener('input', () => {
            localStorage.setItem(notesKey, notesEl.value);
        });
    }

    window.copyMyNotes = function () {
        if (!notesEl) return;
        navigator.clipboard.writeText(notesEl.value);
        alert('Notes copied to clipboard!');
    };

    // =========================================================================
    // 8. DOM CONTENT LOADED BINDINGS (ENSURES BULLETPROOF CLICK HANDLING)
    // =========================================================================
    document.addEventListener('DOMContentLoaded', () => {
        // Autoplay check
        if (video) {
            video.play().catch(() => {
                video.muted = true;
                video.play().catch(e => console.log('Playback deferred:', e));
            });
            window.checkMuteState();
            video.addEventListener('volumechange', window.checkMuteState);
        }

        // Direct Element Click Listeners (Double Guard alongside inline onclick)
        const btnCam = document.getElementById('btnToggleCam');
        if (btnCam) btnCam.addEventListener('click', window.toggleWebcamBroadcast);

        const btnScreen = document.getElementById('btnToggleScreen');
        if (btnScreen) btnScreen.addEventListener('click', window.toggleScreenShareBroadcast);

        const btnMic = document.getElementById('btnToggleMic');
        if (btnMic) btnMic.addEventListener('click', window.toggleMic);

        const btnWhiteboard = document.getElementById('btnToggleWhiteboard');
        if (btnWhiteboard) btnWhiteboard.addEventListener('click', () => window.switchStreamSource('simulator'));

        const btnRaise = document.getElementById('raiseHandBtn');
        if (btnRaise) btnRaise.addEventListener('click', window.toggleRaiseHand);

        // Fetch initial raised hands if host
        if (isHost) {
            fetchAndRenderRaisedHands();
        }
    });
</script>
@endsection
