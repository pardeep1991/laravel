@extends('layouts.AdminLayout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card   col-8 mx-auto mt-5">
        <div class="card-header">Upload Csv File</div>
        <div class="card-body">
            @if($message = Session::get('success'))
                <div class="alert alert-info alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Success!</strong> {{ $message }}
                </div>
            @endif
            {!! Session::forget('success') !!}
            @if(count($errors) > 0)
                @foreach($errors->all() as $error)
                    {{$error}}
                @endforeach
            @endif
            <form action="{{'csvToArray'}}" method="post" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">File</label>

                    <input class="form-control" type="file" name="import_file" value="">
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