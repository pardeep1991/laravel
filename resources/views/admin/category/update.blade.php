@extends('layouts.AdminLayout')
@section('content')
    <div class="container">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header">Update Category</div>
            <div class="card-body">
                @if(count($errors) > 0)
                    @foreach($errors->all() as $error)
                        {{$error}}
                    @endforeach
                @endif
                <form action="{{route('category.update',$data->id)}}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="exampleInputEmail1">Title</label>
                        <input class="form-control" type="text" name="title" value="{{ $data->title }}">
                    </div>
                    <input type="submit" class="btn btn-primary" name="submit">
                    </input>
                </form>
                <div class="text-center">

                </div>
            </div>
        </div>
    </div>
@endsection