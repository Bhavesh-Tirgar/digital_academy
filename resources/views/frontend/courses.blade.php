@extends('frontend.master')

@section('title', 'Explore All Courses – Digital Academy')

@section('content')
<div class="container py-4 py-md-5">

    <!-- Header & Hero Search -->
    <div class="text-center mx-auto mb-4" style="max-width: 680px;">
        <span class="edu-hero-tag">
            <i class="fas fa-graduation-cap"></i> Industry-Accredited Curriculum
        </span>
        <h1 class="edu-section-title mb-2">Explore All Tech Courses</h1>
        <p class="edu-section-sub mb-4">Master high-demand engineering, AI, and design skills with hands-on projects and live mentor support.</p>

        <!-- Search Bar -->
        <form action="{{ route('courses.catalog') }}" method="GET" class="edu-hero-search mx-auto">
            <i class="fas fa-search me-2 text-muted"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courses, instructors, or technologies...">
            <button type="submit" class="btn-edu-primary btn-sm px-4">Search</button>
        </form>
    </div>

    <!-- Category Filter Chips (Horizontal Swipe on Mobile) -->
    <div class="topic-chips-wrapper justify-content-start justify-content-md-center mb-4">
        <a href="{{ route('courses.catalog', request('search') ? ['search' => request('search')] : []) }}" class="topic-chip {{ !request('category') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> All Courses
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('courses.catalog', array_merge(request('search') ? ['search' => request('search')] : [], ['category' => $cat->slug])) }}" class="topic-chip {{ request('category') === $cat->slug ? 'active' : '' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Results Meta -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom" style="border-color: var(--border-color) !important;">
        <div class="text-muted small">
            Showing <strong class="text-primary">{{ $courses->total() }}</strong> verified tech courses
            @if(request('search'))
                matching "<span class="fw-bold" style="color: var(--text-main);">{{ request('search') }}</span>"
            @endif
        </div>
        @if(request('search') || request('category'))
            <a href="{{ route('courses.catalog') }}" class="btn btn-outline-secondary btn-sm py-0 px-2.5" style="font-size: 0.78rem;">
                <i class="fas fa-times me-1"></i> Clear Filter
            </a>
        @endif
    </div>

    <!-- Course Grid -->
    <div class="row g-4">
        @forelse($courses as $course)
            <div class="col-sm-6 col-lg-4">
                <div class="edu-course-card">
                    <!-- Thumbnail with 16:9 Aspect Ratio -->
                    <div class="edu-card-thumb-wrap">
                        <img src="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800' }}" alt="{{ $course->name }}" class="edu-card-thumb-img">
                        <span class="edu-level-badge">{{ $course->level }}</span>
                        @if($course->enrolled_count > 500)
                            <span class="edu-bestseller-badge">🔥 Bestseller</span>
                        @endif

                        <!-- Watch Preview Trigger -->
                        <button type="button" class="edu-play-preview-btn" onclick="openDemoModal('{{ addslashes($course->name) }}', '{{ $course->demo_video_url }}', '{{ route('courses.checkout', $course->id) }}')">
                            <i class="fas fa-play fa-2xs"></i> Preview
                        </button>
                    </div>

                    <!-- Card Body -->
                    <div class="edu-card-body">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="edu-card-category">{{ $course->category->name ?? 'Computer Science' }}</span>
                                <span class="edu-rating-star small"><i class="fas fa-star me-1"></i>{{ $course->rating ?? '4.9' }}</span>
                            </div>

                            <h3 class="edu-card-title">
                                <a href="{{ route('courses.detail', $course->id) }}" class="text-decoration-none" style="color: inherit;">
                                    {{ $course->name }}
                                </a>
                            </h3>

                            <!-- Instructor Info -->
                            @if($course->instructor)
                                <div class="edu-card-instructor">
                                    <img src="{{ $course->instructor->avatar ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400' }}" alt="{{ $course->instructor->name }}">
                                    <span>{{ $course->instructor->name }}</span>
                                </div>
                            @endif

                            <div class="edu-card-rating">
                                <i class="fas fa-user-graduate text-muted me-1 small"></i>
                                <span class="edu-rating-count">{{ number_format($course->enrolled_count) }} students enrolled</span>
                            </div>
                        </div>

                        <!-- Price & Action Footer -->
                        <div class="edu-card-footer">
                            <div>
                                <div class="d-flex align-items-baseline">
                                    <span class="edu-price-strikethrough" data-price-usd="{{ $course->price * 1.8 }}">${{ number_format($course->price * 1.8, 0) }}</span>
                                    <div class="edu-card-price" data-price-usd="{{ $course->price }}">${{ number_format($course->price, 0) }}</div>
                                </div>
                                <div class="text-success small" style="font-size: 0.68rem; font-weight: 600;">Lifetime Access</div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('courses.detail', $course->id) }}" class="btn-edu-secondary btn-sm px-3">
                                    Details
                                </a>
                                <a href="{{ route('courses.checkout', $course->id) }}" class="btn-edu-primary btn-sm px-3">
                                    Enroll
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="edu-card-panel py-5 max-w-500 mx-auto" style="max-width: 500px;">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="fw-bold mb-2">No courses found</h4>
                    <p class="text-muted small mb-4">Try clearing your search query or selecting a different category.</p>
                    <a href="{{ route('courses.catalog') }}" class="btn-edu-primary btn-sm">Reset Filters</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $courses->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
