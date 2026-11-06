@extends('backend.master')

@section('title', 'Edit Review - Digital Academy')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white mb-0">Edit Review</h2>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Reviews
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label text-muted">User</label>
                    <input type="text" class="form-control" value="{{ optional($review->user)->name ?? 'Anonymous' }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Course</label>
                    <input type="text" class="form-control" value="{{ optional($review->course)->name ?? 'General Platform Review' }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Rating</label>
                    <select name="rating" class="form-select" required>
                        <option value="5" {{ $review->rating == 5 ? 'selected' : '' }}>★★★★★ (5 Stars)</option>
                        <option value="4" {{ $review->rating == 4 ? 'selected' : '' }}>★★★★☆ (4 Stars)</option>
                        <option value="3" {{ $review->rating == 3 ? 'selected' : '' }}>★★★☆☆ (3 Stars)</option>
                        <option value="2" {{ $review->rating == 2 ? 'selected' : '' }}>★★☆☆☆ (2 Stars)</option>
                        <option value="1" {{ $review->rating == 1 ? 'selected' : '' }}>★☆☆☆☆ (1 Star)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Review Content</label>
                    <textarea name="review" rows="4" class="form-control" required maxlength="1000">{{ $review->review }}</textarea>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
