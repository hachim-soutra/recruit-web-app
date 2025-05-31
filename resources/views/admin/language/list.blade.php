@extends('admin.layout.app')

@section('title', "Manage Language")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Language
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Language</li>
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
                        <a href="{{ route('admin.language.list', 'active') }}" class="btn bg-purple btn-flat btn-sm">
                            Active Language
                        </a>
                        <a href="{{ route('admin.language.list', 'archived') }}" class="btn bg-navy btn-flat btn-sm">
                            Archive Language
                        </a>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <a href="{{ route('admin.language.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
                        Add Language
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Language List</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                        @forelse ($data as $e)
                            <tr>
                                <td>{{ $e->name }}</td>
                                <td>{{ $e->body }}</td>
                                <td class="text-center" id="userStatus-{{ $e->id }}">
                                    @if ($e->status==1)
                                        <a href="{{ route('admin.language.archive', $e->id) }}" class="btn btn-sm btn-success blockIns" data-id="{{ $e->id }}" data-method="PUT" data-toggle="tooltip" data-placement="bottom" title="Block">
                                            <i class="fa fa-fw fa-check"></i> Active
                                        </a>
                                    @else
                                        <a href="{{ route('admin.language.restore', $e->id) }}" class="btn btn-sm btn-danger activeIns" data-id="{{ $e->id }}" data-method="PUT" data-toggle="tooltip" data-placement="bottom" title="Activate">
                                            <i class="fa fa-fw fa-times"></i> In-active
                                        </a>
                                    @endif

                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.language.edit', $e->id) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" title="Edit Details">
                                            <i class="fa fa-fw fa-pencil"></i>
                                        </a>
                                        <a href="{{route('admin.language.destroy', $e->id)}}" class="btn btn-sm btn-danger js-tooltip-enabled deleteRow" data-toggle="tooltip" data-id="{{ $e->id }}" title="Delete" data-method="DELETE" data-original-title="Delete">
                                            <i class="fa fa-fw fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center">No Data found</td>
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
@include('admin.scripts.language')
@endsection
