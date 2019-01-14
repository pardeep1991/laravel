@extends('layouts.AdminLayout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card   col-8 mx-auto mt-5">
        <div class="card-header">Add New URL</div>
        <div class="card-body">

                @if(count($errors) > 0)
                    @foreach($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{$error}}
                    </div>
                    @endforeach
                @endif


            <form action="{{route('url-list.store')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Url</label>

                    <input class="form-control" type="text" name="url" value="">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Title</label>

                    <input class="form-control" type="text" name="title" value="">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>

                    <input class="form-control" type="text" name="description" value="">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Category</label>

                    <select name="category" class="form-control">
                        @foreach($cat_data as $c_data)
                        <option value="{{$c_data->id}}">{{ $c_data->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Tag</label>
                    <select name="tags[]" multiple class="form-control">
                        @foreach($tag_data as $dt)
                            <option value="{{$dt->id}}">{{$dt->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Review</label>

                    <input class="form-control" type="text" name="review" value="">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Ip</label>

                    <input class="form-control" type="text" name="ip" value="">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Ip Region</label>

                    <input class="form-control" type="text" name="ipregion" value="">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Data Source</label>

                    <input class="form-control" type="text" name="datasource" value="">
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