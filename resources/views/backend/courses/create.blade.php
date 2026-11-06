@extends('backend.master')

@section('title', 'Add Course - Digital Academy')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-center text-uppercase"><strong>Add New Course</strong></h5>
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

                <form action="{{ route('courses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label text-white">Course Name:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Basic, Standard, Premium" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label text-white">Description (optional):</label>
                        <textarea id="description" name="description" class="form-control" rows="4" placeholder="Course description...">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Add Course</button>
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-2 ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
