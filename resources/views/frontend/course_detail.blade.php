@extends('frontend.master')

@section('title', $course->name . ' – Digital Academy')

@section('content')
<div class="container py-4 py-md-5">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('courses.catalog') }}" class="text-decoration-none">Courses</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($course->name, 40) }}</li>
        </ol>
    </nav>

    <div class="row g-4 g-lg-5">
        <!-- Main Course Content (Left Column) -->
        <div class="col-lg-8">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-3 py-1 fw-bold">{{ $course->level }}</span>
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 px-3 py-1">{{ $course->duration }}</span>
                <span class="edu-rating-star small"><i class="fas fa-star me-1"></i>{{ $course->rating ?? '4.9' }} ({{ number_format($course->enrolled_count) }} reviews)</span>
            </div>

            <h1 class="edu-section-title mb-3">{{ $course->name }}</h1>
            <p class="edu-section-sub mb-4">{{ $course->description }}</p>

            <!-- Enrollment Counter Badge -->
            <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-4" style="background: var(--primary-light); border: 1px solid var(--primary-border);">
                <i class="fas fa-user-graduate text-primary"></i>
                <span class="fw-bold small" style="color: var(--primary);">{{ number_format($course->enrolled_count) }} Students Currently Enrolled</span>
            </div>

            <!-- Course Preview Video -->
            <div class="edu-card-panel p-0 overflow-hidden mb-5 position-relative shadow-sm" style="border-radius: var(--radius-lg);">
                <div class="position-relative" style="height: 380px;">
                    <img src="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800' }}" alt="{{ $course->name }}" class="w-100 h-100" style="object-fit: cover;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: rgba(15, 23, 42, 0.45);">
                        <button type="button" class="btn-edu-primary rounded-circle p-0 shadow-lg mb-2" onclick="openDemoModal('{{ addslashes($course->name) }}', '{{ $course->demo_video_url }}', '{{ route('courses.checkout', $course->id) }}')" style="width: 72px; height: 72px; font-size: 1.5rem;">
                            <i class="fas fa-play ms-1"></i>
                        </button>
                        <div class="text-white fw-bold small">Watch Course Preview</div>
                    </div>
                </div>
            </div>

            <!-- What You Will Learn -->
            <div class="edu-card-panel mb-5">
                <h3 class="fw-bold fs-5 mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-check-circle text-success"></i> What You Will Learn
                </h3>
                <div class="row g-3">
                    @php
                        $learnItems = explode("\n", $course->what_you_will_learn ?? "Master foundational concepts to advanced production architecture\nBuild real-world end-to-end applications\nComplete guided capstone projects with instructor review\nEarn verified certificate of completion");
                    @endphp
                    @foreach($learnItems as $item)
                        @if(trim($item))
                            <div class="col-md-6 d-flex align-items-start gap-2">
                                <i class="fas fa-check text-primary mt-1 small"></i>
                                <span class="small text-secondary">{{ trim($item) }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- INSTRUCTOR SPOTLIGHT -->
            @if($course->instructor)
                <div class="edu-card-panel mb-5">
                    <span class="edu-card-category d-block mb-1">Meet Your Instructor</span>
                    <h3 class="fw-bold fs-5 mb-3">Taught by Senior Industry Mentors</h3>

                    <div class="d-flex flex-wrap flex-md-nowrap gap-4 align-items-start">
                        <img src="{{ $course->instructor->avatar ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400' }}" alt="{{ $course->instructor->name }}" class="rounded-4 border" style="width: 100px; height: 100px; object-fit: cover;">
                        <div>
                            <h4 class="fw-bold mb-1 fs-5">{{ $course->instructor->name }}</h4>
                            <div class="text-primary small fw-semibold mb-2">{{ $course->instructor->headline ?? 'Senior Technical Lead & Curriculum Author' }}</div>
                            <p class="text-secondary small mb-3">{{ $course->instructor->bio }}</p>

                            <div class="d-flex flex-wrap gap-4 text-muted small">
                                <div><i class="fas fa-users text-primary me-1"></i> {{ number_format($course->enrolled_count + 850) }} Students</div>
                                <div><i class="fas fa-book text-warning me-1"></i> {{ $course->instructor->taughtCourses->count() ?? 1 }} Courses</div>
                                <div><i class="fas fa-star text-warning me-1"></i> 4.9 Instructor Rating</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Course Curriculum / Syllabus Breakdown -->
            <div class="edu-card-panel mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold fs-5 mb-0 d-flex align-items-center gap-2">
                        <i class="fas fa-layer-group text-primary"></i> Course Curriculum & Modules
                    </h3>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 small">
                        {{ $course->subjects->count() }} Modules
                    </span>
                </div>

                @if($course->subjects->isEmpty())
                    <p class="text-muted small mb-0">Curriculum lessons and coding worksheets are included in the enrolled learning console.</p>
                @else
                    <div class="accordion edu-accordion" id="curriculumAccordion">
                        @foreach($course->subjects as $index => $subject)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $subject->id }}">
                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $subject->id }}">
                                        <span>Module {{ $index + 1 }}: {{ $subject->name }}</span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary ms-auto me-3 small">{{ $subject->materials->count() }} Lessons</span>
                                    </button>
                                </h2>
                                <div id="collapse{{ $subject->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#curriculumAccordion">
                                    <div class="accordion-body">
                                        @forelse($subject->materials as $mat)
                                            <div class="edu-material-row">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($mat->type === 'video')
                                                        <span class="edu-material-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"><i class="fas fa-play"></i></span>
                                                    @elseif($mat->type === 'pdf')
                                                        <span class="edu-material-icon" style="background: rgba(2, 132, 199, 0.1); color: #0284c7;"><i class="fas fa-file-pdf"></i></span>
                                                    @else
                                                        <span class="edu-material-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="fas fa-pen-nib"></i></span>
                                                    @endif
                                                    <span class="fw-medium">{{ $mat->title }}</span>
                                                </div>
                                                <span class="badge bg-secondary bg-opacity-10 text-muted small">{{ ucfirst($mat->type) }}</span>
                                            </div>
                                        @empty
                                            <div class="text-muted small fst-italic py-2">Lessons and project files accessible directly after enrollment.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Live Lectures Attached to this Course -->
            @if($course->liveLectures->isNotEmpty())
                <div class="edu-card-panel mb-5">
                    <h3 class="fw-bold fs-5 mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-broadcast-tower text-danger"></i> Live Interactive Masterclasses
                    </h3>
                    @foreach($course->liveLectures as $lecture)
                        <div class="d-flex flex-wrap justify-content-between align-items-center p-3 rounded mb-2 border" style="background: var(--bg-secondary);">
                            <div>
                                <div class="fw-bold small">{{ $lecture->title }}</div>
                                <div class="text-muted" style="font-size: 0.78rem;">
                                    <i class="far fa-clock me-1"></i> {{ $lecture->start_time ? $lecture->start_time->format('D, M d • h:i A') : 'Upcoming Cohort' }}
                                </div>
                            </div>
                            @if($lecture->status === 'live')
                                <a href="{{ route('live.classroom', $lecture->id) }}" class="btn-edu-primary btn-sm mt-2 mt-sm-0" style="background: #ef4444;">
                                    <i class="fas fa-broadcast-tower me-1"></i> Join Live Classroom
                                </a>
                            @else
                                <div class="d-flex gap-2 mt-2 mt-sm-0">
                                    <a href="{{ route('live.classroom', $lecture->id) }}" class="btn-edu-secondary btn-sm">
                                        <i class="fas fa-door-open me-1"></i> View Room
                                    </a>
                                    <a href="{{ route('schedule.calendar.ics', $lecture->id) }}" class="btn-edu-secondary btn-sm" title="Add to Calendar (.ics)">
                                        <i class="far fa-calendar-plus text-primary"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Lecture Q&A Forum -->
            <div class="edu-card-panel mb-5" id="discussion">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="edu-card-category d-block mb-1">Peer & Mentor Support</span>
                        <h3 class="fw-bold fs-5 mb-0 d-flex align-items-center gap-2">
                            <i class="fas fa-comments text-primary"></i> Course Q&A & Discussion
                        </h3>
                    </div>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 px-3 py-2 rounded-pill small">
                        {{ $course->comments->count() }} {{ Str::plural('Question', $course->comments->count()) }}
                    </span>
                </div>

                <!-- Post Question Form -->
                @auth
                    <div class="p-3 rounded mb-4" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            @if($course->liveLectures->isNotEmpty())
                                <div class="mb-2">
                                    <label class="text-muted small mb-1">Related Lecture (Optional):</label>
                                    <select name="lecture_id" class="form-select form-select-sm" style="max-width: 320px;">
                                        <option value="">-- General Course Discussion --</option>
                                        @foreach($course->liveLectures as $lec)
                                            <option value="{{ $lec->id }}">{{ $lec->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="mb-2">
                                <textarea name="comment" rows="3" class="form-control" placeholder="Ask a question about this course or request clarification from the instructor..." required maxlength="1000"></textarea>
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-link btn-sm text-primary p-0 text-decoration-none" onclick="document.getElementById('codeSnippetBox').classList.toggle('d-none')">
                                    <i class="fas fa-code me-1"></i> + Attach Code Snippet or Error Logs
                                </button>
                            </div>
                            <div id="codeSnippetBox" class="mb-3 d-none">
                                <textarea name="code_snippet" rows="3" class="form-control form-control-sm font-monospace" placeholder="// Paste your code or traceback here..." style="font-size: 0.82rem; background: #0b0f19; color: #38bdf8;"></textarea>
                            </div>
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <span class="text-muted small" style="font-size: 0.78rem;">
                                    <i class="fas fa-info-circle me-1 text-primary"></i> Verified instructors reply directly with solutions.
                                </span>
                                <button type="submit" class="btn-edu-primary btn-sm">
                                    <i class="fas fa-paper-plane me-1"></i> Post Question
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-4 rounded text-center mb-4" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                        <i class="fas fa-lock fa-2x text-muted mb-2"></i>
                        <h4 class="fw-bold fs-6 mb-1">Join the Course Discussion</h4>
                        <p class="text-muted small mb-3">Sign in to ask questions, post debugging queries, and collaborate with your cohort.</p>
                        <a href="{{ route('login') }}" class="btn-edu-primary btn-sm px-4">
                            <i class="fas fa-sign-in-alt me-1"></i> Sign In to Join Discussion
                        </a>
                    </div>
                @endauth

                <!-- Comment Threads -->
                @forelse($course->comments as $c)
                    <div class="edu-comment-card {{ $c->is_instructor_reply ? 'instructor-reply' : '' }}" id="comment-{{ $c->id }}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="edu-comment-avatar">
                                    {{ strtoupper(substr($c->user->name ?? 'User', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold small d-flex align-items-center gap-2">
                                        {{ $c->user->name ?? 'Learner' }}
                                        @if($c->is_instructor_reply || ($c->user && $c->user->id === $course->instructor_id))
                                            <span class="edu-instructor-badge">
                                                <i class="fas fa-chalkboard-teacher"></i> Instructor
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-muted" style="font-size: 0.65rem;">Student</span>
                                        @endif
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $c->created_at->diffForHumans() }}
                                        @if($c->lecture)
                                            • <span class="text-primary"><i class="fas fa-video fa-xs me-1"></i>{{ $c->lecture->title }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(Auth::check() && (Auth::id() === $c->user_id || Auth::user()->isAdmin() || Auth::id() === $course->instructor_id))
                                <form action="{{ route('comments.destroy', $c->id) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this question?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0" title="Delete Question" style="font-size: 0.8rem;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <p class="small mb-2 ps-1 text-secondary" style="line-height: 1.55;">
                            {{ $c->comment }}
                        </p>
                        @if($c->code_snippet)
                            <pre class="edu-code-snippet"><code>{{ $c->code_snippet }}</code></pre>
                        @endif

                        <!-- Reply trigger -->
                        @auth
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-link text-primary p-0 text-decoration-none small" onclick="toggleReplyBox('reply-box-{{ $c->id }}')">
                                    <i class="fas fa-reply me-1"></i> {{ (Auth::id() === $course->instructor_id || Auth::user()->isInstructor()) ? 'Reply as Instructor' : 'Reply' }}
                                </button>
                            </div>

                            <!-- Inline Reply Box -->
                            <div id="reply-box-{{ $c->id }}" class="mt-2 p-3 rounded d-none" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                <form action="{{ route('comments.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    <input type="hidden" name="parent_id" value="{{ $c->id }}">
                                    @if($c->lecture_id)
                                        <input type="hidden" name="lecture_id" value="{{ $c->lecture_id }}">
                                    @endif
                                    <div class="mb-2">
                                        <textarea name="comment" rows="2" class="form-control form-control-sm" placeholder="Write your reply..." required maxlength="1000"></textarea>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn-edu-secondary btn-sm py-1 px-3" onclick="toggleReplyBox('reply-box-{{ $c->id }}')">Cancel</button>
                                        <button type="submit" class="btn-edu-primary btn-sm py-1 px-3">
                                            Post Reply
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endauth

                        <!-- Threaded Replies -->
                        @if($c->replies && $c->replies->isNotEmpty())
                            <div class="mt-3 ps-3 border-start border-2 border-primary border-opacity-25">
                                @foreach($c->replies as $reply)
                                    <div class="p-3 rounded mb-2" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="edu-comment-avatar" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                                    {{ strtoupper(substr($reply->user->name ?? 'User', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold small d-flex align-items-center gap-2">
                                                        {{ $reply->user->name ?? 'Learner' }}
                                                        @if($reply->is_instructor_reply || ($reply->user && $reply->user->id === $course->instructor_id))
                                                            <span class="edu-instructor-badge" style="font-size: 0.65rem;">
                                                                <i class="fas fa-chalkboard-teacher"></i> Instructor
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="text-muted" style="font-size: 0.7rem;">{{ $reply->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>

                                            @if(Auth::check() && (Auth::id() === $reply->user_id || Auth::user()->isAdmin() || Auth::id() === $course->instructor_id))
                                                <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" class="m-0" onsubmit="return confirm('Delete reply?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0" title="Delete Reply" style="font-size: 0.75rem;">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <p class="small mb-0 ps-1 text-secondary" style="font-size: 0.85rem; line-height: 1.5;">
                                            {{ $reply->comment }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        <i class="far fa-comment-dots fa-2x mb-2 d-block text-secondary"></i>
                        No questions posted yet for this course. Be the first to start the discussion!
                    </div>
                @endforelse
            </div>

        </div>

        <!-- Sticky Enrollment Card (Right Column) -->
        <div class="col-lg-4">
            <div class="edu-card-panel sticky-top" style="top: 90px;">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Full Course Price</div>
                        <div class="edu-card-price display-6 fw-bold" data-price-usd="{{ $course->price }}">
                            ${{ number_format($course->price, 0) }}
                        </div>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fw-bold px-2 py-1">
                        <i class="fas fa-bolt me-1"></i> Lifetime Access
                    </span>
                </div>

                <p class="text-muted small mb-4">
                    One-time payment. No subscription traps. Includes full syllabus, hands-on tasks, live interactive classes, and verified certificate.
                </p>

                <!-- Primary Enrollment Action -->
                @if($isEnrolled)
                    <div class="alert alert-success border-0 small text-center mb-3 py-2" style="background: rgba(16, 185, 129, 0.12); color: var(--accent-emerald);">
                        <i class="fas fa-check-circle me-1"></i> You are enrolled in this course!
                    </div>
                    <a href="{{ route('frontend.materials', ['id' => $course->id]) }}" class="btn-edu-primary w-100 justify-content-center py-3 mb-3">
                        <i class="fas fa-book-open me-2"></i> Go to Learning Console
                    </a>
                @else
                    <a href="{{ route('courses.checkout', $course->id) }}" class="btn-edu-primary w-100 justify-content-center py-3 mb-3 fs-6">
                        <i class="fas fa-lock me-2"></i> Enroll Now (<span data-price-usd="{{ $course->price }}">${{ number_format($course->price, 0) }}</span>)
                    </a>
                @endif

                <button type="button" class="btn-edu-secondary w-100 justify-content-center mb-4" onclick="openDemoModal('{{ addslashes($course->name) }}', '{{ $course->demo_video_url }}', '{{ route('courses.checkout', $course->id) }}')">
                    <i class="fas fa-play me-2"></i> Watch Course Preview
                </button>

                <div class="border-top pt-3" style="border-color: var(--border-color) !important;">
                    <div class="fw-bold small mb-2 text-uppercase letter-spacing-1" style="font-size: 0.75rem; color: var(--text-muted);">This Course Includes:</div>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li class="mb-2"><i class="fas fa-video text-primary me-2"></i> Full HD structured video modules</li>
                        <li class="mb-2"><i class="fas fa-broadcast-tower text-danger me-2"></i> Live weekly cohort masterclasses</li>
                        <li class="mb-2"><i class="fas fa-file-pdf text-info me-2"></i> Downloadable notes & architecture blueprints</li>
                        <li class="mb-2"><i class="fas fa-comments text-success me-2"></i> Direct instructor Q&A verification</li>
                        <li class="mb-2"><i class="fas fa-certificate text-warning me-2"></i> Accredited completion certificate</li>
                        <li class="mb-0"><i class="fas fa-shield-alt text-secondary me-2"></i> 100% Anti-Fraud Escrow protected</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Floating Bottom Bar for Instant Enrollment -->
<div class="edu-mobile-enroll-bar">
    <div>
        <div class="text-muted" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase;">Total</div>
        <div class="fw-bold fs-5" data-price-usd="{{ $course->price }}">${{ number_format($course->price, 0) }}</div>
    </div>
    @if($isEnrolled)
        <a href="{{ route('frontend.materials', ['id' => $course->id]) }}" class="btn-edu-primary btn-sm px-4">
            <i class="fas fa-book-open me-1"></i> Open Console
        </a>
    @else
        <a href="{{ route('courses.checkout', $course->id) }}" class="btn-edu-primary btn-sm px-4">
            <i class="fas fa-lock me-1"></i> Enroll Now
        </a>
    @endif
</div>

<script>
    function toggleReplyBox(boxId) {
        const box = document.getElementById(boxId);
        if (box) {
            box.classList.toggle('d-none');
        }
    }
</script>
@endsection
