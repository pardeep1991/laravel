@extends('layouts.AdminLayout')
@section('content')

        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Category Table</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        @foreach($data as $idt => $dt)
                           
                        <tr>
                            <td>{{ $idt+'1' }}</td>
                            <td>{{ $dt->title }}</td>
                            <td><div class="row">
                                    <div class="col-md-6">
                                            <a href="{{route('category.edit',$dt->id)}}" style="color: #6d7073;">
                                                <i class="pull-right fa fa-pencil" style="font-size:20px"></i>
                                            </a>
									</div>
									<div class="col-md-6">									
                                            <form action="{{route('category.destroy',$dt->id)}}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="bgnone">
                                                    <i class=" fa fa-trash-o" style="font-size:24px"></i>
                                                </button>
                                            </form>
                                    </div>
                                </div>
                            </td>



                        </tr>

                        @endforeach

                    </table>
                    <a href="{{route('category.create')}}" class="btn btn-primary">Add New </a>
                </div>

            </div>
        </div>

@endsection