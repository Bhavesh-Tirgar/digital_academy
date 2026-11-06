@extends('frontend.master')

@section('title', 'E-Book Slot – Download Free Tech Handbooks & Study Guides')

@section('content')
<div class="container py-5">

    <!-- Header & Search -->
    <div class="text-center max-w-700 mx-auto mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-3 py-1 mb-2">
            <i class="fas fa-book-reader me-1"></i> E-Book Slot & Digital Library
        </span>
        <h1 class="edu-section-title mb-2">Free Engineering E-Books & Cheat Sheets</h1>
        <p class="text-muted">High-yield study guides, architecture cheat sheets, and coding handbooks curated by our instructors. Over {{ number_format($totalDownloads) }} total downloads.</p>

        <!-- Search Form -->
        <form action="{{ route('ebooks.index') }}" method="GET" class="edu-hero-search mx-auto mt-4">
            <i class="fas fa-search text-muted"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search e-books by topic, title, or author...">
            <button type="submit" class="btn-edu-primary btn-sm px-3.5">Search</button>
        </form>
    </div>

    <!-- Category Filters -->
    <div class="topic-chips-wrapper justify-content-start justify-content-md-center mb-5">
        <a href="{{ route('ebooks.index') }}" class="topic-chip {{ !request('category') ? 'active' : '' }}">
            <i class="fas fa-th-large me-1"></i> All E-Books
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('ebooks.index', ['category' => $cat->slug]) }}" class="topic-chip {{ request('category') === $cat->slug ? 'active' : '' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- E-Books Grid -->
    <div class="row g-4">
        @forelse($ebooks as $ebook)
            <div class="col-md-6 col-lg-6">
                <div class="edu-card-panel h-100 p-4 shadow-sm">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm-4 text-center">
                            <img src="{{ $ebook->cover_image ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600' }}" alt="{{ $ebook->title }}" class="img-fluid rounded-3 shadow border" style="max-height: 200px; object-fit: cover;">
                        </div>
                        <div class="col-sm-8 d-flex flex-column justify-content-between h-100">
                            <div>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 mb-2 small">{{ $ebook->category->name ?? 'Technology' }}</span>
                                <h4 class="fw-bold mb-1 fs-5">{{ $ebook->title }}</h4>
                                <div class="text-muted small mb-2">By <strong>{{ $ebook->author }}</strong> • {{ $ebook->pages }} Pages</div>
                                <p class="text-muted small mb-3">{{ Str::limit($ebook->description, 120) }}</p>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center pt-3 border-top gap-2" style="border-color: var(--border-color) !important;">
                                <span class="small text-muted">
                                    <i class="fas fa-download text-primary me-1"></i> {{ number_format($ebook->downloads_count) }} downloads
                                </span>
                                <a href="{{ route('ebooks.download', $ebook->id) }}" class="btn-edu-primary btn-sm px-3">
                                    <i class="fas fa-file-pdf me-1"></i> Free PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="edu-card-panel py-5 max-w-500 mx-auto" style="max-width: 500px;">
                    <i class="fas fa-book-open fa-3x text-muted mb-3 opacity-50"></i>
                    <h4 class="fw-bold mb-2">No E-Books found</h4>
                    <p class="text-muted small mb-4">Try clearing your search query or choosing another category.</p>
                    <a href="{{ route('ebooks.index') }}" class="btn-edu-primary btn-sm px-4">View All E-Books</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $ebooks->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
