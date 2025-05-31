@extends('admin.layout.app')

@section('title', "Admin Events")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Events
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Events</li>
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
                    <a href="{{ route('admin.event.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
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
                    <h3 class="box-title">All Events List</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Event Date</th>
                            <th scope="col">Event Time</th>
                            <th scope="col">Image</th>
                            <th scope="col" class="text-center">Published By</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                        @forelse ($data as $e)

                            <tr>
                                <td>{{ $e->title }}</td>
                                <td>{{ $e->event_date }}</td>
                                <td>{{ $e->time }}</td>
                                <td><img src="{{ $e->image }}" style="width: 50px; height: 40px;"></td>
                                <td class="text-center">
                                   {{ $e->createdBy->name }}
                                </td>
                                <td class="text-center">
                                    @if($e->status === \App\Enum\EventStatusEnum::SHOW_IN_LIST)
                                        Show in list
                                    @elseif($e->status === \App\Enum\EventStatusEnum::SHOW_IN_HOME)
                                        Show in home
                                    @elseif($e->status === \App\Enum\EventStatusEnum::DRAFT)
                                        Draft
                                    @else
                                        Rejected
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.event.edit', $e->id) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Edit Details">
                                            <i class="fa fa-fw fa-pencil"></i>
                                        </a>
                                        <a href="{{ route('admin.event.show', $e->id) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Details">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                        <a href="{{route('admin.event.destroy', $e->id)}}" class="btn btn-sm btn-danger js-tooltip-enabled deleteRow" data-toggle="tooltip" data-id="{{ $e->id }}" title="Delete" data-method="DELETE" data-original-title="Delete">
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
@include('admin.scripts.event')
@endsection
