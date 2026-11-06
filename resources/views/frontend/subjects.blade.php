@extends('frontend.master')

@section('title', 'Curriculum Modules & Subject Tracks – Digital Academy')

@section('content')
<div class="container py-4 py-md-5">

    <!-- Header Section -->
    <div class="text-center mx-auto mb-5" style="max-width: 680px;">
        <span class="edu-hero-tag mb-2">
            <i class="fas fa-layer-group"></i> Academic Structure
        </span>
        <h1 class="edu-section-title mb-2">Curriculum Modules & Learning Tracks</h1>
        <p class="edu-section-sub mb-4">
            Structured modules across all cohort courses, complete with high-definition video lessons, architecture whitepapers, and downloadable handwritten notes.
        </p>

        <a href="{{ route('courses.catalog') }}" class="btn-edu-secondary btn-sm px-4">
            <i class="fas fa-compass me-1"></i> Browse Full Course Catalog
        </a>
    </div>

    <!-- Modules Grid -->
    <div class="row g-4">
        @forelse($subjects as $subject)
            @php
                $course = $subject->course;
                $materialsCount = $subject->materials ? $subject->materials->count() : 0;
                $videoCount = $subject->materials ? $subject->materials->where('type', 'video')->count() : 0;
                $pdfCount = $subject->materials ? $subject->materials->where('type', 'pdf')->count() : 0;
                $notesCount = $subject->materials ? $subject->materials->where('type', 'handwritten')->count() : 0;
            @endphp
            <div class="col-sm-6 col-lg-4">
                <div class="edu-card-panel h-100 d-flex flex-column justify-content-between p-4 shadow-sm position-relative">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 small px-2.5 py-1">
                                <i class="fas fa-book-open me-1"></i> Module
                            </span>
                            @if($subject->plan)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 small">
                                    {{ $subject->plan }}
                                </span>
                            @endif
                        </div>

                        <h3 class="fw-bold mb-2 fs-5">
                            {{ $subject->name }}
                        </h3>

                        @if($course)
                            <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded" style="background: var(--bg-secondary);">
                                <img src="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=120' }}" 
                                     alt="{{ $course->name }}" 
                                     class="rounded" 
                                     style="width: 38px; height: 38px; object-fit: cover;">
                                <div class="overflow-hidden">
                                    <div class="text-muted small" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 600;">Part of Course</div>
                                    <a href="{{ route('courses.detail', $course->id) }}" class="fw-semibold small text-truncate d-block text-decoration-none" style="color: var(--text-main);">
                                        {{ $course->name }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex flex-wrap gap-2 text-muted small my-3">
                            <span class="d-inline-flex align-items-center gap-1">
                                <i class="fas fa-play-circle text-primary"></i> {{ $videoCount }} Videos
                            </span>
                            <span>•</span>
                            <span class="d-inline-flex align-items-center gap-1">
                                <i class="fas fa-file-pdf text-danger"></i> {{ $pdfCount }} PDFs
                            </span>
                            <span>•</span>
                            <span class="d-inline-flex align-items-center gap-1">
                                <i class="fas fa-pen-fancy text-warning"></i> {{ $notesCount }} Notes
                            </span>
                        </div>
                    </div>

                    <div class="pt-3 border-top" style="border-color: var(--border-color) !important;">
                        @if($course)
                            <a href="{{ route('frontend.materials', ['id' => $course->id]) }}" class="btn-edu-primary btn-sm w-100 justify-content-center">
                                <i class="fas fa-chalkboard me-1"></i> Open Learning Workspace
                            </a>
                        @else
                            <a href="{{ route('courses.catalog') }}" class="btn-edu-secondary btn-sm w-100 justify-content-center">
                                Explore Courses
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="edu-card-panel py-5 max-w-500 mx-auto" style="max-width: 500px;">
                    <i class="fas fa-layer-group fa-3x text-muted mb-3 opacity-50"></i>
                    <h4 class="fw-bold mb-2">No Modules Found</h4>
                    <p class="text-muted small mb-4">Curriculum modules will appear here as instructors publish syllabus units.</p>
                    <a href="{{ route('courses.catalog') }}" class="btn-edu-primary px-4">Browse Courses</a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
