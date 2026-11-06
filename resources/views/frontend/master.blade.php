<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Digital Academy – Learn, Stream & Master High-Income Tech')</title>

    <!-- Theme Preference Initialization -->
    <script>
        (function() {
            var savedTheme = localStorage.getItem('da_theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <!-- Progressive Web App (PWA) Manifest & Native Mobile Shell -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="DigitalAcademy">
    <link rel="apple-touch-icon" href="{{ asset('images/pwa/apple-touch-icon.png') }}">

    <!-- Bootstrap 5.3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Modern Educational Platform CSS (Coursera / Udemy / Maven Inspired) -->
    <link rel="stylesheet" href="{{ asset('css/modern-academy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    @yield('styles')
</head>
<body>

    <!-- Top Educational Navbar -->
    <nav class="edu-navbar">
        <div class="container d-flex align-items-center justify-content-between gap-3">
            <!-- Brand Logo -->
            <a class="edu-brand" href="{{ route('frontend.home') }}">
                <div class="edu-brand-badge">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <span>Digital<span style="color: var(--primary);">Academy</span></span>
            </a>

            <!-- Explore Categories Dropdown (Coursera / Udemy Style) -->
            <div class="dropdown edu-explore-dropdown d-none d-xl-block">
                <button class="btn btn-edu-secondary btn-sm dropdown-toggle py-1.5 px-3 d-flex align-items-center gap-2 fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-th-large text-primary"></i> Explore
                </button>
                <div class="dropdown-menu shadow-lg">
                    <h6 class="dropdown-header small text-uppercase fw-bold text-muted px-2 mb-1">Learning Tracks</h6>
                    <a class="edu-explore-item" href="{{ route('courses.catalog', ['search' => 'Web']) }}">
                        <i class="fas fa-code text-primary"></i>
                        <div>
                            <div class="fw-bold small">Full-Stack Development</div>
                            <div class="text-muted" style="font-size: 0.72rem;">Python, React, Node, Laravel</div>
                        </div>
                    </a>
                    <a class="edu-explore-item" href="{{ route('courses.catalog', ['search' => 'Cloud']) }}">
                        <i class="fas fa-cloud text-info"></i>
                        <div>
                            <div class="fw-bold small">Cloud & DevOps</div>
                            <div class="text-muted" style="font-size: 0.72rem;">AWS, Docker, Kubernetes, CI/CD</div>
                        </div>
                    </a>
                    <a class="edu-explore-item" href="{{ route('courses.catalog', ['search' => 'AI']) }}">
                        <i class="fas fa-brain text-warning"></i>
                        <div>
                            <div class="fw-bold small">Artificial Intelligence & LLMs</div>
                            <div class="text-muted" style="font-size: 0.72rem;">PyTorch, Generative AI, Agents</div>
                        </div>
                    </a>
                    <a class="edu-explore-item" href="{{ route('courses.catalog', ['search' => 'Security']) }}">
                        <i class="fas fa-shield-alt text-success"></i>
                        <div>
                            <div class="fw-bold small">Cybersecurity & Defense</div>
                            <div class="text-muted" style="font-size: 0.72rem;">Pen testing, AppSec, Networks</div>
                        </div>
                    </a>
                    <div class="border-top border-secondary border-opacity-15 my-2"></div>
                    <a class="d-flex align-items-center justify-content-between px-3 py-2 text-primary small fw-bold text-decoration-none" href="{{ route('courses.catalog') }}">
                        <span>Browse All Courses</span>
                        <i class="fas fa-arrow-right fa-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Search Input (Desktop) -->
            <form action="{{ route('courses.catalog') }}" method="GET" class="edu-nav-search d-none d-md-block">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="What do you want to learn today?">
            </form>

            <!-- Navigation Links (Desktop) -->
            <div class="d-none d-lg-flex align-items-center gap-1">
                <a class="edu-nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.catalog') }}" data-i18n="nav_courses">
                    Courses
                </a>
                <a class="edu-nav-link {{ request()->routeIs('schedule.*') ? 'active' : '' }}" href="{{ route('schedule.index') }}">
                    <span class="badge-live-pulse me-1">Live</span> <span data-i18n="nav_live">Cohorts</span>
                </a>
                <a class="edu-nav-link {{ request()->routeIs('ai.sandbox') ? 'active' : '' }}" href="{{ route('ai.sandbox') }}">
                    <i class="fas fa-brain text-warning me-1"></i> AI Sandbox
                </a>
                <a class="edu-nav-link {{ request()->routeIs('become.instructor') ? 'active' : '' }}" href="{{ route('become.instructor') }}" data-i18n="nav_teach">
                    Teach
                </a>
            </div>

            <!-- Header Actions -->
            <div class="d-flex align-items-center gap-2">
                <!-- Currency / Country Selector Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-edu-secondary btn-sm dropdown-toggle py-1 px-2 d-flex align-items-center gap-1" type="button" id="currencySelectorBtn" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.8rem;" title="Select Currency & Country">
                        <span>🇺🇸 USD ($)</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" style="font-size: 0.82rem; min-width: 190px; background: var(--dropdown-bg); border: 1px solid var(--border-color);">
                        <li><h6 class="dropdown-header small text-uppercase">Country & Currency</h6></li>
                        <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="setPlatformCurrency('USD')">🇺🇸 USD ($) – United States</a></li>
                        <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="setPlatformCurrency('INR')">🇮🇳 INR (₹) – India</a></li>
                        <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="setPlatformCurrency('EUR')">🇪🇺 EUR (€) – Eurozone</a></li>
                        <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="setPlatformCurrency('GBP')">🇬🇧 GBP (£) – United Kingdom</a></li>
                        <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="setPlatformCurrency('CAD')">🇨🇦 CAD ($) – Canada</a></li>
                        <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="setPlatformCurrency('AUD')">🇦🇺 AUD ($) – Australia</a></li>
                    </ul>
                </div>

                <!-- Language Selector Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-edu-secondary btn-sm dropdown-toggle py-1 px-2 d-flex align-items-center gap-1" type="button" id="langSelectorBtn" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.8rem;" title="Select Language">
                        <i class="fas fa-globe text-muted"></i> <span id="currentLangLabel">EN</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" style="font-size: 0.82rem; min-width: 160px; background: var(--dropdown-bg); border: 1px solid var(--border-color);">
                        <li><h6 class="dropdown-header small text-uppercase">Platform Language</h6></li>
                        <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="setPlatformLanguage('en')">🇺🇸 English (EN)</a></li>
                        <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="setPlatformLanguage('hi')">🇮🇳 हिन्दी (HI)</a></li>
                        <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="setPlatformLanguage('es')">🇪🇸 Español (ES)</a></li>
                        <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="setPlatformLanguage('fr')">🇫🇷 Français (FR)</a></li>
                        <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="setPlatformLanguage('de')">🇩🇪 Deutsch (DE)</a></li>
                    </ul>
                </div>

                <!-- Audio Learning Sounds Toggle Button -->
                <button id="soundToggleBtn" class="btn btn-edu-secondary btn-sm p-2" type="button" aria-label="Toggle Sound Effects" title="Sound Effects: ON" onclick="window.Dopamine && window.Dopamine.toggleSound()">
                    <i class="fas fa-volume-up text-primary" id="soundIcon"></i>
                </button>

                <!-- Theme Toggle Button -->
                <button id="themeToggleBtn" class="btn btn-edu-secondary btn-sm p-2" type="button" aria-label="Toggle Theme" title="Toggle Dark/Light Mode">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>

                <!-- PWA 1-Click Install Button (Shown on desktop & mobile when installable) -->
                <button class="btn btn-edu-secondary btn-sm d-none btn-pwa-install px-2.5 py-1 text-primary border-primary border-opacity-30 fw-semibold align-items-center gap-1" type="button" onclick="window.installPwaApp()" title="Install Digital Academy Desktop / Mobile App" style="font-size: 0.8rem;">
                    <i class="fas fa-arrow-down-to-bracket text-primary"></i> <span class="d-none d-md-inline">Install App</span>
                </button>

                <!-- Auth Buttons / User Profile -->
                @if(Auth::check())
                    <div class="dropdown">
                        <button class="btn btn-edu-secondary btn-sm dropdown-toggle d-flex align-items-center gap-2 py-1 px-2" type="button" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=80' }}" class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;" alt="Avatar">
                            <span class="d-none d-sm-inline font-weight-bold" style="font-size: 0.85rem;">{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" style="background: var(--dropdown-bg); border: 1px solid var(--border-color); min-width: 210px;">
                            <li class="px-3 py-2 border-bottom border-secondary border-opacity-15">
                                <div class="fw-bold small">{{ Auth::user()->name }}</div>
                                <div class="text-muted small" style="font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                                <div class="d-flex align-items-center gap-1 mt-1">
                                    <span class="badge bg-primary text-uppercase" style="font-size: 0.65rem;">{{ Auth::user()->role }}</span>
                                    @if(Auth::user()->isInstructor())
                                        <span class="badge bg-success" style="font-size: 0.65rem;">Verified Creator</span>
                                    @endif
                                </div>
                            </li>
                            @if(Auth::user()->isAdmin())
                                <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i class="fas fa-shield-alt me-2 text-danger"></i> Admin Dashboard</a></li>
                            @endif

                            <!-- Student Learning Dashboard (Available to All Learners and Teachers) -->
                            <li><a class="dropdown-item py-2 fw-semibold text-primary" href="{{ route('student.dashboard') }}"><i class="fas fa-user-graduate me-2"></i> Student Dashboard (My Learning)</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('ai.sandbox') }}"><i class="fas fa-terminal me-2 text-warning"></i> Professor AI Sandbox</a></li>

                            @if(Auth::user()->isInstructor() || Auth::user()->isAdmin())
                                <li><a class="dropdown-item py-2 fw-semibold text-success" href="{{ route('instructor.dashboard') }}"><i class="fas fa-chalkboard-teacher me-2"></i> Instructor Studio (My Cohorts)</a></li>
                            @else
                                <li><a class="dropdown-item py-2 text-muted" href="{{ route('become.instructor') }}"><i class="fas fa-hand-holding-usd me-2 text-warning"></i> Teach on Platform (80% Payout)</a></li>
                            @endif

                            <li><a class="dropdown-item py-2" href="{{ route('schedule.index') }}"><i class="fas fa-calendar-alt me-2 text-info"></i> Live Cohorts Schedule</a></li>
                            <li><hr class="dropdown-divider border-secondary border-opacity-25"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="fas fa-sign-out-alt me-2"></i> Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-edu-secondary btn-sm px-3" data-i18n="nav_login">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-edu-primary btn-sm px-3 d-none d-sm-inline-flex" data-i18n="nav_register">Sign Up</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Global Flash Messages -->
    @if(session('success') || session('info') || session('error'))
        <div class="container mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2 text-success"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-info-circle me-2 text-info"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2 text-danger"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content Yield -->
    <main>
        @yield('content')
    </main>

    <!-- Mobile Bottom App Bar (Native Smartphone Navigation) -->
    @if(!request()->routeIs('live.classroom'))
    <nav class="mobile-bottom-nav">
        <a href="{{ route('frontend.home') }}" class="mobile-nav-item {{ request()->routeIs('frontend.home') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('courses.catalog') }}" class="mobile-nav-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
            <i class="fas fa-book-open"></i>
            <span>Courses</span>
        </a>
        <a href="{{ route('schedule.index') }}" class="mobile-nav-item {{ request()->routeIs('schedule.*') ? 'active' : '' }}">
            <div class="mobile-live-dot"></div>
            <i class="fas fa-broadcast-tower"></i>
            <span>Live</span>
        </a>
        @auth
            @if(Auth::user()->isInstructor())
                <a href="{{ route('instructor.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('instructor.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Studio</span>
                </a>
            @else
                <a href="{{ route('student.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('student.*') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Learning</span>
                </a>
            @endif
        @else
            <a href="{{ route('login') }}" class="mobile-nav-item">
                <i class="fas fa-user"></i>
                <span>Sign In</span>
            </a>
        @endauth
        <!-- Mobile PWA Install Item (Shown when install prompt is active) -->
        <button type="button" class="mobile-nav-item btn-pwa-install d-none border-0 bg-transparent text-primary" onclick="window.installPwaApp()" title="Install App">
            <i class="fas fa-arrow-down-to-bracket"></i>
            <span>Install</span>
        </button>
    </nav>
    @endif

    <!-- Clean Educational Footer -->
    <footer class="py-5 mt-5 border-top" style="background: var(--bg-card); border-color: var(--border-color) !important;">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <div class="col-lg-4">
                    <div class="edu-brand mb-3">
                        <div class="edu-brand-badge"><i class="fas fa-graduation-cap"></i></div>
                        <span>Digital<span style="color: var(--primary);">Academy</span></span>
                    </div>
                    <p class="text-muted small pe-lg-4 mb-3">
                        Accredited technical academy and creator marketplace. Learn directly from verified senior engineers with hands-on labs and live masterclasses.
                    </p>
                    <div class="text-muted small">
                        © 2026 Digital Academy Platform. All rights reserved.
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold small text-uppercase mb-3">Learn</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('courses.catalog') }}" class="text-muted">Course Catalog</a></li>
                        <li class="mb-2"><a href="{{ route('schedule.index') }}" class="text-muted">Live Cohorts</a></li>
                        <li class="mb-2"><a href="{{ route('ebooks.index') }}" class="text-muted">Free E-Books & Guides</a></li>
                        <li class="mb-2"><a href="{{ route('reviews.index') }}" class="text-muted">Student Reviews</a></li>
                        <li class="mb-2"><a href="{{ route('pricing.index') }}" class="text-muted">Platform Pricing</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold small text-uppercase mb-3">Instructors</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('become.instructor') }}" class="text-muted">Teach on Academy</a></li>
                        <li class="mb-2"><a href="{{ route('instructor.dashboard') }}" class="text-muted">Creator Portal</a></li>
                        <li class="mb-2"><a href="{{ route('pricing.index') }}" class="text-muted">80% Revenue Share</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h6 class="fw-bold small text-uppercase mb-3">Anti-Fraud Guarantee</h6>
                    <p class="text-muted small mb-2">
                        Student payments are protected in platform escrow and released only after verified curriculum completion.
                    </p>
                    <div class="d-flex align-items-center gap-2 text-success small fw-semibold">
                        <i class="fas fa-shield-alt"></i> 100% Student Protection Active
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme Toggle Controller -->
    <script>
        (function() {
            var themeBtn = document.getElementById('themeToggleBtn');
            var themeIcon = document.getElementById('themeIcon');

            function updateIcon(theme) {
                if (themeIcon) {
                    if (theme === 'light') {
                        themeIcon.className = 'fas fa-moon text-secondary';
                        if (themeBtn) themeBtn.title = 'Switch to Dark Mode';
                    } else {
                        themeIcon.className = 'fas fa-sun text-warning';
                        if (themeBtn) themeBtn.title = 'Switch to Light Mode';
                    }
                }
            }

            var currentTheme = localStorage.getItem('da_theme') || 'light';
            updateIcon(currentTheme);

            if (themeBtn) {
                themeBtn.addEventListener('click', function() {
                    var cur = document.documentElement.getAttribute('data-theme') || 'light';
                    var nextTheme = cur === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', nextTheme);
                    localStorage.setItem('da_theme', nextTheme);
                    updateIcon(nextTheme);
                });
            }
        })();
    </script>

    <!-- Global Interactive Demo Video Modal -->
    <div class="modal fade" id="demoVideoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); overflow: hidden;">
                <div class="modal-header border-bottom border-secondary border-opacity-15 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-20 text-primary small"><i class="fas fa-play-circle me-1"></i> Public Preview</span>
                        <h5 class="modal-title fw-bold fs-6 mb-0 text-truncate" id="demoModalTitle" style="max-width: 500px;">Course Preview</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="stopDemoVideo()"></button>
                </div>
                <div class="modal-body p-0 bg-black position-relative" style="min-height: 360px;">
                    <video id="demoVideoPlayer" controls playsinline class="w-100" style="max-height: 480px; display: block; background: #000; outline: none;">
                        <source id="demoVideoSource" src="" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <iframe id="demoVideoIframe" class="w-100 d-none" style="height: 420px; border: 0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-15 py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="text-muted small">
                        <i class="fas fa-shield-alt text-success me-1"></i> Anti-Fraud Escrow Protected • Lifetime Access Included
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-edu-secondary btn-sm px-3" data-bs-dismiss="modal" onclick="stopDemoVideo()">Close Preview</button>
                        <a href="#" id="demoModalEnrollBtn" class="btn btn-edu-primary btn-sm px-4">
                            <i class="fas fa-lock me-1"></i> Enroll in Course
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Modal Global Controller -->
    <script>
        window.openDemoModal = function(title, videoUrl, checkoutUrl) {
            var modalEl = document.getElementById('demoVideoModal');
            if (!modalEl) return;
            var titleEl = document.getElementById('demoModalTitle');
            if (titleEl) titleEl.textContent = title || 'Course Preview';

            var enrollBtn = document.getElementById('demoModalEnrollBtn');
            if (enrollBtn && checkoutUrl) enrollBtn.href = checkoutUrl;

            var videoEl = document.getElementById('demoVideoPlayer');
            var iframeEl = document.getElementById('demoVideoIframe');
            var sourceEl = document.getElementById('demoVideoSource');

            var cleanUrl = (videoUrl || '').trim();
            if (cleanUrl.indexOf('youtube.com') !== -1 || cleanUrl.indexOf('youtu.be') !== -1) {
                var embedUrl = cleanUrl;
                if (cleanUrl.indexOf('watch?v=') !== -1) {
                    embedUrl = cleanUrl.replace('watch?v=', 'embed/');
                } else if (cleanUrl.indexOf('youtu.be/') !== -1) {
                    embedUrl = cleanUrl.replace('youtu.be/', 'youtube.com/embed/');
                }
                if (videoEl) videoEl.classList.add('d-none');
                if (iframeEl) {
                    iframeEl.src = embedUrl;
                    iframeEl.classList.remove('d-none');
                }
            } else {
                if (iframeEl) {
                    iframeEl.classList.add('d-none');
                    iframeEl.src = '';
                }
                if (sourceEl) {
                    sourceEl.src = cleanUrl || '';
                }
                if (videoEl && cleanUrl) {
                    videoEl.classList.remove('d-none');
                    videoEl.load();
                    videoEl.play().catch(function() {});
                } else if (videoEl) {
                    videoEl.classList.add('d-none');
                }
            }

            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        };

        window.stopDemoVideo = function() {
            var videoEl = document.getElementById('demoVideoPlayer');
            if (videoEl) {
                videoEl.pause();
                videoEl.currentTime = 0;
            }
            var iframeEl = document.getElementById('demoVideoIframe');
            if (iframeEl) {
                iframeEl.src = '';
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            var modalEl = document.getElementById('demoVideoModal');
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', window.stopDemoVideo);
            }
        });
    </script>

    <!-- Currency Global Switcher -->
    <script src="{{ asset('js/currency-lang-switcher.js') }}"></script>

    <!-- Canvas Confetti & Dopamine Gamification Audio/XP Engine -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <script src="{{ asset('js/gamification-engine.js') }}"></script>

    <!-- Progressive Web App (PWA) Service Worker Registration & Install Engine -->
    <script>
        (function() {
            // Register Service Worker for offline resilience & PWA capabilities
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function(reg) {
                            console.log('[PWA] ServiceWorker registered with scope:', reg.scope);
                        })
                        .catch(function(err) {
                            console.warn('[PWA] ServiceWorker registration failed:', err);
                        });
                });
            }

            // Capture beforeinstallprompt event for 1-Click Native App Installation
            var deferredPrompt = null;
            window.addEventListener('beforeinstallprompt', function(e) {
                e.preventDefault();
                deferredPrompt = e;
                // Reveal install buttons in desktop navbar and mobile bottom nav
                document.querySelectorAll('.btn-pwa-install').forEach(function(el) {
                    el.classList.remove('d-none');
                    el.classList.add('d-inline-flex');
                });
            });

            window.installPwaApp = function() {
                if (!deferredPrompt) {
                    alert('To install Digital Academy on iOS, tap the Share icon and select "Add to Home Screen". On Chrome/Edge, click the install icon in your browser address bar.');
                    return;
                }
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choiceResult) {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('[PWA] User accepted the installation');
                    } else {
                        console.log('[PWA] User dismissed the installation');
                    }
                    deferredPrompt = null;
                    document.querySelectorAll('.btn-pwa-install').forEach(function(el) {
                        el.classList.add('d-none');
                        el.classList.remove('d-inline-flex');
                    });
                });
            };

            window.addEventListener('appinstalled', function() {
                console.log('[PWA] Digital Academy was installed successfully!');
                document.querySelectorAll('.btn-pwa-install').forEach(function(el) {
                    el.classList.add('d-none');
                    el.classList.remove('d-inline-flex');
                });
            });
        })();
    </script>

    @yield('scripts')
</body>
</html>
