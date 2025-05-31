@extends('admin.layout.app')

@section('title', "Users List")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Admins
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Users</li>
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
                    <div class="btn-group">
                        <a href="{{ route('admin.user.list', 'active') }}" class="btn bg-purple btn-flat btn-sm">
                            Active Users
                        </a>
                        <a href="{{ route('admin.user.list', 'archived') }}" class="btn bg-navy btn-flat btn-sm">
                            Archive Users
                        </a>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <a href="{{ route('admin.user.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
                        Create User
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Users List</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>Key</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        @forelse ($data as $e)
                            <tr>
                                <td>{{ $e->user_key }}</td>
                                <td>{{ $e->first_name }} {{ $e->last_name }}</td>
                                <td>{{ $e->email }}</td>
                                <td>{{ $e->mobile }}</td>
                                <td class="status-col-{{ $e->id }}">
                                    @if ($e->id != 1)
                                        @if ($e->status === 1)
                                            <button class="btn btn-xs btn-success make-archive" data-id="{{ $e->id }}">Active</button>
                                        @else
                                            <button class="btn btn-xs btn-danger make-active" data-id="{{ $e->id }}">Archived</button>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.user.show', $e->id) }}" class="btn btn-xs btn-info">View</a>
                                        @if ($e->id != 1)
                                            <a href="{{ route('admin.user.edit', $e->id) }}" class="btn btn-xs btn-warning">Edit</a>
                                        @endif
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
                        {{ $data->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('myscript')
@include('admin.scripts.admin')
@endsection
