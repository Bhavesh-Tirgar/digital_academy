@extends('frontend.master')

@section('title', 'Digital Academy – Learn In-Demand Tech Skills from Senior Engineers')

@section('content')
<div class="container py-3 py-md-4">

    <!-- 1. HIGH-IMPACT HERO SECTION -->
    <section class="edu-hero-section">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: var(--primary-light); border: 1px solid var(--primary-border);">
                    <i class="fas fa-certificate text-primary"></i>
                    <span class="text-primary fw-bold small text-uppercase letter-spacing-1">Accredited Engineering Cohorts</span>
                </div>

                <h1 class="edu-hero-title">
                    Master Real-World Tech Skills. <br class="d-none d-md-block">
                    Learn Directly From <span style="color: var(--primary);">Senior Engineers</span>.
                </h1>

                <p class="edu-hero-sub">
                    Autograded coding sandboxes, interactive weekly live masterclasses, and verified digital credentials. Pay once per course with lifetime ownership and zero recurring subscriptions.
                </p>

                <!-- Search Input -->
                <form action="{{ route('courses.catalog') }}" method="GET" class="edu-hero-search mb-3">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="What skill do you want to master? (e.g. Python, AWS, React, AI)...">
                    <button type="submit" class="btn-edu-primary btn-sm px-3.5 text-nowrap">Explore</button>
                </form>

                <!-- Popular Search Chips -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    <span class="small text-muted fw-semibold">Popular:</span>
                    <a href="{{ route('courses.catalog', ['search' => 'Python']) }}" class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 text-decoration-none px-2.5 py-1">Python</a>
                    <a href="{{ route('courses.catalog', ['search' => 'Full-Stack']) }}" class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 text-decoration-none px-2.5 py-1">Full-Stack</a>
                    <a href="{{ route('courses.catalog', ['search' => 'Cloud']) }}" class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 text-decoration-none px-2.5 py-1">Cloud & DevOps</a>
                    <a href="{{ route('courses.catalog', ['search' => 'AI']) }}" class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 text-decoration-none px-2.5 py-1">Generative AI</a>
                </div>

                <!-- Trust Metrics Strip -->
                <div class="d-flex flex-wrap align-items-center gap-4 pt-3 border-top" style="border-color: var(--border-color) !important;">
                    <div>
                        <div class="fw-bold fs-5 text-primary">25,000+</div>
                        <div class="text-muted small">Global Learners</div>
                    </div>
                    <div class="border-start border-secondary border-opacity-20 ps-4">
                        <div class="fw-bold fs-5 text-warning">4.9 ★★★★★</div>
                        <div class="text-muted small">Average Rating</div>
                    </div>
                    <div class="border-start border-secondary border-opacity-20 ps-4">
                        <div class="fw-bold fs-5 text-success">100%</div>
                        <div class="text-muted small">Escrow Protected</div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Visual Live Cohort / Interactive Showcase -->
            <div class="col-lg-5">
                @if($liveNow)
                    <!-- Active Live Cohort Panel -->
                    <div class="edu-card-panel position-relative shadow-lg overflow-hidden" style="border-top: 3px solid var(--accent-rose);">
                        <div class="position-relative mb-3 rounded-3 overflow-hidden bg-black" style="aspect-ratio: 16/9;">
                            <img src="{{ $liveNow->course->thumbnail ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800' }}" alt="Live stream preview" class="w-100 h-100 object-fit-cover opacity-75">
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge-live-pulse">🔴 Live Masterclass</span>
                            </div>
                            <div class="position-absolute bottom-0 end-0 m-2">
                                <span class="badge bg-black bg-opacity-75 text-white small px-2 py-1">
                                    <i class="fas fa-users me-1 text-danger"></i> {{ $liveNow->active_viewers ?? 142 }} In Room
                                </span>
                            </div>
                        </div>

                        <h4 class="fw-bold mb-1 fs-5">{{ $liveNow->title }}</h4>
                        <p class="text-muted small mb-3">{{ Str::limit($liveNow->description, 80) }}</p>

                        <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded" style="background: var(--bg-secondary);">
                            <img src="{{ $liveNow->instructor->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" alt="{{ $liveNow->instructor->name }}" class="rounded-circle border border-primary" style="width: 36px; height: 36px; object-fit: cover;">
                            <div>
                                <div class="fw-bold small">{{ $liveNow->instructor->name }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $liveNow->instructor->headline ?? 'Senior Tech Lead & Curriculum Author' }}</div>
                            </div>
                        </div>

                        <a href="{{ route('live.classroom', $liveNow->id) }}" class="btn-edu-primary w-100 justify-content-center py-2.5">
                            <i class="fas fa-broadcast-tower me-1"></i> Enter Live Classroom
                        </a>
                    </div>
                @else
                    <!-- High-Converting Cohort Invitation Card -->
                    <div class="edu-card-panel position-relative shadow-lg p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-2.5 py-1 small fw-bold">
                                🚀 Weekly Cohorts
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-success small">Seats Available</span>
                        </div>

                        <div class="position-relative mb-3 rounded-3 overflow-hidden" style="aspect-ratio: 16/9; background: #0f172a;">
                            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800" alt="Cohort collaboration" class="w-100 h-100 object-fit-cover opacity-85">
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: rgba(15, 23, 42, 0.45);">
                                <button type="button" class="btn-edu-primary rounded-circle p-0 shadow-lg mb-2" onclick="openDemoModal('Platform Engineering Masterclass', 'https://www.youtube.com/watch?v=nu_pCVPKzTk', '{{ route('courses.catalog') }}')" style="width: 56px; height: 56px; font-size: 1.25rem;">
                                    <i class="fas fa-play ms-1"></i>
                                </button>
                                <span class="text-white fw-bold small">Watch 2-Min Platform Tour</span>
                            </div>
                        </div>

                        <h4 class="fw-bold mb-1 fs-5">Interactive Live Cohorts</h4>
                        <p class="text-muted small mb-3">Join senior software engineers for real-time code reviews, Q&A, and system design masterclasses.</p>

                        <div class="d-flex gap-2">
                            <a href="{{ route('schedule.index') }}" class="btn-edu-primary flex-grow-1 justify-content-center">
                                <i class="fas fa-calendar-alt me-1"></i> View Cohort Timetable
                            </a>
                            <a href="{{ route('courses.catalog') }}" class="btn-edu-secondary">
                                Catalog
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- 2. ENTERPRISE REPUTATION STRIP (COMPANIES OUR LEARNERS COME FROM) -->
    <div class="edu-trust-strip">
        <div class="edu-trust-title">Engineers & teams from high-growth companies build skills on Digital Academy</div>
        <div class="edu-trust-logos">
            <span class="edu-trust-brand"><i class="fab fa-google text-danger"></i> Google</span>
            <span class="edu-trust-brand"><i class="fab fa-amazon text-warning"></i> Amazon</span>
            <span class="edu-trust-brand"><i class="fab fa-microsoft text-info"></i> Microsoft</span>
            <span class="edu-trust-brand"><i class="fab fa-meta text-primary"></i> Meta</span>
            <span class="edu-trust-brand"><i class="fab fa-stripe text-primary"></i> Stripe</span>
            <span class="edu-trust-brand"><i class="fab fa-spotify text-success"></i> Spotify</span>
        </div>
    </div>

    <!-- 3. INTERACTIVE LEARNING TRACKS (CATEGORY GRID) -->
    <section class="my-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-2.5 py-1 mb-1 small fw-bold">Explore Fields</span>
                <h2 class="edu-section-title mb-1">Top Engineering Learning Tracks</h2>
                <div class="edu-section-sub mb-0">Specialized technical tracks designed for zero-to-production mastery.</div>
            </div>
            <a href="{{ route('courses.catalog') }}" class="btn-edu-secondary btn-sm d-none d-sm-inline-flex">
                All Tracks <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            <div class="col-sm-6 col-lg-4">
                <a href="{{ route('courses.catalog', ['search' => 'Web']) }}" class="edu-category-tile">
                    <div class="edu-cat-icon-wrap blue"><i class="fas fa-code"></i></div>
                    <div>
                        <div class="edu-cat-tile-title">Full-Stack Web Development</div>
                        <div class="edu-cat-tile-sub">Python, React, Node, Laravel • 8 Courses</div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-4">
                <a href="{{ route('courses.catalog', ['search' => 'Cloud']) }}" class="edu-category-tile">
                    <div class="edu-cat-icon-wrap emerald"><i class="fas fa-cloud"></i></div>
                    <div>
                        <div class="edu-cat-tile-title">Cloud Architecture & DevOps</div>
                        <div class="edu-cat-tile-sub">AWS, Docker, Kubernetes, Terraform • 6 Courses</div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-4">
                <a href="{{ route('courses.catalog', ['search' => 'AI']) }}" class="edu-category-tile">
                    <div class="edu-cat-icon-wrap purple"><i class="fas fa-brain"></i></div>
                    <div>
                        <div class="edu-cat-tile-title">Artificial Intelligence & LLMs</div>
                        <div class="edu-cat-tile-sub">PyTorch, LangChain, Transformers • 5 Courses</div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-4">
                <a href="{{ route('courses.catalog', ['search' => 'Mobile']) }}" class="edu-category-tile">
                    <div class="edu-cat-icon-wrap cyan"><i class="fas fa-mobile-alt"></i></div>
                    <div>
                        <div class="edu-cat-tile-title">Mobile App Development</div>
                        <div class="edu-cat-tile-sub">Flutter, React Native, Swift, Kotlin • 4 Courses</div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-4">
                <a href="{{ route('courses.catalog', ['search' => 'Security']) }}" class="edu-category-tile">
                    <div class="edu-cat-icon-wrap rose"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <div class="edu-cat-tile-title">Cybersecurity & Ethical Hacking</div>
                        <div class="edu-cat-tile-sub">Network Defense, AppSec, OWASP • 4 Courses</div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-4">
                <a href="{{ route('courses.catalog', ['search' => 'Data']) }}" class="edu-category-tile">
                    <div class="edu-cat-icon-wrap amber"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <div class="edu-cat-tile-title">Data Engineering & Analytics</div>
                        <div class="edu-cat-tile-sub">PostgreSQL, Spark, Kafka, BigQuery • 5 Courses</div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- 4. FEATURED COURSES (CLEAN COURSERA/UDEMY STANDARD) -->
    <section class="my-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-2.5 py-1 mb-1 small fw-bold">Curated Catalog</span>
                <h2 class="edu-section-title mb-1">Most Popular Engineering Courses</h2>
                <div class="edu-section-sub mb-0">Structured video modules, hands-on coding sandboxes, and verified certificates.</div>
            </div>
            <a href="{{ route('courses.catalog') }}" class="btn-edu-secondary btn-sm">
                View All Courses <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($courses->take(6) as $course)
                <div class="col-sm-6 col-lg-4">
                    <div class="edu-course-card">
                        <!-- 16:9 Thumbnail -->
                        <div class="edu-card-thumb-wrap">
                            <img src="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800' }}" alt="{{ $course->name }}" class="edu-card-thumb-img">
                            <span class="edu-level-badge">{{ $course->level }}</span>
                            @if($course->enrolled_count > 500)
                                <span class="edu-bestseller-badge">🔥 Bestseller</span>
                            @endif

                            <button type="button" class="edu-play-preview-btn" onclick="openDemoModal('{{ addslashes($course->name) }}', '{{ $course->demo_video_url }}', '{{ route('courses.checkout', $course->id) }}')">
                                <i class="fas fa-play fa-2xs"></i> Free Demo
                            </button>
                        </div>

                        <!-- Card Body -->
                        <div class="edu-card-body">
                            <div>
                                <div class="edu-card-category">{{ $course->category->name ?? 'Computer Science' }}</div>
                                <h3 class="edu-card-title">
                                    <a href="{{ route('courses.detail', $course->id) }}">
                                        {{ $course->name }}
                                    </a>
                                </h3>

                                <div class="edu-card-instructor">
                                    <img src="{{ $course->instructor->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=80' }}" alt="Mentor">
                                    <span>{{ $course->instructor->name ?? 'Senior Instructor' }}</span>
                                </div>

                                <div class="edu-card-rating">
                                    <span class="edu-rating-star">★ {{ $course->rating ?? '4.9' }}</span>
                                    <span class="edu-rating-count">({{ number_format($course->enrolled_count) }} students)</span>
                                </div>
                            </div>

                            <div class="edu-card-footer">
                                <div>
                                    <div class="d-flex align-items-baseline">
                                        <span class="edu-price-strikethrough" data-price-usd="{{ $course->price * 1.8 }}">${{ number_format($course->price * 1.8, 0) }}</span>
                                        <span class="edu-card-price" data-price-usd="{{ $course->price }}">${{ number_format($course->price, 0) }}</span>
                                    </div>
                                    <div class="text-success small" style="font-size: 0.7rem; font-weight: 600;">Lifetime Access</div>
                                </div>
                                <a href="{{ route('courses.detail', $course->id) }}" class="btn-edu-primary btn-sm px-3">
                                    View Syllabus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 5. UPCOMING LIVE COHORTS SCHEDULE -->
    <section class="my-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2.5 py-1 mb-1 small fw-bold">Live Timetable</span>
                <h2 class="edu-section-title mb-1">Upcoming Live Masterclasses</h2>
                <div class="edu-section-sub mb-0">Join interactive workshops with senior instructors and code live in browser.</div>
            </div>
            <a href="{{ route('schedule.index') }}" class="btn-edu-secondary btn-sm">
                Full Calendar <i class="fas fa-calendar ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($liveLectures->take(3) as $lecture)
                <div class="col-md-6 col-lg-4">
                    <div class="edu-card-panel h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                @if($lecture->status === 'live')
                                    <span class="badge-live-pulse">🔴 Live Now</span>
                                @else
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 small fw-semibold">
                                        <i class="far fa-clock me-1"></i> {{ $lecture->start_time ? $lecture->start_time->format('D, M d • h:i A') : 'Upcoming Cohort' }}
                                    </span>
                                @endif
                                <a href="{{ route('schedule.calendar.ics', $lecture->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" title="Sync to Calendar">
                                    <i class="far fa-calendar-plus"></i>
                                </a>
                            </div>

                            <h4 class="fw-bold mb-2 fs-5">{{ $lecture->title }}</h4>
                            <p class="text-muted small mb-3">{{ Str::limit($lecture->description, 85) }}</p>
                        </div>

                        <div>
                            <div class="d-flex align-items-center gap-2 mb-3 pt-3 border-top" style="border-color: var(--border-color) !important;">
                                <img src="{{ $lecture->instructor->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;">
                                <div>
                                    <div class="small fw-semibold">{{ $lecture->instructor->name }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $lecture->instructor->headline ?? 'Curriculum Author' }}</div>
                                </div>
                            </div>

                            <a href="{{ route('live.classroom', $lecture->id) }}" class="btn-edu-primary btn-sm w-100 justify-content-center">
                                <i class="fas fa-broadcast-tower me-1"></i> Enter Classroom
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-4">No live masterclasses scheduled today.</div>
            @endforelse
        </div>
    </section>

    <!-- 6. WHY DIGITAL ACADEMY: PEDAGOGY & PROTECTION -->
    <section class="my-5 py-4 edu-card-panel">
        <div class="text-center mb-5 max-w-600 mx-auto">
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1 mb-2 small fw-bold">Pedagogy First</span>
            <h2 class="edu-section-title mb-2">Built for Real Engineering Competency</h2>
            <p class="edu-section-sub mb-0">Unlike traditional platforms with passive video watching, our methodology combines hands-on sandboxes with active accountability.</p>
        </div>

        <div class="row g-4 text-center">
            <div class="col-sm-6 col-lg-3">
                <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(37, 99, 235, 0.1); color: var(--primary);">
                    <i class="fas fa-code fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-2">Autograded Code Labs</h5>
                <p class="text-muted small mb-0">Write real software in your browser with automated unit assertions and instant feedback.</p>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-emerald);">
                    <i class="fas fa-shield-alt fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-2">Anti-Fraud Escrow</h5>
                <p class="text-muted small mb-0">Payments are protected in platform escrow and only released once teachers complete the curriculum.</p>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(245, 158, 11, 0.1); color: var(--accent-amber);">
                    <i class="fas fa-broadcast-tower fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-2">Native In-Browser Live</h5>
                <p class="text-muted small mb-0">Zero Zoom or Meet links. Experience real-time video streaming with live doubt queues and chat.</p>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(239, 68, 68, 0.1); color: var(--accent-rose);">
                    <i class="fas fa-certificate fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-2">Accredited Credentials</h5>
                <p class="text-muted small mb-0">Verifiable digital certificates with unique verification QR codes unlocked after passing the final exam.</p>
            </div>
        </div>
    </section>

    <!-- 7. TEACH ON PLATFORM BANNER -->
    <section class="my-5 p-4 p-md-5 rounded-4 text-white text-center text-md-start" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);">
        <div class="row align-items-center g-4">
            <div class="col-md-8">
                <span class="badge bg-white bg-opacity-20 text-white mb-2 px-3 py-1 rounded-pill small fw-bold">Creator Revenue Share</span>
                <h2 class="fw-bold mb-2 text-white">Share Your Expertise. Teach on Digital Academy.</h2>
                <p class="mb-0 text-white text-opacity-80 small" style="max-width: 580px;">
                    Publish technical courses, stream live cohorts with our native studio, and keep 80% net revenue with automated milestone payouts.
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('become.instructor') }}" class="btn btn-light btn-lg py-2.5 px-4 rounded-pill fw-bold" style="color: var(--primary);">
                    Start Teaching Today <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
