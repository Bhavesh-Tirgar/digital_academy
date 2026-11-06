<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Digital Academy') }}</title>

    <!-- Pre-render Theme Check (prevents theme flicker) -->
    <script>
        (function() {
            var savedTheme = localStorage.getItem('da_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Modern Academy Styles -->
    <link rel="stylesheet" href="{{ asset('css/modern-academy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body style="background-color: var(--bg-main) !important; color: var(--text-primary) !important;">
    <div class="ambient-glow"></div>

    <div id="app" class="position-relative" style="z-index: 1;">
        <nav class="navbar navbar-expand-md modern-navbar">
            <div class="container">
                <a class="navbar-brand navbar-brand-logo" href="{{ url('/') }}">
                    <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
                    <span>Digital<span style="color: var(--primary);">Academy</span></span>
                </a>

                <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#appNavbar">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="appNavbar">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom" href="{{ route('courses.catalog') }}">Courses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom" href="{{ route('schedule.index') }}">Live Schedule</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom" href="{{ route('ebooks.index') }}">E-Books</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom" href="{{ route('pricing.index') }}">Membership</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center gap-2">
                        <!-- Theme Toggle Button -->
                        <li class="nav-item">
                            <button id="themeToggleBtnApp" class="theme-toggle-btn" type="button" aria-label="Toggle Theme" title="Switch Theme (Dark / Light)">
                                <i class="fas fa-moon" id="themeIconApp"></i>
                            </button>
                        </li>

                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="btn btn-glass btn-sm" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn-glow-primary btn-sm" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="btn btn-glass dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1 text-info"></i> {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" style="background: var(--dropdown-bg); border: 1px solid var(--border-color);">
                                    <li class="px-3 py-2 border-bottom border-secondary border-opacity-25">
                                        <div class="fw-bold text-white small">{{ Auth::user()->name }}</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                                    </li>
                                    @if(Auth::user()->isAdmin())
                                        <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i class="fas fa-shield-alt me-2 text-danger"></i> Admin Dashboard</a></li>
                                    @endif
                                    @if(Auth::user()->isInstructor() || Auth::user()->isAdmin())
                                        <li><a class="dropdown-item py-2" href="{{ route('instructor.dashboard') }}"><i class="fas fa-chalkboard-teacher me-2 text-success"></i> Instructor Portal</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider border-secondary border-opacity-25"></li>
                                    <li>
                                        <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Flash messages -->
        @if(session('info') || session('success') || session('error'))
            <div class="container mt-4">
                @if(session('info'))
                    <div class="alert alert-info border-0 text-white" style="background: rgba(99, 102, 241, 0.2); border-left: 4px solid var(--primary) !important;">
                        <i class="fas fa-info-circle me-2 text-info"></i> {{ session('info') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success border-0 text-white" style="background: rgba(16, 185, 129, 0.2); border-left: 4px solid var(--accent-emerald) !important;">
                        <i class="fas fa-check-circle me-2 text-success"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger border-0 text-white" style="background: rgba(244, 63, 94, 0.2); border-left: 4px solid var(--accent-rose) !important;">
                        <i class="fas fa-exclamation-triangle me-2 text-danger"></i> {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        <main class="py-4">
            @yield('content')
        </main>

        <!-- Mobile App-Style Bottom Navigation Bar (< 768px) -->
        <nav class="mobile-bottom-nav">
            <a href="{{ route('frontend.home') }}" class="mobile-nav-item {{ request()->routeIs('frontend.home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('courses.catalog') }}" class="mobile-nav-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                <i class="fas fa-graduation-cap"></i>
                <span>Courses</span>
            </a>
            <a href="{{ route('schedule.index') }}" class="mobile-nav-item {{ request()->routeIs('schedule.*') ? 'active' : '' }}">
                <i class="fas fa-broadcast-tower"></i>
                <span class="live-dot"></span>
                <span>Live</span>
            </a>
            <a href="{{ route('ebooks.index') }}" class="mobile-nav-item {{ request()->routeIs('ebooks.*') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i>
                <span>E-Books</span>
            </a>
            @if(Auth::check())
                <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isInstructor() ? route('instructor.dashboard') : route('schedule.index')) }}" class="mobile-nav-item">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="mobile-nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Sign In</span>
                </a>
            @endif
        </nav>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme Toggle Controller -->
    <script>
        (function() {
            var themeBtn = document.getElementById('themeToggleBtnApp');
            var themeIcon = document.getElementById('themeIconApp');

            function updateIcon(theme) {
                if (themeIcon) {
                    if (theme === 'light') {
                        themeIcon.className = 'fas fa-sun text-warning';
                    } else {
                        themeIcon.className = 'fas fa-moon text-light';
                    }
                }
            }

            var currentTheme = localStorage.getItem('da_theme') || 'dark';
            updateIcon(currentTheme);

            if (themeBtn) {
                themeBtn.addEventListener('click', function() {
                    var cur = document.documentElement.getAttribute('data-theme') || 'dark';
                    var nextTheme = cur === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', nextTheme);
                    localStorage.setItem('da_theme', nextTheme);
                    updateIcon(nextTheme);
                });
            }
        })();
    </script>
</body>
</html>
