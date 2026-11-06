@extends('frontend.master')

@section('title', 'Instructor Dashboard & Revenue – Digital Academy')

@section('content')
<div class="container py-4 py-md-5">

    <!-- Top Welcome & Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 mb-2 fw-semibold">
                <i class="fas fa-chalkboard-teacher me-1"></i> Instructor Console
            </span>
            <h1 class="edu-section-title mb-1">Welcome Back, {{ Auth::user()->name }}</h1>
            <p class="edu-section-sub mb-0">{{ Auth::user()->headline ?? 'Senior Technical Lead & Curriculum Author' }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('student.dashboard') }}" class="btn-edu-secondary">
                <i class="fas fa-user-graduate me-1"></i> Learner Mode
            </a>
            <button type="button" class="btn-edu-primary" data-bs-toggle="modal" data-bs-target="#createCourseModal">
                <i class="fas fa-plus-circle me-1"></i> Create Course
            </button>
            <button type="button" class="btn-edu-secondary" data-bs-toggle="modal" data-bs-target="#scheduleLectureModal">
                <i class="fas fa-video me-1"></i> Schedule Live Class
            </button>
        </div>
    </div>

    <!-- Metrics Cards (Withdrawable Balance, Escrow & Students) -->
    <div class="row g-3 g-md-4 mb-5">
        <div class="col-sm-6 col-lg-3">
            <div class="edu-stat-card">
                <div class="edu-stat-icon success">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div class="edu-stat-val text-success">${{ number_format($withdrawableBalance, 2) }}</div>
                    <div class="edu-stat-label">Withdrawable</div>
                    <div class="edu-stat-sub text-muted">Ready for payout</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="edu-stat-card">
                <div class="edu-stat-icon warning">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="edu-stat-val text-warning">${{ number_format($escrowBalance, 2) }}</div>
                    <div class="edu-stat-label">Anti-Fraud Escrow</div>
                    <div class="edu-stat-sub text-muted">Released on completion</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="edu-stat-card">
                <div class="edu-stat-icon info">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <div class="edu-stat-val text-primary">${{ number_format($totalNetEarnings, 2) }}</div>
                    <div class="edu-stat-label">Total Net Earnings</div>
                    <div class="edu-stat-sub text-muted">80% instructor share</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="edu-stat-card">
                <div class="edu-stat-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="edu-stat-val">{{ number_format($totalStudents) }}</div>
                    <div class="edu-stat-label">Enrolled Students</div>
                    <div class="edu-stat-sub text-muted">Across {{ $courses->count() }} courses</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Tabs -->
    <ul class="nav edu-tabs mb-4 gap-2" id="instructorTab" role="tablist">
        <li class="nav-item">
            <button class="edu-tab-btn active" id="courses-tab" data-bs-toggle="pill" data-bs-target="#tab-courses" type="button">
                <i class="fas fa-book me-2"></i> My Courses ({{ $courses->count() }})
            </button>
        </li>
        <li class="nav-item">
            <button class="edu-tab-btn" id="lectures-tab" data-bs-toggle="pill" data-bs-target="#tab-lectures" type="button">
                <i class="fas fa-broadcast-tower me-2"></i> Live Cohorts ({{ $lectures->count() }})
            </button>
        </li>
        <li class="nav-item">
            <button class="edu-tab-btn" id="enrollments-tab" data-bs-toggle="pill" data-bs-target="#tab-enrollments" type="button">
                <i class="fas fa-receipt me-2"></i> Student Enrollments & Payouts
            </button>
        </li>
    </ul>

    <div class="tab-content" id="instructorTabContent">
        <!-- Tab 1: My Courses -->
        <div class="tab-pane fade show active" id="tab-courses">
            <div class="row g-4">
                @forelse($courses as $course)
                    <div class="col-md-6 col-lg-4">
                        <div class="edu-course-card">
                            <div class="edu-card-thumb-wrap">
                                <img src="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800' }}" alt="{{ $course->name }}" class="edu-card-thumb-img">
                                <span class="edu-level-badge">{{ $course->level }}</span>
                            </div>

                            <div class="edu-card-body">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="edu-card-category">{{ $course->category->name ?? 'Technology' }}</span>
                                        <span class="edu-rating-star small"><i class="fas fa-star me-1"></i>{{ $course->rating ?? '4.9' }}</span>
                                    </div>
                                    <h4 class="edu-card-title">
                                        <a href="{{ route('courses.detail', $course->id) }}" class="text-decoration-none" style="color: inherit;">
                                            {{ $course->name }}
                                        </a>
                                    </h4>
                                    <p class="text-muted small mb-3">{{ Str::limit($course->description, 80) }}</p>

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small"><i class="fas fa-user-graduate me-1 text-primary"></i> {{ number_format($course->enrolled_count) }} Students</span>
                                        <span class="fw-bold fs-6">${{ number_format($course->price, 0) }}</span>
                                    </div>

                                    <div class="mb-3">
                                        @if($course->is_completed)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 small">
                                                <i class="fas fa-check-circle me-1"></i> Completed (Escrow Released)
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 small">
                                                <i class="fas fa-shield-alt me-1"></i> In Progress (Funds Held in Escrow)
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="edu-card-footer flex-column gap-2 pt-3">
                                    <div class="d-flex gap-2 w-100">
                                        <a href="{{ route('courses.detail', $course->id) }}" class="btn-edu-secondary btn-sm flex-grow-1 text-center">Preview</a>
                                        <a href="{{ route('courses.uploadMaterial', ['id' => $course->id]) }}" class="btn-edu-primary btn-sm flex-grow-1 text-center">Add Material</a>
                                    </div>

                                    @if(!$course->is_completed)
                                        <form action="{{ route('instructor.course.complete', $course->id) }}" method="POST" class="w-100 mt-1">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm w-100 py-1" onclick="return confirm('Confirm that you have completed uploading all course materials and final exam. Ready to verify and release escrow payout?');">
                                                <i class="fas fa-check-circle me-1"></i> Mark Complete & Release Escrow
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="edu-card-panel py-5 max-w-500 mx-auto" style="max-width: 500px;">
                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                            <h4 class="fw-bold mb-2">You haven't created any courses yet</h4>
                            <p class="text-muted small mb-4">Start by creating your first course and set your price.</p>
                            <button type="button" class="btn-edu-primary" data-bs-toggle="modal" data-bs-target="#createCourseModal">
                                Create Your First Course
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tab 2: Live Lectures -->
        <div class="tab-pane fade" id="tab-lectures">
            <div class="edu-table-responsive">
                <table class="table edu-table">
                    <thead>
                        <tr>
                            <th>Lecture Title</th>
                            <th>Course</th>
                            <th>Scheduled Date & Time</th>
                            <th>Status</th>
                            <th class="text-end">Studio Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lectures as $lec)
                            <tr>
                                <td class="fw-bold">{{ $lec->title }}</td>
                                <td class="text-muted">{{ $lec->course->name ?? 'Open Masterclass' }}</td>
                                <td class="small">{{ $lec->start_time ? $lec->start_time->format('M d, Y • h:i A') : 'Flexible Schedule' }}</td>
                                <td>
                                    @if($lec->status === 'live')
                                        <span class="badge-live-pulse">Live Now</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20">{{ ucfirst($lec->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('live.classroom', $lec->id) }}" class="btn-edu-primary btn-sm px-3" style="{{ $lec->status === 'live' ? 'background: #ef4444;' : '' }}">
                                        <i class="fas fa-broadcast-tower me-1"></i> {{ $lec->status === 'live' ? 'Broadcast Studio' : 'Host Studio' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No live lectures scheduled yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 3: Recent Enrollments & Fees -->
        <div class="tab-pane fade" id="tab-enrollments">
            <div class="edu-table-responsive">
                <table class="table edu-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Gross Paid</th>
                            <th>Platform Fee (20%)</th>
                            <th>Your Payout (80%)</th>
                            <th>Escrow Status</th>
                            <th class="text-end">Enrollment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $enr)
                            <tr>
                                <td class="fw-semibold">{{ $enr->user->name ?? 'Student' }}</td>
                                <td class="text-muted">{{ $enr->course->name ?? 'Course' }}</td>
                                <td class="fw-semibold">${{ number_format($enr->amount_paid, 2) }}</td>
                                <td class="text-muted small">-${{ number_format($enr->platform_fee, 2) }}</td>
                                <td class="text-success fw-bold">+${{ number_format($enr->instructor_earning, 2) }}</td>
                                <td>
                                    @if($enr->payout_status === 'released_to_instructor')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 small">
                                            <i class="fas fa-check-double me-1"></i> Released to Wallet
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 small">
                                            <i class="fas fa-shield-alt me-1"></i> Held in Escrow
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted small text-end">{{ $enr->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No student enrollments yet. Share your course link to start receiving sales!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal: Create New Course -->
<div class="modal fade" id="createCourseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i> Create & Publish New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('instructor.courses.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="text-muted small fw-semibold mb-1">Course Title:</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Production Go & Microservices Architecture" required>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold mb-1">Category:</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold mb-1">Enrollment Price ($ USD):</label>
                            <input type="number" name="price" class="form-control" value="49" min="0" step="1" required>
                            <div class="text-muted mt-1" style="font-size: 0.72rem;">You keep 80% ($39.20), Platform fee 20% ($9.80)</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold mb-1">Difficulty Level:</label>
                            <select name="level" class="form-select">
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate" selected>Intermediate</option>
                                <option value="Advanced">Advanced</option>
                                <option value="All Levels">All Levels</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold mb-1">Duration:</label>
                            <input type="text" name="duration" class="form-control" value="8 Weeks (30 Hours)">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small fw-semibold mb-1">Course Description:</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Describe the practical skills and outcomes students gain from this course..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small fw-semibold mb-1">Demo Video URL (Sample Class):</label>
                        <input type="url" name="demo_video_url" class="form-control" placeholder="https://... (mp4 or streaming URL)">
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small fw-semibold mb-1">What Students Will Learn (One outcome per line):</label>
                        <textarea name="what_you_will_learn" rows="3" class="form-control" placeholder="Core language syntax and idioms&#10;Building high-scale REST & gRPC microservices&#10;Deploying with Docker & Kubernetes CI/CD"></textarea>
                    </div>
                </div>
                <div class="modal-footer p-3" style="border-top: 1px solid var(--border-color); background: var(--bg-secondary);">
                    <button type="button" class="btn-edu-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-edu-primary">Publish Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Schedule Live Lecture -->
<div class="modal fade" id="scheduleLectureModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title fw-bold"><i class="fas fa-video text-danger me-2"></i> Schedule Live Masterclass</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('instructor.lectures.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="text-muted small fw-semibold mb-1">Lecture Topic / Title:</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Live System Design Q&A: High-Throughput Message Queues" required>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small fw-semibold mb-1">Associated Course (Optional):</label>
                        <select name="course_id" class="form-select">
                            <option value="">-- Open Masterclass (Accessible to all students) --</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small fw-semibold mb-1">Start Date & Time:</label>
                            <input type="datetime-local" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-semibold mb-1">End Date & Time:</label>
                            <input type="datetime-local" name="end_time" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3 p-3 rounded" style="background: var(--primary-light); border: 1px solid var(--primary-border);">
                        <div class="d-flex align-items-center gap-2 text-primary fw-bold small mb-1">
                            <i class="fas fa-satellite-dish"></i> Built-in Browser Live Studio
                        </div>
                        <div class="text-muted small">
                            Zero external downloads or third-party links required. Broadcast directly inside Digital Academy with screen sharing, live chat, doubt queue, and code runners.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small fw-semibold mb-1">Session Status:</label>
                        <select name="status" class="form-select">
                            <option value="scheduled" selected>Scheduled (Upcoming)</option>
                            <option value="live">Live Now (Streaming)</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_demo" value="1" id="isDemoCheck">
                        <label class="form-check-label text-muted small" for="isDemoCheck">
                            Mark as Free Demo (Accessible to guest learners without enrollment)
                        </label>
                    </div>
                </div>
                <div class="modal-footer p-3" style="border-top: 1px solid var(--border-color); background: var(--bg-secondary);">
                    <button type="button" class="btn-edu-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-edu-primary">Schedule Class</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
