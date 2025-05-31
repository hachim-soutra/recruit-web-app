@extends('admin.layout.app')

@section('title', "Role List")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Roles</li>
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
                    <a href="{{ route('role.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
                        Create Role
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
                            <th>Name</th>
                            <th>Display Name</th>
                            <th>Description</th>
                            <th>Permissions</th>
                            <th>Action</th>
                        </tr>
                        @forelse ($data as $e)
                            <tr>
                                <td>{{ $e->name }}</td>
                                <td>{{ $e->display_name }}</td>
                                <td>{{ $e->description }}</td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-info view-permission" data-toggle="popover" data-placement="bottom" data-html="true" title="Permissions"
                                    data-content="
                                        @foreach($e->permissions as $p)
                                            {{ ucfirst(str_replace('_', ' ', $p->name)) }} <br />
                                        @endforeach
                                    ">View Permissions
                                </button>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('role.edit', $e->id) }}" class="btn btn-xs btn-warning">Edit</a>
                                        @if ($e->id != 1)
                                            <a href="{{ route('role.destroy', $e->id) }}" class="btn btn-xs btn-danger delete-role" data-id="{{ $e->id }}" data-method="DELETE">Remove</a>
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
<script>
    $(document).ready(function(){
        $('.view-permission').popover();
        $('body').on('click', function (e) {
            if ($(e.target).data('toggle') !== 'popover'
                && $(e.target).parents('.popover.in').length === 0) {
                $('[data-toggle="popover"]').popover('hide');
            }
        });
    });
</script>
@include('admin.scripts.role')
@endsection
