@extends('backend.master')
@section('title', 'Digital Academy')
@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-12">
       
        @if(Session('info'))
            <div class="alert alert-success alert-dismissible show fade">
                {{ Session('info')}}
                <a class="btn btn-close" data-bs-dismiss="alert" ></a>
            </div>
        @endif
      
        </div>
     <form action="{{url('admin/category')}}" method="POST">
        @csrf
        <div class="card text-center bg-info">
            <div class="card-header bg-success">
                <h5 class="text-center text-uppercase"><strong>Category create form</strong></h5>
            </div>
            <div class="card-body col-md-6" style="align-self: center">
                <label for="" class="text-uppercase">Name</label> <br>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Category Name." value="{{old('name')}}"> 
                @error('name') 
                    <span class="text-danger"><small>{{$message}}</small></span><br>
                @enderror

                <label for="" class="text-uppercase mt-3">Content</label> <br>
                <textarea class="form-control @error('content') is-invalid @enderror" name="content" placeholder="Enter Category Content"> {{old('content')}} </textarea>
                @error('content') 
                    <span class="text-danger"><small>{{$message}}</small></span><br>
                @enderror
            </div>  
            <button type="submit" class="btn btn-primary col-md-3 mb-3" style="align-self: center">Create</button> 
     </form>
    </div>
</div>
@endsection