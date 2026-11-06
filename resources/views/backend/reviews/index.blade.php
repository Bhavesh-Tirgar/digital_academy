@extends('backend.master')
@section('title', 'User Reviews - Digital Academy')
@section('content')
<div class="container-fluid">
    <h2 class="mb-4 text-white">User Reviews</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Course</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $key => $review)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ optional($review->user)->name ?? 'Anonymous' }}</td>
                            <td>{{ optional($review->course)->name ?? 'General' }}</td>
                            <td>{{ $review->rating }} ⭐</td>
                            <td>{{ $review->review }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No reviews found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $reviews->links() }}  {{-- Pagination --}}
            </div>
        </div>
    </div>
</div>
@endsection
