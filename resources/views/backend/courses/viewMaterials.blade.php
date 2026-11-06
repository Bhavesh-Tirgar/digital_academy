@extends('backend.master')

@section('title', 'Course Curriculum Materials - ' . $course->name)

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('courses.index') }}" class="text-secondary small text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Back to Courses
                </a>
                <span class="text-secondary small">•</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">
                    {{ $course->category ?? 'Course Modules' }}
                </span>
            </div>
            <h2 class="text-white fw-bold mb-1">Curriculum Materials: {{ $course->name }}</h2>
            <p class="text-secondary mb-0 small">
                Manage all video lessons, PDF handouts, and handwritten notes for this cohort.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('courses.uploadMaterial', $course->id) }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="fas fa-cloud-upload-alt me-1"></i> Upload Materials
            </a>
            <a href="{{ route('frontend.materials', ['id' => $course->id]) }}" target="_blank" class="btn btn-outline-info btn-sm px-3">
                <i class="fas fa-external-link-alt me-1"></i> Preview Learner View
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 d-flex align-items-center mb-4" role="alert" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3) !important;">
            <i class="fas fa-check-circle me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 d-flex align-items-center mb-4" role="alert" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3) !important;">
            <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Material Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card bg-dark border-0 shadow-sm p-3 rounded-3" style="background: #1c1c24 !important; border: 1px solid #2a2a33 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-secondary small text-uppercase fw-semibold">Total Materials</span>
                        <h3 class="text-white fw-bold mb-0 mt-1">{{ $materials->count() }}</h3>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(94, 92, 230, 0.15); color: #5e5ce6;">
                        <i class="fas fa-folder-open fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card bg-dark border-0 shadow-sm p-3 rounded-3" style="background: #1c1c24 !important; border: 1px solid #2a2a33 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-secondary small text-uppercase fw-semibold">Video Lessons</span>
                        <h3 class="text-white fw-bold mb-0 mt-1">{{ $materials->where('type', 'video')->count() }}</h3>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(59, 130, 246, 0.15); color: #3b82f6;">
                        <i class="fas fa-play-circle fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card bg-dark border-0 shadow-sm p-3 rounded-3" style="background: #1c1c24 !important; border: 1px solid #2a2a33 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-secondary small text-uppercase fw-semibold">PDF Guides</span>
                        <h3 class="text-white fw-bold mb-0 mt-1">{{ $materials->where('type', 'pdf')->count() }}</h3>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                        <i class="fas fa-file-pdf fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card bg-dark border-0 shadow-sm p-3 rounded-3" style="background: #1c1c24 !important; border: 1px solid #2a2a33 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-secondary small text-uppercase fw-semibold">Handwritten Notes</span>
                        <h3 class="text-white fw-bold mb-0 mt-1">{{ $materials->where('type', 'handwritten')->count() }}</h3>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                        <i class="fas fa-pen-fancy fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Materials Table Card -->
    <div class="card border-0 shadow-sm rounded-3" style="background: #1c1c24; border: 1px solid #2a2a33 !important;">
        <div class="card-header bg-transparent py-3 px-4 d-flex justify-content-between align-items-center border-bottom" style="border-color: #2a2a33 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-list text-primary"></i>
                <h5 class="text-white mb-0 fw-bold">Course Modules & Asset Files</h5>
            </div>
            @if(!$materials->isEmpty())
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmBulkDelete()">
                    <i class="fas fa-trash-alt me-1"></i> Delete Selected
                </button>
            @endif
        </div>

        <div class="card-body p-0">
            @if($materials->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3 text-secondary" style="font-size: 3rem;">
                        <i class="fas fa-folder-open opacity-25"></i>
                    </div>
                    <h5 class="text-white fw-semibold mb-2">No learning materials uploaded yet</h5>
                    <p class="text-secondary mb-4 small">Start adding video lessons, reference manuals, or notes to populate your student workspace.</p>
                    <a href="{{ route('courses.uploadMaterial', $course->id) }}" class="btn btn-primary px-4">
                        <i class="fas fa-plus me-1"></i> Upload First Material
                    </a>
                </div>
            @else
                <form action="{{ route('materials.bulkDelete') }}" method="POST" id="bulkDeleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="color: #d4d4d8;">
                            <thead style="background: #25252e; border-bottom: 2px solid #2a2a33;">
                                <tr>
                                    <th style="width: 40px;" class="ps-4">
                                        <input type="checkbox" class="form-check-input" id="selectAll" style="cursor: pointer;">
                                    </th>
                                    <th>Type</th>
                                    <th>Title & Module</th>
                                    <th>Plan Level</th>
                                    <th>Asset Preview</th>
                                    <th>Download</th>
                                    <th>Uploaded</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materials as $material)
                                    <tr style="border-bottom: 1px solid #25252e;">
                                        <td class="ps-4">
                                            <input type="checkbox" name="material_ids[]" value="{{ $material->id }}" class="form-check-input material-checkbox" style="cursor: pointer;">
                                        </td>
                                        <td>
                                            @if($material->type === 'video')
                                                <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">
                                                    <i class="fas fa-video me-1"></i> Video
                                                </span>
                                            @elseif($material->type === 'pdf')
                                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1">
                                                    <i class="fas fa-file-pdf me-1"></i> PDF
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1">
                                                    <i class="fas fa-pen-nib me-1"></i> Notes
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-white">{{ $material->title }}</div>
                                            <div class="small text-secondary">
                                                <i class="fas fa-layer-group me-1 opacity-75"></i>
                                                {{ $material->subject->name ?? 'General Curriculum' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-light border border-secondary">
                                                {{ $material->plan_level ?? 'Standard' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($material->type === 'video')
                                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2.5">
                                                    <i class="fas fa-play me-1"></i> Play
                                                </a>
                                            @elseif($material->type === 'pdf')
                                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="btn btn-sm btn-outline-danger py-1 px-2.5">
                                                    <i class="fas fa-file-pdf me-1"></i> View PDF
                                                </a>
                                            @elseif($material->type === 'handwritten')
                                                <img src="{{ asset('storage/' . $material->file_path) }}" 
                                                     alt="Preview" 
                                                     class="img-thumbnail bg-dark border-secondary p-1 rounded" 
                                                     style="max-width: 55px; max-height: 45px; object-fit: cover; cursor: pointer;"
                                                     onclick="openLightbox('{{ asset('storage/' . $material->file_path) }}', '{{ addslashes($material->title) }}')">
                                            @else
                                                <span class="text-secondary small">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ asset('storage/' . $material->file_path) }}" download class="btn btn-sm btn-outline-secondary py-1 px-2.5">
                                                <i class="fas fa-download me-1"></i> File
                                            </a>
                                        </td>
                                        <td class="small text-secondary">
                                            {{ $material->created_at ? $material->created_at->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" onclick="deleteSingleMaterial('{{ route('materials.delete', $material->id) }}', '{{ addslashes($material->title) }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Lightbox Modal for Handwritten Notes / Images -->
<div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border border-secondary shadow-lg">
            <div class="modal-header border-bottom border-secondary">
                <h6 class="modal-title text-white fw-bold" id="lightboxModalTitle">Image Preview</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2 text-center">
                <img id="lightboxModalImage" src="" alt="Handwritten preview" class="img-fluid rounded" style="max-height: 75vh;">
            </div>
        </div>
    </div>
</div>

<!-- Standalone Single Delete Form -->
<form id="singleDeleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.material-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
});

function confirmBulkDelete() {
    const checked = document.querySelectorAll('.material-checkbox:checked');
    if (checked.length === 0) {
        alert("Please select at least one material to delete.");
        return;
    }
    if (confirm(`Are you sure you want to permanently delete the selected ${checked.length} material(s)? This action cannot be undone.`)) {
        document.getElementById('bulkDeleteForm').submit();
    }
}

function deleteSingleMaterial(url, title) {
    if (confirm(`Are you sure you want to delete "${title}"?`)) {
        const form = document.getElementById('singleDeleteForm');
        form.action = url;
        form.submit();
    }
}

function openLightbox(src, title) {
    document.getElementById('lightboxModalImage').src = src;
    document.getElementById('lightboxModalTitle').textContent = title || 'Image Preview';
    const modal = new bootstrap.Modal(document.getElementById('imageLightboxModal'));
    modal.show();
}
</script>
@endsection
