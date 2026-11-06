@extends('frontend.master')

@section('title', 'Student Learning Dashboard – Digital Academy')

@section('content')
<div class="container py-4 py-md-5">

    <!-- Top Student Profile Banner -->
    <div class="edu-card-panel mb-4 shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-md-8 d-flex align-items-center gap-3 gap-md-4">
                <img src="{{ $user->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=400&auto=format&fit=crop&q=80' }}" 
                     alt="{{ $user->name }}" 
                     class="rounded-circle border border-2 border-primary" 
                     style="width: 72px; height: 72px; object-fit: cover;">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-2 py-1 rounded-pill small fw-semibold">
                            🎓 Student Portal
                        </span>
                        @if($user->isInstructor())
                            <a href="{{ route('instructor.dashboard') }}" class="badge bg-success bg-opacity-15 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill small fw-semibold text-decoration-none">
                                <i class="fas fa-chalkboard-teacher me-1"></i> Switch to Instructor Studio
                            </a>
                        @else
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2 py-1 rounded-pill small fw-semibold">
                                <i class="fas fa-check-circle me-1"></i> Direct Learner Account
                            </span>
                        @endif
                    </div>
                    <h2 class="fw-bold mb-1 fs-4">{{ $user->name }}</h2>
                    <p class="text-muted small mb-0">
                        <i class="far fa-envelope me-1"></i> {{ $user->email }} • 
                        Member since {{ $user->created_at ? $user->created_at->format('M Y') : '2026' }}
                    </p>
                </div>
            </div>

            <div class="col-md-5 text-md-end">
                <div class="d-inline-flex flex-column align-items-md-end gap-2 w-100" style="max-width: 340px;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge border px-3 py-1.5 rounded-pill fw-bold small" style="background: rgba(255, 255, 255, 0.05); color: {{ $stats['tier_color'] }}; border-color: {{ $stats['tier_color'] }} !important;">
                            <i class="fas {{ $stats['tier_icon'] }} me-1"></i> Level {{ $stats['mastery_level'] }} • {{ $stats['mastery_tier'] }}
                        </span>
                        <span class="badge bg-warning bg-opacity-15 text-warning border border-warning border-opacity-30 px-3 py-1.5 rounded-pill fw-bold small">
                            🔥 {{ $stats['study_streak_days'] }} Day Streak
                        </span>
                    </div>

                    <div class="w-100 mt-1">
                        <div class="d-flex justify-content-between text-muted small mb-1" style="font-size: 0.72rem;">
                            <span><strong>{{ $stats['total_xp'] }} XP</strong> earned</span>
                            <span>{{ $stats['next_tier_label'] }}</span>
                        </div>
                        <div class="progress" style="height: 6px; background: rgba(148, 163, 184, 0.2); border-radius: 999px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $stats['tier_progress'] }}%; background: {{ $stats['tier_color'] }}; border-radius: 999px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Learning Stats -->
    <div class="row g-3 g-md-4 mb-5">
        <div class="col-6 col-lg-3">
            <div class="edu-stat-card">
                <div class="edu-stat-icon primary">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div>
                    <div class="edu-stat-val">{{ $stats['total_courses'] }}</div>
                    <div class="edu-stat-label">Enrolled Courses</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="edu-stat-card">
                <div class="edu-stat-icon warning">
                    <i class="fas fa-spinner"></i>
                </div>
                <div>
                    <div class="edu-stat-val">{{ $stats['in_progress'] }}</div>
                    <div class="edu-stat-label">In Progress</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="edu-stat-card">
                <div class="edu-stat-icon success">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <div class="edu-stat-val">{{ $stats['completed'] }}</div>
                    <div class="edu-stat-label">Certificates</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="edu-stat-card">
                <div class="edu-stat-icon info">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
                <div>
                    <div class="edu-stat-val">{{ $liveClasses->count() }}</div>
                    <div class="edu-stat-label">Live Sessions</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gamification: Unlocked Achievement Badges -->
    <div class="edu-card-panel p-4 mb-5 shadow-sm border" style="background: var(--bg-card); border-color: var(--border-color) !important;">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom border-secondary border-opacity-15">
            <div>
                <h4 class="fw-bold mb-0 fs-6 d-flex align-items-center gap-2">
                    <span style="color: #ffd700;"><i class="fas fa-medal"></i></span>
                    Engineering Mastery Badges & Quests
                </h4>
                <p class="text-muted small mb-0" style="font-size: 0.78rem;">Complete challenges, test code algorithms, and pass certification exams to unlock career trophies.</p>
            </div>
            <span class="badge bg-primary bg-opacity-15 text-primary border border-primary border-opacity-25 px-2.5 py-1 rounded-pill small">
                {{ collect($badges)->where('unlocked', true)->count() }} / {{ count($badges) }} Unlocked
            </span>
        </div>

        <div class="row g-3">
            @foreach($badges as $badge)
                <div class="col-6 col-md-4 col-lg">
                    <div class="p-3 rounded text-center h-100 d-flex flex-column align-items-center justify-content-between transition-all" 
                         style="background: {{ $badge['unlocked'] ? 'rgba(79, 70, 229, 0.08)' : 'rgba(148, 163, 184, 0.05)' }}; border: 1px solid {{ $badge['unlocked'] ? $badge['color'] . '44' : 'var(--border-color)' }}; cursor: {{ $badge['unlocked'] ? 'pointer' : 'default' }};"
                         @if($badge['unlocked']) onclick="window.Dopamine && window.Dopamine.showAchievementModal('{{ $badge['title'] }}', '{{ $badge['description'] }}', '{{ $badge['icon'] }}')" title="Click to inspect badge" @endif>
                        <div>
                            <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center position-relative" 
                                 style="width: 48px; height: 48px; background: {{ $badge['unlocked'] ? $badge['color'] . '22' : 'rgba(148, 163, 184, 0.15)' }}; color: {{ $badge['unlocked'] ? $badge['color'] : '#94a3b8' }}; font-size: 1.3rem; border: 1px solid {{ $badge['unlocked'] ? $badge['color'] . '66' : 'transparent' }};">
                                <i class="{{ $badge['icon'] }}"></i>
                                @if($badge['unlocked'])
                                    <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 14px; height: 14px; display: flex; align-items: center; justify-content: center; font-size: 0.5rem; color: #fff;"><i class="fas fa-check"></i></span>
                                @endif
                            </div>
                            <h6 class="fw-bold mb-1 small text-truncate {{ $badge['unlocked'] ? 'text-white' : 'text-muted' }}" style="font-size: 0.8rem;">{{ $badge['title'] }}</h6>
                            <p class="text-muted small mb-2 d-none d-sm-block" style="font-size: 0.68rem; line-height: 1.3;">{{ $badge['description'] }}</p>
                        </div>
                        <div>
                            @if($badge['unlocked'])
                                <span class="badge bg-success bg-opacity-20 text-success fw-bold" style="font-size: 0.65rem;">UNLOCKED</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-20 text-muted" style="font-size: 0.65rem;"><i class="fas fa-lock me-1"></i> LOCKED</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Section 1: My Enrolled Courses -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 fs-5">📚 My Enrolled Courses</h3>
            <p class="text-muted small mb-0">Pick up where you left off. Watch video lessons, download guides, and earn certificates.</p>
        </div>
        <a href="{{ route('courses.catalog') }}" class="btn-edu-secondary btn-sm px-3">
            <i class="fas fa-plus me-1"></i> Browse Catalog
        </a>
    </div>

    @if($enrollments->count() > 0)
        <div class="row g-4 mb-5">
            @foreach($enrollments as $enrollment)
                @php 
                    $course = $enrollment->course;
                    $progress = $enrollment->progress ?? ['percent' => 0, 'completed' => 0, 'total' => 6];
                @endphp
                @if($course)
                    <div class="col-md-6 col-lg-4">
                        <div class="edu-course-card">
                            <div class="edu-card-thumb-wrap">
                                <img src="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800' }}" 
                                     alt="{{ $course->name }}" 
                                     class="edu-card-thumb-img">
                                
                                <span class="edu-level-badge">{{ $course->level }}</span>

                                <div class="position-absolute bottom-0 end-0 m-2">
                                    @if($progress['percent'] >= 100)
                                        <span class="badge bg-success shadow-sm"><i class="fas fa-check-circle me-1"></i> Completed</span>
                                    @else
                                        <span class="badge bg-primary shadow-sm"><i class="fas fa-play-circle me-1"></i> In Progress</span>
                                    @endif
                                </div>
                            </div>

                            <div class="edu-card-body">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <img src="{{ $course->instructor->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" 
                                             alt="{{ $course->instructor->name ?? 'Instructor' }}" 
                                             class="rounded-circle" 
                                             style="width: 24px; height: 24px; object-fit: cover;">
                                        <span class="text-muted small fw-medium">{{ $course->instructor->name ?? 'Digital Academy Mentor' }}</span>
                                    </div>

                                    <h4 class="edu-card-title">
                                        <a href="{{ route('courses.detail', $course->id) }}" class="text-decoration-none" style="color: inherit;">
                                            {{ $course->name }}
                                        </a>
                                    </h4>

                                    <!-- Progress Bar -->
                                    <div class="my-3">
                                        <div class="d-flex justify-content-between text-muted small mb-1">
                                            <span>Progress</span>
                                            <span class="fw-bold text-primary">{{ $progress['percent'] }}%</span>
                                        </div>
                                        <div class="progress" style="height: 6px; border-radius: 999px; background: var(--bg-secondary);">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress['percent'] }}%;"></div>
                                        </div>
                                        <div class="text-muted mt-1" style="font-size: 0.72rem;">
                                            {{ $progress['completed'] }} of {{ $progress['total'] }} modules completed
                                        </div>
                                    </div>
                                </div>

                                <div class="edu-card-footer pt-3 gap-2">
                                    <a href="{{ route('frontend.materials', ['id' => $course->id]) }}" class="btn-edu-primary btn-sm flex-grow-1">
                                        <i class="fas fa-play me-1"></i> Continue
                                    </a>

                                    <a href="{{ route('courses.quiz.show', $course->id) }}" class="btn-edu-secondary btn-sm px-3" title="Take Certification Exam">
                                        <i class="fas fa-file-signature text-warning"></i>
                                    </a>

                                    @if($progress['percent'] >= 100)
                                        <a href="{{ route('courses.certificate', $course->id) }}" class="btn-edu-secondary btn-sm px-3" title="View Certificate">
                                            <i class="fas fa-certificate text-success"></i>
                                        </a>
                                    @endif

                                    <a href="{{ route('courses.detail', $course->id) }}" class="btn-edu-secondary btn-sm px-3" title="Course Info">
                                        <i class="fas fa-info-circle text-muted"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="edu-card-panel p-5 text-center mb-5 shadow-sm">
            <div class="mb-3 text-muted display-4"><i class="fas fa-graduation-cap"></i></div>
            <h4 class="fw-bold mb-2">You haven't enrolled in any courses yet</h4>
            <p class="text-muted small max-w-500 mx-auto mb-4" style="max-width: 480px;">
                Explore industry-vetted courses taught by senior engineers. Direct enrollment with lifetime access and zero subscription lock-in.
            </p>
            <a href="{{ route('courses.catalog') }}" class="btn-edu-primary px-4">
                <i class="fas fa-compass me-1"></i> Explore Courses
            </a>
        </div>
    @endif

    <!-- Section 2: Live Masterclasses & Timetable -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 fs-5">🔴 Live Cohorts & Masterclasses</h3>
            <p class="text-muted small mb-0">Join interactive live lectures with real-time video, chat, code runner, and Q&A.</p>
        </div>
        <a href="{{ route('schedule.index') }}" class="btn-edu-secondary btn-sm px-3">
            Full Schedule <i class="fas fa-arrow-right fa-xs ms-1"></i>
        </a>
    </div>

    @if($liveClasses->count() > 0)
        <div class="row g-4 mb-5">
            @foreach($liveClasses as $lecture)
                <div class="col-md-6">
                    <div class="edu-card-panel h-100 d-flex flex-column justify-content-between shadow-sm position-relative overflow-hidden">
                        @if($lecture->status === 'live')
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge-live-pulse">LIVE NOW</span>
                            </div>
                        @endif

                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-secondary bg-opacity-10 text-primary small">
                                    <i class="far fa-clock me-1"></i> {{ $lecture->start_time ? $lecture->start_time->format('h:i A, M d') : 'Scheduled' }}
                                </span>
                                @if($lecture->is_demo)
                                    <span class="badge bg-info bg-opacity-10 text-info small">Open Preview</span>
                                @endif
                            </div>

                            <h4 class="fw-bold mb-2 fs-6">{{ $lecture->title }}</h4>
                            <p class="text-muted small mb-3">
                                {{ Str::limit($lecture->description ?? 'Join our senior engineers for an interactive architecture breakdown and live coding session.', 110) }}
                            </p>

                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img src="{{ $lecture->instructor->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" 
                                     alt="{{ $lecture->instructor->name ?? 'Instructor' }}" 
                                     class="rounded-circle" 
                                     style="width: 28px; height: 28px; object-fit: cover;">
                                <div>
                                    <span class="small fw-bold">{{ $lecture->instructor->name ?? 'Host Instructor' }}</span>
                                    <span class="text-muted small ms-1" style="font-size: 0.72rem;">Senior Tech Lead</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-top d-flex gap-2" style="border-color: var(--border-color) !important;">
                            <a href="{{ route('live.classroom', $lecture->id) }}" class="btn-edu-primary flex-grow-1" style="{{ $lecture->status === 'live' ? 'background: #ef4444;' : '' }}">
                                <i class="fas fa-video me-1"></i> {{ $lecture->status === 'live' ? 'Join Live Stream' : 'Open Classroom Lobby' }}
                            </a>
                            <a href="{{ route('schedule.calendar.ics', $lecture->id) }}" class="btn-edu-secondary px-3" title="Sync to Calendar (.ics)">
                                <i class="far fa-calendar-plus text-primary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="edu-card-panel p-4 text-center mb-5 shadow-sm">
            <p class="text-muted small mb-0">No live sessions scheduled right now. Check the <a href="{{ route('schedule.index') }}" class="text-primary fw-semibold">live timetable</a> for upcoming masterclasses.</p>
        </div>
    @endif

    <!-- Section 3: Technical E-Books Slot -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 fs-5">📖 Digital Library & Technical E-Books</h3>
            <p class="text-muted small mb-0">Download complimentary cheat sheets, system design blueprints, and algorithm guides.</p>
        </div>
        <a href="{{ route('ebooks.index') }}" class="btn-edu-secondary btn-sm px-3">
            Browse All Books <i class="fas fa-arrow-right fa-xs ms-1"></i>
        </a>
    </div>

    <div class="row g-3 g-md-4 mb-4">
        @forelse($ebooks as $book)
            <div class="col-md-6 col-lg-3">
                <div class="edu-card-panel p-3 h-100 d-flex flex-column justify-content-between shadow-sm">
                    <div>
                        <div class="rounded mb-2 overflow-hidden position-relative" style="height: 140px;">
                            <img src="{{ $book->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400' }}" 
                                 alt="{{ $book->title }}" 
                                 class="w-100 h-100" 
                                 style="object-fit: cover;">
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">Free</span>
                        </div>
                        <h5 class="fw-bold mb-1 fs-6 text-truncate" title="{{ $book->title }}">{{ $book->title }}</h5>
                        <p class="text-muted small mb-2" style="font-size: 0.75rem;">By {{ $book->author ?? 'Digital Academy' }}</p>
                    </div>

                    <a href="{{ route('ebooks.download', $book->id) }}" class="btn-edu-secondary btn-sm w-100 mt-2">
                        <i class="fas fa-download me-1 text-primary"></i> Download PDF
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-3">No e-books currently available.</div>
        @endforelse
    </div>

</div>
@endsection
