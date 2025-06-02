@extends('layouts.admin')
@section('title', 'Edit Blog')

@extends('backend.master')

@extends('layouts.admin')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        @if(session('info'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-success text-white text-center">
                <h5 class="text-uppercase"><strong>Edit Blog</strong></h5>
            </div>
            <div class="card-body">
                <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="text-uppercase fw-bold">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            placeholder="Enter Title" value="{{ old('title', $blog->title) }}">
                        @error('title') 
                            <span class="text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>

                    {{-- Category Selection --}}
                    <div class="mb-3">
                        <label class="text-uppercase fw-bold">Category</label>
                        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') 
                            <span class="text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div class="mb-3">
                        <label class="text-uppercase fw-bold">Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        <br>
                        <img src="{{ asset('storage/blog-images/' . $blog->image) }}" 
                             alt="Blog Image" width="100px" style="border: 1px solid red;">
                        @error('image') 
                            <span class="text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>

                    {{-- Content --}}
                    <div class="mb-3">
                        <label class="text-uppercase fw-bold">Content</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                            name="content" placeholder="Enter Content">{{ old('content', $blog->content) }}</textarea>
                        @error('content') 
                            <span class="text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>

                    {{-- Status Selection --}}
                    <div class="mb-3">
                        <label class="text-uppercase fw-bold">Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status') 
                            <span class="text-danger"><small>{{ $message }}</small></span>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4">Update Blog</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
