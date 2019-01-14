@extends('layouts.AdminLayout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card   col-8 mx-auto mt-5">
                <div class="card-header">Update  URL</div>
                <div class="card-body">
                    @if(count($errors) > 0)
                        @foreach($errors->all() as $error)
                            {{$error}}
                        @endforeach
                    @endif
                <form action="{{route('url-list.update',$data->id)}}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="exampleInputEmail1">Url</label>
                        <input class="form-control" type="text" name="url" value="{{ $data->url }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Title</label>
                        <input class="form-control" type="text" name="title" value="{{ $data->title }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Description</label>
                        <textarea class="form-control" type="text" name="description" >
                            {{ $data->description }}
                        </textarea>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Category</label>
                        <select name="category"  class="form-control" style="height: calc(1.7rem + 1px);">
                        @foreach($cat as $ct)
                                <option @if($data->category == $ct->id) selected @endif value="{{ $ct->id }}">{{ $ct->title }}</option>
                       @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Ip</label>
                        <input class="form-control" type="text" name="ip" value="{{ $data->ip }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Ip Region</label>
                        <input class="form-control" type="text" name="ipregion" value="{{ $data->ipregion }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Data Source</label>
                        <input class="form-control" type="text" name="datasource" value="{{ $data->datasource }}">
                    </div>
                    <button type="submit" class="btn btn-primary" name="Update" style="float: left;">
                        Update
                    </button>

                </form>
                        <div class="text-center" style="float: left;margin-left: 21px;">
                            <a href="{{ URL::previous() }}"  class="btn btn-success">
                                Go Back
                            </a>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection