@extends('frontend.master')

@section('title', 'Curriculum Studio: ' . $course->name . ' – Digital Academy')

@section('styles')
<style>
    .studio-dropzone {
        border: 2px dashed var(--border-color);
        background: var(--bg-secondary);
        border-radius: var(--radius-md);
        padding: 32px 20px;
        text-align: center;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        position: relative;
    }
    .studio-dropzone:hover, .studio-dropzone.dragover {
        border-color: var(--primary);
        background: var(--primary-light);
    }
    .studio-dropzone input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .material-type-pill {
        border-radius: 999px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .curriculum-module-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        margin-bottom: 12px;
        overflow: hidden;
    }
    .curriculum-item-row {
        padding: 10px 14px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        transition: background 0.15s ease;
    }
    .curriculum-item-row:last-child {
        border-bottom: none;
    }
    .curriculum-item-row:hover {
        background: var(--bg-secondary);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-lg-5 py-4 py-md-5">

    <!-- Studio Top Navigation & Course Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ Auth::user()->isAdmin() ? route('courses.index') : route('instructor.dashboard') }}" class="btn-edu-secondary btn-sm px-3">
                <i class="fas fa-arrow-left me-1"></i> {{ Auth::user()->isAdmin() ? 'Back to Courses' : 'Instructor Dashboard' }}
            </a>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-primary bg-opacity-20 text-primary small">{{ $course->level ?? 'All Levels' }}</span>
                    <span class="text-muted small">• {{ $course->category->name ?? 'Computer Science' }}</span>
                </div>
                <h2 class="fw-bold fs-4 mb-0">{{ $course->name }}</h2>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('courses.detail', $course->id) }}" class="btn-edu-secondary btn-sm px-3" target="_blank">
                <i class="fas fa-eye me-1"></i> Public Page
            </a>
            <a href="{{ route('frontend.materials', ['id' => $course->id]) }}" class="btn-edu-primary btn-sm px-3" target="_blank">
                <i class="fas fa-external-link-alt me-1"></i> Student Learning Console
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
            <i class="fas fa-check-circle fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
            <i class="fas fa-exclamation-triangle fs-5 me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
            <div class="fw-bold mb-1"><i class="fas fa-exclamation-circle me-1"></i> Please check the form errors:</div>
            <ul class="mb-0 small ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Curriculum Metrics Bar -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="edu-card-panel p-3 d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: var(--primary-light); color: var(--primary);">
                    <i class="fas fa-layer-group fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Modules</div>
                    <div class="fw-bold fs-5" id="metricTotalModules">{{ $subjects->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="edu-card-panel p-3 d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(59, 130, 246, 0.15); color: #3b82f6;">
                    <i class="fas fa-video fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Video Lessons</div>
                    <div class="fw-bold fs-5">{{ $allMaterials->where('type', 'video')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="edu-card-panel p-3 d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                    <i class="fas fa-file-pdf fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">PDF Guides</div>
                    <div class="fw-bold fs-5">{{ $allMaterials->where('type', 'pdf')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="edu-card-panel p-3 d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                    <i class="fas fa-pen-nib fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Lecture Notes</div>
                    <div class="fw-bold fs-5">{{ $allMaterials->where('type', 'handwritten')->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Studio Grid -->
    <div class="row g-4">

        <!-- Left Column: Material Upload Studio Console -->
        <div class="col-lg-7 col-xl-7">
            <div class="edu-card-panel p-4 p-md-5 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="fas fa-cloud-upload-alt text-primary me-2"></i> Add Curriculum Material</h4>
                        <p class="text-muted small mb-0">Upload video lectures, embed video links, or publish study resources for this course.</p>
                    </div>
                </div>

                <form action="{{ route('courses.storeMaterial', ['id' => $course->id]) }}" method="POST" enctype="multipart/form-data" id="materialUploadForm">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    <!-- Module / Subject Selector with Inline Add -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase letter-spacing-1">
                            Target Module / Chapter:
                        </label>
                        <div class="input-group">
                            <select name="subject_id" id="subject_id" class="form-select" required>
                                <option value="">-- Choose Module Chapter --</option>
                                @foreach($subjects as $subj)
                                    <option value="{{ $subj->id }}">{{ $subj->name }} ({{ $subj->materials->count() }} items)</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newModuleModal">
                                <i class="fas fa-plus me-1"></i> New Module
                            </button>
                        </div>
                    </div>

                    <!-- Material Type Tabs -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase letter-spacing-1">
                            Material Format:
                        </label>
                        <ul class="nav nav-pills gap-2" id="materialTypeTabs" role="tablist">
                            <li class="nav-item flex-fill">
                                <button class="btn btn-edu-secondary active w-100 text-center py-2" id="type-video-tab" data-bs-toggle="pill" data-bs-target="#tab-video" type="button">
                                    <i class="fas fa-video me-1 text-primary"></i> Video Lesson
                                </button>
                            </li>
                            <li class="nav-item flex-fill">
                                <button class="btn btn-edu-secondary w-100 text-center py-2" id="type-pdf-tab" data-bs-toggle="pill" data-bs-target="#tab-pdf" type="button">
                                    <i class="fas fa-file-pdf me-1 text-danger"></i> PDF Handout
                                </button>
                            </li>
                            <li class="nav-item flex-fill">
                                <button class="btn btn-edu-secondary w-100 text-center py-2" id="type-notes-tab" data-bs-toggle="pill" data-bs-target="#tab-notes" type="button">
                                    <i class="fas fa-pen-nib me-1 text-warning"></i> Notes & Worksheets
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content mb-4" id="materialTabContent">

                        <!-- TAB 1: Video Lesson -->
                        <div class="tab-pane fade show active" id="tab-video">
                            <div class="p-4 rounded" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-play-circle me-1"></i> Video Lecture Configuration</h6>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <input type="radio" class="btn-check" name="video_source_mode" id="modeUrl" value="url" checked onchange="toggleVideoSourceMode()">
                                        <label class="btn btn-outline-primary" for="modeUrl"><i class="fas fa-link me-1"></i> Stream URL</label>

                                        <input type="radio" class="btn-check" name="video_source_mode" id="modeFile" value="file" onchange="toggleVideoSourceMode()">
                                        <label class="btn btn-outline-primary" for="modeFile"><i class="fas fa-file-upload me-1"></i> Upload File</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-semibold mb-1">Lesson Title:</label>
                                    <input type="text" name="video_title" class="form-control" placeholder="e.g. Lesson 2: Microservices & Event-Driven Architecture">
                                </div>

                                <!-- Mode 1: Video URL / Embed Link -->
                                <div id="videoUrlSection" class="mb-3">
                                    <label class="text-muted small fw-semibold mb-1">
                                        Stream / Video URL (YouTube, Vimeo, CloudFront, AWS S3, or direct MP4/HLS):
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-globe text-primary"></i></span>
                                        <input type="url" name="video_url" id="videoUrlInput" class="form-control" placeholder="https://www.youtube.com/watch?v=... or https://cdn.example.com/lecture.mp4">
                                    </div>
                                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                                        <i class="fas fa-info-circle text-info me-1"></i> High speed: Embeds stream directly without server bandwidth or upload limits.
                                    </div>
                                </div>

                                <!-- Mode 2: Direct Video File Upload -->
                                <div id="videoFileSection" class="mb-3 d-none">
                                    <label class="text-muted small fw-semibold mb-1">Video File Upload (MP4, WebM, MOV):</label>
                                    <div class="studio-dropzone" id="videoDropzone">
                                        <input type="file" name="video[]" id="videoFileInput" accept="video/mp4,video/webm,video/quicktime" onchange="displaySelectedFiles(this, 'videoFileName')">
                                        <div class="display-6 text-primary mb-2"><i class="fas fa-cloud-upload-alt"></i></div>
                                        <div class="fw-semibold small mb-1">Drag and drop your video file here, or click to browse</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">Supports MP4, WebM, MOV (Up to 100MB)</div>
                                        <div id="videoFileName" class="mt-2 fw-bold text-success small"></div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <label class="text-muted small fw-semibold mb-1">Estimated Duration (Minutes):</label>
                                        <input type="number" name="duration_minutes" class="form-control" placeholder="e.g. 24" min="1" max="600">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="text-muted small fw-semibold mb-1">Access Scope:</label>
                                        <input type="text" class="form-control" value="All Enrolled Cohort Members" readonly>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-muted small fw-semibold mb-1">Lesson Summary / Key Takeaways:</label>
                                    <textarea name="transcript" rows="2" class="form-control" placeholder="Summary notes, timestamp markers, and prerequisites..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: PDF Resource -->
                        <div class="tab-pane fade" id="tab-pdf">
                            <div class="p-4 rounded" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                <h6 class="fw-bold mb-3 text-danger"><i class="fas fa-file-pdf me-1"></i> PDF Handout & Study Guide</h6>
                                <div class="mb-3">
                                    <label class="text-muted small fw-semibold mb-1">Document Title:</label>
                                    <input type="text" name="pdf_title" class="form-control" placeholder="e.g. System Design Cheat Sheet & Blueprint">
                                </div>
                                <div class="mb-2">
                                    <label class="text-muted small fw-semibold mb-1">Attach PDF File:</label>
                                    <div class="studio-dropzone" id="pdfDropzone">
                                        <input type="file" name="pdf[]" id="pdfFileInput" accept="application/pdf" multiple onchange="displaySelectedFiles(this, 'pdfFileName')">
                                        <div class="display-6 text-danger mb-2"><i class="fas fa-file-pdf"></i></div>
                                        <div class="fw-semibold small mb-1">Drag and drop PDF documents here, or click to browse</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">Supports PDF handouts up to 20MB</div>
                                        <div id="pdfFileName" class="mt-2 fw-bold text-success small"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: Handwritten Notes & Worksheets -->
                        <div class="tab-pane fade" id="tab-notes">
                            <div class="p-4 rounded" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                <h6 class="fw-bold mb-3 text-warning"><i class="fas fa-pen-nib me-1"></i> Lecture Notes & Solution Worksheets</h6>
                                <div class="mb-3">
                                    <label class="text-muted small fw-semibold mb-1">Resource Title:</label>
                                    <input type="text" name="handwritten_title" class="form-control" placeholder="e.g. Instructor Solution Diagrams & Walkthrough">
                                </div>
                                <div class="mb-2">
                                    <label class="text-muted small fw-semibold mb-1">Attach Files (PDF, PNG, JPG):</label>
                                    <div class="studio-dropzone" id="notesDropzone">
                                        <input type="file" name="handwritten_notes[]" id="notesFileInput" accept="image/*,application/pdf" multiple onchange="displaySelectedFiles(this, 'notesFileName')">
                                        <div class="display-6 text-warning mb-2"><i class="fas fa-images"></i></div>
                                        <div class="fw-semibold small mb-1">Drag and drop notes or image scans here, or click to browse</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">Supports PNG, JPG, WebP, PDF</div>
                                        <div id="notesFileName" class="mt-2 fw-bold text-success small"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Progress Visualizer -->
                    <div id="uploadProgressContainer" class="d-none mb-3">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span><i class="fas fa-spinner fa-spin me-1"></i> Processing & Uploading to Syllabus...</span>
                            <span id="uploadProgressPct" class="fw-bold text-primary">0%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" id="uploadProgressBar" style="width: 0%"></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-edu-primary w-100 justify-content-center py-2.5 fs-6" id="submitMaterialBtn">
                        <i class="fas fa-cloud-upload-alt me-2"></i> Save & Publish Material to Course
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Interactive Curriculum Syllabus Tree -->
        <div class="col-lg-5 col-xl-5">
            <div class="edu-card-panel p-4 p-md-4 shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-stream text-primary me-2"></i> Course Syllabus Tree</h5>
                    <button type="button" class="btn btn-edu-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#newModuleModal">
                        <i class="fas fa-plus me-1"></i> Add Module
                    </button>
                </div>
                <p class="text-muted small mb-3">Click on any module to review existing lessons, preview documents, or manage curriculum items.</p>

                <div id="syllabusModulesList">
                    @forelse($subjects as $moduleIndex => $subj)
                        <div class="curriculum-module-card shadow-sm">
                            <div class="p-3 d-flex justify-content-between align-items-center" style="background: var(--bg-secondary); cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#moduleCollapse{{ $subj->id }}">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary bg-opacity-20 text-primary small">#{{ $moduleIndex + 1 }}</span>
                                    <span class="fw-bold small">{{ $subj->name }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-secondary bg-opacity-25 text-muted small">{{ $subj->materials->count() }} items</span>
                                    <i class="fas fa-chevron-down text-muted small"></i>
                                </div>
                            </div>

                            <div class="collapse show" id="moduleCollapse{{ $subj->id }}">
                                @forelse($subj->materials as $mat)
                                    <div class="curriculum-item-row">
                                        <div class="d-flex align-items-center gap-2 text-truncate" style="max-width: 70%;">
                                            @if($mat->type === 'video')
                                                <i class="fas fa-play-circle text-primary"></i>
                                            @elseif($mat->type === 'pdf')
                                                <i class="fas fa-file-pdf text-danger"></i>
                                            @else
                                                <i class="fas fa-pen-nib text-warning"></i>
                                            @endif
                                            <div class="text-truncate">
                                                <span class="small fw-semibold d-block text-truncate">{{ $mat->title }}</span>
                                                <span class="text-muted small" style="font-size: 0.7rem;">
                                                    {{ ucfirst($mat->type) }}
                                                    @if($mat->duration_seconds)
                                                        • {{ round($mat->duration_seconds / 60) }}m
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-1">
                                            @if($mat->video_url)
                                                <a href="{{ $mat->video_url }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" title="Watch External Stream">
                                                    <i class="fas fa-external-link-alt fa-xs"></i>
                                                </a>
                                            @elseif($mat->file_path)
                                                <a href="{{ asset('storage/' . $mat->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info py-0 px-2" title="Preview File">
                                                    <i class="fas fa-eye fa-xs"></i>
                                                </a>
                                            @endif

                                            <form action="{{ route('materials.delete', $mat->id) }}" method="POST" onsubmit="return confirm('Remove \'{{ addslashes($mat->title) }}\' from course curriculum?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="Delete Material">
                                                    <i class="fas fa-trash fa-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-3 text-center text-muted small">
                                        <em>No materials uploaded in this module yet.</em>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            <i class="fas fa-folder-open fa-2x mb-2 text-muted"></i>
                            <div>No curriculum modules created yet.</div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#newModuleModal">
                                <i class="fas fa-plus me-1"></i> Create Your First Module
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Escrow & Syllabus Integrity Guidance -->
            <div class="edu-card-panel p-3 shadow-sm border-start border-4 border-success">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-shield-alt text-success fs-5"></i>
                    <h6 class="fw-bold mb-0">Anti-Fraud Escrow Status</h6>
                </div>
                <p class="text-muted small mb-2">
                    {{ $course->is_completed ? '✅ Course verified 100% complete. Revenue released to your wallet immediately.' : '⏳ Course in progress. Upload all modules & exam to unlock escrow release.' }}
                </p>
                <a href="{{ route('instructor.dashboard') }}" class="small fw-bold text-primary text-decoration-none">
                    View Wallet & Payouts <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

    </div>

</div>

<!-- Modal: Quick Add Module / Chapter -->
<div class="modal fade" id="newModuleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title fw-bold"><i class="fas fa-layer-group text-primary me-2"></i> Add New Syllabus Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Module Title:</label>
                    <input type="text" id="new_module_name" class="form-control" placeholder="e.g. Module 3: Redis Distributed Caching">
                </div>
                <div id="moduleAjaxNotice" class="small"></div>
            </div>
            <div class="modal-footer p-3" style="border-top: 1px solid var(--border-color); background: var(--bg-secondary);">
                <button type="button" class="btn-edu-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-edu-primary" onclick="createModuleAjax()">Create Module</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleVideoSourceMode() {
        const isUrl = document.getElementById('modeUrl').checked;
        const urlSec = document.getElementById('videoUrlSection');
        const fileSec = document.getElementById('videoFileSection');
        if (isUrl) {
            urlSec.classList.remove('d-none');
            fileSec.classList.add('d-none');
        } else {
            urlSec.classList.add('d-none');
            fileSec.classList.remove('d-none');
        }
    }

    function displaySelectedFiles(input, labelId) {
        const label = document.getElementById(labelId);
        if (!label) return;
        if (input.files && input.files.length > 0) {
            const names = Array.from(input.files).map(f => `${f.name} (${(f.size / (1024 * 1024)).toFixed(1)} MB)`).join(', ');
            label.textContent = `Selected: ${names}`;
        } else {
            label.textContent = '';
        }
    }

    // Drag and drop visual cues
    ['videoDropzone', 'pdfDropzone', 'notesDropzone'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('dragover', (e) => { e.preventDefault(); el.classList.add('dragover'); });
        el.addEventListener('dragleave', () => el.classList.remove('dragover'));
        el.addEventListener('drop', () => el.classList.remove('dragover'));
    });

    // Form submission animated progress
    const form = document.getElementById('materialUploadForm');
    if (form) {
        form.addEventListener('submit', function () {
            const btn = document.getElementById('submitMaterialBtn');
            const progCont = document.getElementById('uploadProgressContainer');
            const progBar = document.getElementById('uploadProgressBar');
            const progPct = document.getElementById('uploadProgressPct');

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Uploading to Syllabus...';
            }
            if (progCont) progCont.classList.remove('d-none');

            let progress = 15;
            const interval = setInterval(() => {
                progress += Math.floor(Math.random() * 15) + 5;
                if (progress > 90) {
                    clearInterval(interval);
                    progress = 92;
                }
                if (progBar) progBar.style.width = progress + '%';
                if (progPct) progPct.textContent = progress + '%';
            }, 300);
        });
    }

    // Ajax module creator
    async function createModuleAjax() {
        const input = document.getElementById('new_module_name');
        const name = (input ? input.value : '').trim();
        const notice = document.getElementById('moduleAjaxNotice');
        if (!name) {
            if (notice) {
                notice.className = 'small text-danger';
                notice.textContent = 'Please enter a module title.';
            }
            return;
        }

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
            if (data.status === 'success' || data.success) {
                if (notice) {
                    notice.className = 'small text-success';
                    notice.textContent = 'Module added successfully! Adding to syllabus...';
                }

                // Add to select dropdown
                const sel = document.getElementById('subject_id');
                if (sel) {
                    const opt = document.createElement('option');
                    opt.value = data.subject.id;
                    opt.textContent = `${data.subject.name} (0 items)`;
                    opt.selected = true;
                    sel.appendChild(opt);
                }

                input.value = '';

                // Close modal and refresh or update syllabus tree
                setTimeout(() => {
                    const modalEl = document.getElementById('newModuleModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    window.location.reload();
                }, 700);
            } else {
                if (notice) {
                    notice.className = 'small text-danger';
                    notice.textContent = data.message || 'Failed to create module.';
                }
            }
        } catch (err) {
            if (notice) {
                notice.className = 'small text-danger';
                notice.textContent = err.message || 'Network error.';
            }
        }
    }
</script>
@endsection
