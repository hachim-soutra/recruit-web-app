@extends('admin.layout.app')

@section('title', 'Admin Plans')

@section('mystyle')

@endsection

@section('content')
    <section class="content-header">
        <h1>
            Manage Plans
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Plans</li>
        </ol>
    </section>
    <section class="content">
        <div class="box">
            <div class="row">
                <div class="box-body">
                    <div class="col-md-4 col-xs-12">
                        <form method="GET" action="">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control pull-right" placeholder="Search"
                                    value="{{ isset($request->keyword) ? $request->keyword : null }}">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 col-xs-12">
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <a href="{{ route('admin.plan.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
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
                        <h3 class="box-title">All Plans List</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th scope="col">Plan For</th>
                                <th scope="col">Plan Type</th>
                                <th scope="col">Title</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Slot Number</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Assign to employers</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                            @forelse ($data as $e)
                                <tr>
                                    <td>{{ $e->plan_for->value }}</td>
                                    <td>{{ $e->plan_type->value }}</td>
                                    <td>{{ $e->title }}</td>
                                    <td>{{ $e->slug }}</td>
                                    <td>{{ $e->job_number }}</td>
                                    <td class="text-center">
                                        @if ($e->status == \App\Enum\Payments\PlanStatusEnum::ACTIVE)
                                            <a href="{{ route('admin.plan.update_status', $e) }}"
                                                class="btn btn-sm btn-success blockIns" data-id="{{ $e->id }}"
                                                data-method="PATCH" data-toggle="tooltip" data-placement="bottom"
                                                title="ACTIVE">
                                                <i class="fa fa-fw fa-check"></i> Active
                                            </a>
                                        @else
                                            <a href="{{ route('admin.plan.update_status', $e) }}"
                                                class="btn btn-sm btn-danger activeIns" data-id="{{ $e->id }}"
                                                data-method="PATCH" data-toggle="tooltip" data-placement="bottom"
                                                title="INACTIVE">
                                                <i class="fa fa-fw fa-times"></i> In-active
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($e->status == \App\Enum\Payments\PlanStatusEnum::ACTIVE)
                                            <a href="{{ route('admin.plan.assign', $e) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-users" aria-hidden="true"></i> Assign to employers
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.plan.edit', $e) }}"
                                                class="btn btn-sm btn-warning js-tooltip-enabled" data-toggle="tooltip"
                                                data-placement="top" title="Edit Details">
                                                <i class="fa fa-fw fa-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.plan.show', $e->id) }}"
                                                class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip"
                                                data-placement="top" title="View Details">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.plan.destroy', $e) }}"
                                                class="btn btn-sm btn-danger js-tooltip-enabled deleteRow"
                                                data-toggle="tooltip" data-id="{{ $e->id }}" data-placement="top"
                                                title="Delete" data-method="DELETE">
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
                            {{ $data->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('myscript')
    @include('admin.scripts.plan')
@endsection
