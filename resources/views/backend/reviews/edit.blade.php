@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Review</h2>
    <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
        @csrf
        @method('PUT')
        <textarea name="review" class="form-control" required>{{ $review->review }}</textarea>
        <label>Rating:</label>
        <select name="rating" class="form-control" required>
            <option value="5" {{ $review->rating == 5 ? 'selected' : '' }}>★★★★★</option>
            <option value="4" {{ $review->rating == 4 ? 'selected' : '' }}>★★★★☆</option>
            <option value="3" {{ $review->rating == 3 ? 'selected' : '' }}>★★★☆☆</option>
            <option value="2" {{ $review->rating == 2 ? 'selected' : '' }}>★★☆☆☆</option>
            <option value="1" {{ $review->rating == 1 ? 'selected' : '' }}>★☆☆☆☆</option>
        </select>
        <button type="submit" class="btn btn-success mt-2">Update</button>
    </form>
</div>
@endsection
