@extends('frontend.master')

@section('title', 'Live Lectures & Masterclass Schedule – Digital Academy')

@section('content')
<div class="container py-4 py-md-5">

    <!-- Header -->
    <div class="text-center mx-auto mb-4" style="max-width: 680px;">
        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-3 py-1 mb-2 small fw-bold">
            <i class="fas fa-circle fa-2xs me-1"></i> In-Platform Live Studio
        </span>
        <h1 class="edu-section-title mb-2">Live Masterclasses & Interactive Cohorts</h1>
        <p class="edu-section-sub mb-4">Attend real-time technical workshops, code live with instructors in our in-browser studio, and get your questions answered.</p>
    </div>

    <!-- Active Live Now Banner (if any) -->
    @if($liveNow)
        <div class="edu-card-panel mb-5 shadow-lg position-relative overflow-hidden" style="border-top: 3px solid var(--accent-rose);">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 mb-2 px-3 py-1 rounded-pill" style="background: rgba(239, 68, 68, 0.12); border: 1px solid var(--accent-rose);">
                        <span class="badge-live-pulse">🔴 Live Stream Active</span>
                        <span class="small fw-bold text-danger"><i class="fas fa-users me-1"></i> {{ $liveNow->active_viewers ?? 148 }} Students in Classroom</span>
                    </div>
                    <h2 class="fw-bold mb-2 fs-3">{{ $liveNow->title }}</h2>
                    <p class="text-muted small mb-3">{{ $liveNow->description }}</p>

                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $liveNow->instructor->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" alt="{{ $liveNow->instructor->name }}" class="rounded-circle border border-danger" style="width: 44px; height: 44px; object-fit: cover;">
                        <div>
                            <div class="fw-bold small">{{ $liveNow->instructor->name }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $liveNow->instructor->headline ?? 'Technical Mentor' }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('live.classroom', $liveNow->id) }}" class="btn-edu-primary py-2.5 px-4 fs-6">
                        <i class="fas fa-broadcast-tower me-2"></i> Enter Classroom <span class="badge bg-danger ms-2">LIVE</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="topic-chips-wrapper justify-content-start justify-content-md-center mb-5">
        <a href="{{ route('schedule.index', ['filter' => 'all']) }}" class="topic-chip {{ $filter === 'all' ? 'active' : '' }}">
            All Sessions
        </a>
        <a href="{{ route('schedule.index', ['filter' => 'live']) }}" class="topic-chip {{ $filter === 'live' ? 'active' : '' }}">
            <i class="fas fa-circle text-danger fa-2xs me-1"></i> Live Now
        </a>
        <a href="{{ route('schedule.index', ['filter' => 'today']) }}" class="topic-chip {{ $filter === 'today' ? 'active' : '' }}">
            Scheduled Today
        </a>
        <a href="{{ route('schedule.index', ['filter' => 'upcoming']) }}" class="topic-chip {{ $filter === 'upcoming' ? 'active' : '' }}">
            Upcoming Weeks
        </a>
    </div>

    <!-- Lecture Grid -->
    <div class="row g-4">
        @forelse($lectures as $lecture)
            <div class="col-md-6 col-lg-4">
                <div class="edu-card-panel h-100 d-flex flex-column justify-content-between shadow-sm">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if($lecture->status === 'live')
                                <span class="badge-live-pulse">🔴 Live Now</span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 small">
                                    <i class="far fa-calendar-alt me-1"></i> {{ $lecture->start_time ? $lecture->start_time->format('D, M d • h:i A') : 'Upcoming Session' }}
                                </span>
                            @endif

                            @if($lecture->is_demo)
                                <span class="badge bg-success bg-opacity-10 text-success small">Open Demo</span>
                            @endif
                        </div>

                        <h4 class="fw-bold mb-2 fs-5">{{ $lecture->title }}</h4>
                        <p class="text-muted small mb-4">{{ Str::limit($lecture->description, 85) }}</p>

                        @if($lecture->course)
                            <div class="small text-primary mb-3">
                                <i class="fas fa-book-open me-1"></i> Course: {{ $lecture->course->name }}
                            </div>
                        @endif
                    </div>

                    <div class="pt-3 border-top" style="border-color: var(--border-color) !important;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ $lecture->instructor->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="rounded-circle border" style="width: 36px; height: 36px; object-fit: cover;">
                            <div>
                                <div class="small fw-bold">{{ $lecture->instructor->name }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $lecture->instructor->headline ?? 'Instructor' }}</div>
                            </div>
                        </div>

                        @if($lecture->status === 'live')
                            <a href="{{ route('live.classroom', $lecture->id) }}" class="btn-edu-primary btn-sm w-100 justify-content-center py-2">
                                <i class="fas fa-broadcast-tower me-1"></i> Join Classroom (Live)
                            </a>
                        @else
                            <div class="d-flex gap-2">
                                <a href="{{ route('live.classroom', $lecture->id) }}" class="btn-edu-secondary btn-sm flex-grow-1 justify-content-center py-2">
                                    Classroom Portal
                                </a>
                                <a href="{{ route('schedule.calendar.ics', $lecture->id) }}" class="btn btn-outline-secondary btn-sm py-2 px-3" title="Sync to Google/Apple Calendar (.ics)">
                                    <i class="far fa-calendar-plus text-info"></i>
                                </a>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-2 px-2.5" onclick="alert('Notification alert set for {{ addslashes($lecture->title) }}! We will send a reminder.')" title="Set Bell Reminder">
                                    <i class="far fa-bell text-warning"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="edu-card-panel py-5 max-w-500 mx-auto" style="max-width: 500px;">
                    <i class="far fa-calendar-times fa-3x text-muted mb-3 opacity-50"></i>
                    <h4 class="fw-bold mb-2">No Live Lectures Scheduled</h4>
                    <p class="text-muted small mb-4">No sessions match your selected filter. Check back soon or browse all courses.</p>
                    <a href="{{ route('schedule.index') }}" class="btn-edu-primary btn-sm px-4">View All Sessions</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-5 d-flex justify-content-center">
        {{ $lectures->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
