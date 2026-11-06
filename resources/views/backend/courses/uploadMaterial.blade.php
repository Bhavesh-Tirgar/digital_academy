@extends('backend.master')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('courses.index') }}" class="text-muted small text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i> Back to Courses
            </a>
            <h3 class="text-white fw-bold mb-0 mt-1">Upload Learning Materials: {{ $course->name }}</h3>
        </div>
        <a href="{{ route('frontend.materials', ['id' => $course->id]) }}" target="_blank" class="btn btn-outline-info btn-sm">
            <i class="fas fa-external-link-alt me-1"></i> Preview Student Workspace
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card bg-dark border-secondary border-opacity-25 shadow p-4">
                <form action="{{ route('courses.storeMaterial', ['id' => $course->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    <!-- Module / Subject Selector -->
                    <div class="mb-4">
                        <label class="form-label text-white fw-bold">Select Module / Subject Chapter:</label>
                        <select name="subject_id" id="subject_id" class="form-select bg-dark border-secondary text-white" required>
                            <option value="">-- Choose Module --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 1. Video Lesson Upload -->
                    <div class="p-3 rounded mb-4 border border-secondary border-opacity-25" style="background: rgba(255, 255, 255, 0.02);">
                        <h6 class="text-white fw-bold mb-3"><i class="fas fa-video text-danger me-2"></i> 1. Video Lesson</h6>
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Lesson Video Title:</label>
                            <input type="text" name="video_title" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Lesson 1: Architecture & Data Flow">
                        </div>
                        <div class="mb-2">
                            <label class="text-muted small mb-1">Video Files (MP4, MOV, WebM):</label>
                            <input type="file" name="video[]" class="form-control bg-dark border-secondary text-white" multiple accept="video/*">
                        </div>
                    </div>

                    <!-- 2. PDF Study Guide Upload (No longer gated) -->
                    <div class="p-3 rounded mb-4 border border-secondary border-opacity-25" style="background: rgba(255, 255, 255, 0.02);">
                        <h6 class="text-white fw-bold mb-3"><i class="fas fa-file-pdf text-info me-2"></i> 2. PDF Study Guide & Handout</h6>
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Document Title:</label>
                            <input type="text" name="pdf_title" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Architecture Blueprint & Cheat Sheet">
                        </div>
                        <div class="mb-2">
                            <label class="text-muted small mb-1">PDF Files:</label>
                            <input type="file" name="pdf[]" class="form-control bg-dark border-secondary text-white" multiple accept="application/pdf">
                        </div>
                    </div>

                    <!-- 3. Notes & Worksheets Upload (No longer gated) -->
                    <div class="p-3 rounded mb-4 border border-secondary border-opacity-25" style="background: rgba(255, 255, 255, 0.02);">
                        <h6 class="text-white fw-bold mb-3"><i class="fas fa-pen-nib text-warning me-2"></i> 3. Lecture Notes & Source Code Worksheets</h6>
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Notes Title:</label>
                            <input type="text" name="handwritten_title" class="form-control bg-dark border-secondary text-white" placeholder="e.g. In-Depth Solution Notes">
                        </div>
                        <div class="mb-2">
                            <label class="text-muted small mb-1">Files (PDF, PNG, JPG):</label>
                            <input type="file" name="handwritten_notes[]" class="form-control bg-dark border-secondary text-white" multiple accept="image/*,application/pdf">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-cloud-upload-alt me-2"></i> Upload All Materials to Syllabus
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side: Quick Add Module / Chapter -->
        <div class="col-lg-4">
            <div class="card bg-dark border-secondary border-opacity-25 shadow p-4 mb-4">
                <h5 class="text-white fw-bold mb-3"><i class="fas fa-plus-circle text-primary me-2"></i> Add Module / Chapter</h5>
                <div class="mb-3">
                    <label class="text-muted small mb-1">Module Name:</label>
                    <input type="text" id="new_subject" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Module 3: Redis In-Memory Caching">
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addSubject()">
                    Create Module
                </button>
                <div id="subject-message" class="small mt-2"></div>
            </div>

            <div class="card bg-dark border-secondary border-opacity-25 shadow p-4">
                <h6 class="text-white fw-bold mb-2">Modern Ed-Tech Architecture:</h6>
                <p class="text-muted small mb-0">
                    All course lessons, PDFs, and worksheets are automatically unlocked for enrolled students with full syllabus access.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    async function addSubject() {
        const input = document.getElementById('new_subject');
        const name = input.value.trim();
        const msg = document.getElementById('subject-message');
        if (!name) return;

        try {
            const res = await fetch("{{ route('courses.addSubject') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    course_id: {{ $course->id }},
                    name: name
                })
            });
            const data = await res.json();
            if (data.status === 'success') {
                msg.className = 'small text-success mt-2';
                msg.textContent = 'Module added! Refreshing list...';
                const sel = document.getElementById('subject_id');
                const opt = document.createElement('option');
                opt.value = data.subject.id;
                opt.textContent = data.subject.name;
                opt.selected = true;
                sel.appendChild(opt);
                input.value = '';
            } else {
                msg.className = 'small text-danger mt-2';
                msg.textContent = data.message || 'Error adding module.';
            }
        } catch (e) {
            msg.className = 'small text-danger mt-2';
            msg.textContent = e.message;
        }
    }
</script>
@endsection
