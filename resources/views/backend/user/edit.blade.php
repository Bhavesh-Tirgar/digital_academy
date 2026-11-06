@extends('backend.master')
@section('title', 'Update User - Digital Academy')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="text-center text-uppercase mb-0"><strong>User Update</strong></h5>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf 
                @method('PUT')
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

                    <div class="form-group mb-3">
                        <label for="name" class="text-white">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="text-white">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="role" class="text-white">Role</label>
                        <select name="role" id="role" class="form-select">
                            <option value="student" @if($user->role == 'student' || $user->role == 'user') selected @endif>Student (Learner)</option>
                            <option value="instructor" @if($user->role == 'instructor') selected @endif>Instructor (Course Creator)</option>
                            <option value="admin" @if($user->role == 'admin') selected @endif>Admin</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary mt-2">Update</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-2 ms-2">Cancel</a>
                </div>
            </form>
        </div>  
    </div>
</div>
@endsection
