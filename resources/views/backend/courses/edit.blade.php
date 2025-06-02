@extends('layouts.app')

@extends('layouts.admin')

@section('title', 'Digital Academy')

@section('content')
<div class="container">
    <h2>Edit Course</h2>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('courses.update', $course->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Course Name Input -->
        <div class="mb-3">
            <label for="name" class="form-label">Course Name:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $course->name) }}" required>
        </div>

        <!-- Course Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $course->description) }}</textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success">Update Course</button>
    </form>
</div>
@endsection
