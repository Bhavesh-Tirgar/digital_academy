@extends('backend.master')
@section('title', 'Digital Academy')
@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-12">
        <h5 class="text-center text-uppercase"><strong>Blog List</strong></h5>

        @if(session('info'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <a href="{{ route('blogs.create') }}" class="btn btn-primary mb-2">
            <i class="fa fa-plus-circle"></i> Add Blog
        </a>

        <table class="table table-bordered table-hover">
            <thead class="bg-danger text-center text-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Content</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="text-center">
                @foreach($blogs as $blog)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $blog->title }}</td>
                    <td>
                        <img src="{{ asset('storage/blog-images/' . $blog->image) }}" alt="Blog Image" width="100px">
                    </td>
                    <td>{{ Str::limit($blog->content, 50) }}</td> {{-- ✅ Limits content display --}}
                    <td>{{ $blog->created_at->format('d-M-y') }}</td>
                    <td>
                        <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-primary btn-sm rounded-pill">
                                <i class="far fa-edit"></i> Edit
                            </a>
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill"
                                onclick="return confirm('Are you sure you want to delete this blog?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination (Uncomment if needed) --}}
        {{-- {{ $blogs->links() }} --}}
    </div>
</div>
@endsection
