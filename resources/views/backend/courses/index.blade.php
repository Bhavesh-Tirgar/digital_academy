@extends('backend.master')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-white fw-bold mb-1">Course Curriculum & Materials</h3>
            <p class="text-muted small mb-0">Manage course modules, upload videos, PDFs and study guides.</p>
        </div>
        <a href="{{ route('courses.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Course
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card bg-dark border-secondary border-opacity-25 shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="text-uppercase small text-muted border-bottom border-secondary border-opacity-25">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Course Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Modules</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr class="border-bottom border-secondary border-opacity-10">
                                <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold text-white">{{ $course->name }}</div>
                                    <div class="text-muted small">{{ Str::limit($course->description, 60) }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-25 text-info">
                                        {{ $course->category->name ?? 'Tech' }}
                                    </span>
                                </td>
                                <td class="text-white fw-bold">${{ number_format($course->price, 0) }}</td>
                                <td>
                                    <span class="text-muted small">
                                        {{ $course->subjects->count() }} Modules
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('courses.uploadMaterial', ['id' => $course->id]) }}" class="btn btn-outline-info btn-sm" title="Upload Materials">
                                            <i class="fas fa-upload me-1"></i> Upload
                                        </a>
                                        <a href="{{ route('courses.viewMaterials', ['id' => $course->id]) }}" class="btn btn-outline-light btn-sm" title="View Syllabus">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this course and all materials?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No courses found. Click "Add New Course" above.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
