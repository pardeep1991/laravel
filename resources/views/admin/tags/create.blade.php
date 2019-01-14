@extends('layouts.AdminLayout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card   col-8 mx-auto mt-5">
        <div class="card-header">Add New Tag</div>
        <div class="card-body">
            @if(count($errors) > 0)
                @foreach($errors->all() as $error)
                    {{$error}}
                @endforeach
            @endif
            <form action="{{route('tag.store')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Title</label>

                    <input class="form-control" type="text" name="title" value="">
                </div>
                <input type="submit" class="btn btn-primary" name="submit">
                </input>
            </form>
            <div class="text-center">

            </div>
        </div>
    </div>
</div>
    </div>
@endsection