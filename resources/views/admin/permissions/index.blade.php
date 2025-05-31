@extends('admin.layout.app')

@section('title', 'Permissions List')

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Permissions</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="row">
            <div class="box-body">
                <div class="col-md-4 col-xs-12">
                    <form method="GET" action="">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control pull-right" placeholder="Search" value="{{ (isset($request->keyword)) ? $request->keyword : null }}">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 col-xs-12">
                </div>
                <div class="col-md-4 col-xs-12">
                    <a href="{{ route('permission.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
                        Add Permission
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All Users List</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>Dispaly Name</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                        @forelse ($permissions as $e)
                            <tr>
                                <td>{{ $e->display_name }}</td>
                                <td>{{ $e->name }}</td>
                                <td>{{ $e->description }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('permission.edit', $e->id) }}" class="btn btn-xs btn-primary">Edit</a>
                                        <a href="{{ route('permission.destroy', $e->id) }}" class="btn btn-xs btn-danger delete-permission" data-id="{{ $e->id }}" data-method="DELETE">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>No Record Found</td>
                            </tr>
                        @endforelse
                    </table>
                    <div class="mt-5">
                        {{ $permissions->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('myscript')
@include('admin.scripts.permission')
@endsection
