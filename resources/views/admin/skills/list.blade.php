@extends('admin.layout.app')

@section('title', "Manage Skill")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Skills
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Skill</li>
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
                    <a href="{{ route('admin.skill.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
                        Create
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All Skill List</h3>
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
                            <td>
                                @if ($e->description)
                                    {{ $e->description }}
                                @else
                                    ---
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($e->status)
                                    Active
                                @else
                                    In-active
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{route('admin.skill.edit', $e->id)}}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="Edit" data-original-title="Edit">
                                        <i class="fa fa-fw fa-pencil"></i>
                                    </a>
                                    <a href="{{route('admin.skill.destroy', $e->id)}}" class="btn btn-sm btn-danger js-tooltip-enabled deleteRow" data-toggle="tooltip" data-id="{{ $e->id }}" title="Delete" data-method="DELETE" data-original-title="Delete">
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
@include('admin.scripts.skill')
@endsection
