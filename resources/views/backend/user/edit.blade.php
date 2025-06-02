@extends('backend.master')
@section('title', 'Digital AcademysS')
@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="text-center text-uppercase"><strong>user update</strong></h5>
            </div>

            <form action="{{ url('admin/users/'.$user->id.'/update') }}" method="POST">
                @csrf 
            <div class="card-body">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" @error('name') is-invalid @enderror >
                    @error('name')
                    <span class="text-danger"> {{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" @error('name') is-invalid @enderror >
                    @error('email')
                    <span class="text-danger"> {{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status" id="" class="form-control">
                        <option value="">Select Status</option>
                        <option value="admin" @if($user->status == 'admin') selected @endif>
                            Admin</option>
                        <option value="user" @if($user->status == 'user') selected @endif>
                            User</option>
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary mt-3">Update</button>
            </div>
        </form>
        </div>  
    </div>
</div>
@endsection