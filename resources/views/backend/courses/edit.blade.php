@extends('backend.master')

@section('title', 'Edit Course - Digital Academy')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-center text-uppercase"><strong>Edit Course</strong></h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('courses.update', $course->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label text-white">Course Name:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $course->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label text-white">Description:</label>
                        <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Update Course</button>
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-2 ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
