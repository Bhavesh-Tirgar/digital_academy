@extends('backend.master')
@section('title', 'Create User - Digital Academy')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card bg-dark border-secondary border-opacity-25 shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="text-center text-uppercase mb-0"><strong>Create New User</strong></h5>
            </div>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf 
                <div class="card-body p-4">
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
                        <label for="name" class="text-white fw-bold mb-1">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Alex Johnson" required>
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="text-white fw-bold mb-1">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="alex@example.com" required>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="text-white fw-bold mb-1">Password</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="•••••••• (Min 8 characters)" required>
                        @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="role" class="text-white fw-bold mb-1">Platform Role</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="student">Student (Learner)</option>
                            <option value="instructor">Instructor (Course Creator & Broadcaster)</option>
                            <option value="admin">Administrator (Full Access)</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer p-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>  
    </div>
</div>
@endsection
