@extends('frontend.master')

@section('title', 'Verified Student Reviews & Feedback – Digital Academy')

@section('content')
<div class="container py-4 py-md-5">

    <!-- Header Section -->
    <div class="text-center mb-5 mx-auto" style="max-width: 720px;">
        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 px-3 py-1 rounded-pill small fw-bold mb-2">
            <i class="fas fa-star text-warning me-1"></i> VERIFIED LEARNER REPUTATION
        </span>
        <h1 class="edu-section-title mb-2">Student Reviews & Community Feedback</h1>
        <p class="edu-section-sub mb-4">
            Read honest feedback from over 2,400+ developers, architects, and designers who mastered skills through Digital Academy cohorts.
        </p>

        <!-- Rating Highlight Stats -->
        <div class="d-inline-flex flex-wrap align-items-center justify-content-center gap-3 p-3 rounded-4 edu-card-panel mt-2 shadow-sm">
            <div class="d-flex align-items-center gap-2">
                <span class="display-6 fw-bold text-warning">{{ $averageRating }}</span>
                <div>
                    <div class="text-warning small">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= round($averageRating) ? '' : '-half-alt' }}"></i>
                        @endfor
                    </div>
                    <div class="text-muted small" style="font-size: 0.75rem;">Platform Average Rating</div>
                </div>
            </div>
            <div class="border-start ps-3 d-none d-sm-block" style="border-color: var(--border-color) !important;">
                <div class="fw-bold fs-5">{{ number_format($totalReviews) }}</div>
                <div class="text-muted small" style="font-size: 0.75rem;">Verified Reviews</div>
            </div>
        </div>
    </div>

    <!-- Review Form Card (If authenticated student) -->
    <div class="edu-card-panel p-4 p-md-5 mb-5 shadow-lg mx-auto" style="max-width: 820px; border-top: 3px solid var(--primary);">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom" style="border-color: var(--border-color) !important;">
            <div>
                <h4 class="fw-bold mb-1 fs-5"><i class="fas fa-pen-nib text-primary me-2"></i> Leave a Verified Course Review</h4>
                <p class="text-muted small mb-0">Share your learning experience to help other students choose their next career cohort.</p>
            </div>
            <span class="badge bg-success bg-opacity-10 text-success small">Verified Enrollment</span>
        </div>

        @auth
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-7">
                        <label class="form-label text-muted small fw-bold mb-1">Select Course:</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">-- Choose Course You Enrolled In --</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-muted small fw-bold mb-1">Rating:</label>
                        <select name="rating" class="form-select text-warning fw-bold" required>
                            <option value="5">⭐⭐⭐⭐⭐ (5 Stars - Outstanding)</option>
                            <option value="4">⭐⭐⭐⭐ (4 Stars - Great Content)</option>
                            <option value="3">⭐⭐⭐ (3 Stars - Satisfactory)</option>
                            <option value="2">⭐⭐ (2 Stars - Needs Improvement)</option>
                            <option value="1">⭐ (1 Star - Poor)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold mb-1">Your Review & Practical Outcomes:</label>
                    <textarea name="review" rows="3" class="form-control" placeholder="What concepts did you master? How did the instructor's live sessions and hands-on coding labs help your career?" required maxlength="500"></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small" style="font-size: 0.75rem;">
                        <i class="fas fa-shield-alt text-success me-1"></i> Your review will display with a Verified Learner badge.
                    </div>
                    <button type="submit" class="btn-edu-primary btn-sm px-4 py-2">
                        <i class="fas fa-paper-plane me-2"></i> Submit Review
                    </button>
                </div>
            </form>
        @else
            <div class="p-4 text-center rounded-3" style="background: var(--bg-secondary);">
                <i class="fas fa-user-lock fa-2x text-muted mb-2"></i>
                <p class="text-muted small mb-3">Please log in to your student account to submit a review for your courses.</p>
                <a href="{{ route('login') }}" class="btn-edu-primary btn-sm px-4">Sign In to Leave Feedback</a>
            </div>
        @endauth
    </div>

    <!-- Reviews Grid -->
    <div class="row g-4 mb-5">
        @forelse($reviews as $review)
            <div class="col-md-6 col-lg-4">
                <div class="edu-card-panel h-100 p-4 d-flex flex-column justify-content-between shadow-sm">
                    <div>
                        <!-- Author & Rating Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $review->user->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100' }}" alt="{{ $review->user->name ?? 'Learner' }}" class="rounded-circle border border-primary border-opacity-30" style="width: 44px; height: 44px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold small">{{ $review->user->name ?? 'Verified Student' }}</div>
                                    <span class="badge bg-success bg-opacity-15 text-success small" style="font-size: 0.65rem;">
                                        <i class="fas fa-check-circle me-1"></i> Verified Student
                                    </span>
                                </div>
                            </div>
                            <div class="text-warning small text-nowrap">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : ' text-secondary text-opacity-30' }}"></i>
                                @endfor
                            </div>
                        </div>

                        <!-- Course Tag -->
                        @if($review->course)
                            <div class="mb-2">
                                <a href="{{ route('courses.detail', $review->course->id) }}" class="badge bg-primary bg-opacity-10 text-primary text-decoration-none small text-truncate d-inline-block" style="max-width: 100%;">
                                    <i class="fas fa-book-open me-1"></i> {{ $review->course->name }}
                                </a>
                            </div>
                        @endif

                        <!-- Review Text -->
                        <p class="text-muted small mb-3" style="line-height: 1.6;">
                            "{{ $review->review }}"
                        </p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top text-muted small" style="border-color: var(--border-color) !important; font-size: 0.72rem;">
                        <span><i class="far fa-calendar-alt me-1"></i> {{ $review->created_at ? $review->created_at->diffForHumans() : 'Recently' }}</span>
                        @if(Auth::check() && (Auth::user()->isAdmin() || Auth::id() === $review->user_id))
                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Delete this review?');" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0 small text-decoration-none" style="font-size: 0.72rem;">
                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="edu-card-panel py-5 max-w-500 mx-auto" style="max-width: 480px;">
                    <i class="far fa-comment-dots fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="fw-bold mb-2">No reviews yet</h5>
                    <p class="text-muted small mb-0">Be the first student to review a course and share your learning milestones!</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $reviews->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
