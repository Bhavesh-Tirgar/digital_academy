@extends('frontend.master')

@section('title', 'Curriculum Materials – ' . $course->name)

@section('content')
<div class="container-fluid px-lg-5 py-4 py-md-5">

    <!-- Top Bar Navigation -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ Auth::user()->isAdmin() ? route('courses.index') : route('instructor.dashboard') }}" class="btn-edu-secondary btn-sm px-3">
                <i class="fas fa-arrow-left me-1"></i> {{ Auth::user()->isAdmin() ? 'Back to Courses' : 'Instructor Dashboard' }}
            </a>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-primary bg-opacity-20 text-primary small">{{ $course->level ?? 'Curriculum' }}</span>
                    <span class="text-muted small">• {{ $course->category->name ?? 'Computer Science' }}</span>
                </div>
                <h2 class="fw-bold fs-4 mb-0">Curriculum Materials: {{ $course->name }}</h2>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('courses.uploadMaterial', $course->id) }}" class="btn btn-edu-primary btn-sm px-3">
                <i class="fas fa-cloud-upload-alt me-1"></i> Upload Materials
            </a>
            <a href="{{ route('frontend.materials', ['id' => $course->id]) }}" target="_blank" class="btn btn-edu-secondary btn-sm px-3">
                <i class="fas fa-external-link-alt me-1"></i> Student View
            </a>
        </div>
    </div>

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

    <!-- Material Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="edu-card-panel p-3 d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: var(--primary-light); color: var(--primary);">
                    <i class="fas fa-folder-open fs-5"></i>
                </div>
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Total Materials</span>
                    <h3 class="fw-bold mb-0 mt-1 fs-4">{{ $materials->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="edu-card-panel p-3 d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(59, 130, 246, 0.15); color: #3b82f6;">
                    <i class="fas fa-play-circle fs-5"></i>
                </div>
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Video Lessons</span>
                    <h3 class="fw-bold mb-0 mt-1 fs-4">{{ $materials->where('type', 'video')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="edu-card-panel p-3 d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                    <i class="fas fa-file-pdf fs-5"></i>
                </div>
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">PDF Guides</span>
                    <h3 class="fw-bold mb-0 mt-1 fs-4">{{ $materials->where('type', 'pdf')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="edu-card-panel p-3 d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                    <i class="fas fa-pen-fancy fs-5"></i>
                </div>
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Handwritten Notes</span>
                    <h3 class="fw-bold mb-0 mt-1 fs-4">{{ $materials->where('type', 'handwritten')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Materials Table Panel -->
    <div class="edu-card-panel p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0"><i class="fas fa-list text-primary me-2"></i> All Published Materials</h5>
            <a href="{{ route('courses.uploadMaterial', $course->id) }}" class="btn btn-edu-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add More Materials
            </a>
        </div>

        <div class="table-responsive">
            <table class="table edu-table align-middle">
                <thead>
                    <tr>
                        <th>Material Title</th>
                        <th>Module / Chapter</th>
                        <th>Type</th>
                        <th>Source / File</th>
                        <th>Uploaded On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $mat)
                        <tr>
                            <td class="fw-bold">
                                @if($mat->type === 'video')
                                    <i class="fas fa-play-circle text-primary me-2"></i>
                                @elseif($mat->type === 'pdf')
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                @else
                                    <i class="fas fa-pen-nib text-warning me-2"></i>
                                @endif
                                {{ $mat->title }}
                            </td>
                            <td class="text-muted small">
                                <span class="badge bg-secondary bg-opacity-25 text-muted">{{ $mat->subject->name ?? 'General Module' }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $mat->type === 'video' ? 'bg-primary' : ($mat->type === 'pdf' ? 'bg-danger' : 'bg-warning') }} bg-opacity-10 {{ $mat->type === 'video' ? 'text-primary' : ($mat->type === 'pdf' ? 'text-danger' : 'text-warning') }} small">
                                    {{ ucfirst($mat->type) }}
                                </span>
                            </td>
                            <td class="small">
                                @if($mat->video_url)
                                    <a href="{{ $mat->video_url }}" target="_blank" class="text-primary text-decoration-none text-truncate d-inline-block" style="max-width: 220px;">
                                        <i class="fas fa-external-link-alt me-1"></i> {{ $mat->video_url }}
                                    </a>
                                @elseif($mat->file_path)
                                    <a href="{{ asset('storage/' . $mat->file_path) }}" target="_blank" class="text-info text-decoration-none">
                                        <i class="fas fa-file-download me-1"></i> {{ basename($mat->file_path) }}
                                    </a>
                                @else
                                    <span class="text-muted">Direct Lesson</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $mat->created_at ? $mat->created_at->format('M d, Y') : 'N/A' }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    @if($mat->video_url)
                                        <a href="{{ $mat->video_url }}" target="_blank" class="btn btn-sm btn-edu-secondary py-1 px-2" title="Watch Stream">
                                            <i class="fas fa-play fa-xs"></i>
                                        </a>
                                    @elseif($mat->file_path)
                                        <a href="{{ asset('storage/' . $mat->file_path) }}" target="_blank" class="btn btn-sm btn-edu-secondary py-1 px-2" title="View File">
                                            <i class="fas fa-eye fa-xs"></i>
                                        </a>
                                    @endif

                                    <form action="{{ route('materials.delete', $mat->id) }}" method="POST" onsubmit="return confirm('Permanently delete \'{{ addslashes($mat->title) }}\'?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete">
                                            <i class="fas fa-trash fa-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3 d-block"></i>
                                <h6 class="fw-bold mb-1">No curriculum materials uploaded yet</h6>
                                <p class="small text-muted mb-3">Begin by uploading your video lessons, slides, or study guides.</p>
                                <a href="{{ route('courses.uploadMaterial', $course->id) }}" class="btn btn-edu-primary btn-sm">
                                    Upload First Material
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
