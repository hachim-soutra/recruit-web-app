@extends('admin.layout.app')

@section('title', 'Admin Campaign')

@section('content')
    <section class="content-header">
        <h1>
            Manage Campaign
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Campaign</li>
        </ol>
    </section>
    <section class="content">
        <div class="box">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="col-md-7 col-xs-12">
                                <h3 class="box-title">All Campaign List</h3>
                            </div>
                            <div class="col-md-4 col-xs-12 d-flex justify-content-between flex-col">
                                <form method="GET">
                                    <div class="input-group">
                                        <input type="text" name="keyword" class="form-control d-block"
                                            placeholder="Search"
                                            value="{{ isset($request->keyword) ? $request->keyword : null }}">
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i
                                                    class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <a href="{{ route('admin.campaign.create') }}" class="btn bg-maroon btn-flat col-2 w-100">
                                Create
                            </a>

                        </div>
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Number jobs</th>
                                    <th scope="col">Number participent</th>
                                    <th scope="col">Date envoi</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                                @forelse ($data as $e)
                                    <tr>
                                        <td>{{ $e->object }}</td>
                                        <td>{{ $e->status }}</td>
                                        <td>{{ count(json_decode($e->jobs)) }}</td>
                                        <td>{{ count(json_decode($e->recipients)) }}</td>
                                        <td>{{ $e->date_envoi }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.campaign.resend', ['id' => $e->id]) }}"
                                                    class="btn btn-sm btn-success" data-toggle="tooltip"
                                                    data-placement="bottom" title="Resend">
                                                    <i class="fa fa-fw fa-paper-plane"></i>
                                                </a>
                                                <a href="{{ route('admin.campaign.edit', $e) }}"
                                                    class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                    data-placement="bottom" title="Edit Details">
                                                    <i class="fa fa-fw fa-pencil"></i>
                                                </a>
                                                <a href="{{ route('admin.campaign.show', $e->id) }}"
                                                    class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="View Details">
                                                    <i class="fa fa-fw fa-eye"></i>

                                                </a> <a href="{{ route('admin.campaign.destroy', $e) }}"
                                                    class="btn btn-sm btn-danger js-tooltip-enabled deleteRow"
                                                    data-toggle="tooltip" data-id="{{ $e->id }}" title="Delete"
                                                    data-method="DELETE" data-original-title="Delete">
                                                    <i class="fa fa-fw fa-times"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="6">No Data found</td>
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
        </div>
    </section>
@endsection

@section('myscript')
    @include('admin.scripts.campaign')
@endsection
