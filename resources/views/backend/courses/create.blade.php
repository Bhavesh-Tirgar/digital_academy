@extends('layouts.app')

@extends('layouts.admin')

@section('title', 'Digital Academy')

@section('content')
<div class="container">
    <h2>Add a Subject</h2>

    <form action="{{ route('subjects.store') }}" method="POST">
        @csrf
        <label>Course:</label>
        <select name="course_id" class="form-control">
            @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->name }}</option>
            @endforeach
        </select>

        <label>Subject Name:</label>
        <input type="text" name="name" class="form-control" required>

        <button type="submit" class="btn btn-primary mt-3">Add Subject</button>
    </form>
</div>
@endsection
