@extends('admin.layout.app')

@section('title', 'Coach List')

@section('mystyle')
    <style>
        .subscribed {
            height: 28px;
            width: 99px;
            background-color: #4caf50;
            border-radius: 10px;
            /* border: 1px solid; */
            display: flex;
            justify-content: center;
            color: white;
            font-size: large;
            font-weight: 600;
        }

        .unsubscribed {
            height: 28px;
            width: 99px;
            background-color: #dd4b39;
            border-radius: 10px;
            /* border: 1px solid; */
            display: flex;
            justify-content: center;
            color: white;
            font-size: large;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Manage Coaches
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Coaches</li>
        </ol>
    </section>

    <!-- =============Main Page====================== -->
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
                        <div class="btn-group">
                            <a href="{{ route('admin.coach.list', 'active') }}" class="btn bg-purple btn-flat btn-sm">
                                Active Coaches
                            </a>
                            <a href="{{ route('admin.coach.list', 'archived') }}" class="btn bg-navy btn-flat btn-sm">
                                Archive Coaches
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <a href="{{ route('admin.coach.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
                            Create Coach
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Coaches List</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Key</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Email Status</th>
                                <th class="text-center">Mobile Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                            @forelse ($data as $e)
                                <tr>
                                    <td>{{ $e->user_key }}</td>
                                    <td>{{ $e->name }}</td>
                                    <td>{{ $e->email }}</td>
                                    <td>{{ $e->mobile ? $e->mobile : '--' }}</td>
                                    <td class="text-center" id="userStatus-{{ $e->id }}">
                                        @if ($e->status == 1)
                                            <a href="{{ route('admin.coach.archive', $e->id) }}"
                                                class="btn btn-sm btn-success blockUser" data-id="{{ $e->id }}"
                                                data-method="PUT" data-toggle="tooltip" data-placement="bottom"
                                                title="Block User Account">
                                                <i class="fa fa-fw fa-check"></i> Active
                                            </a>
                                        @else
                                            <a href="{{ route('admin.coach.restore', $e->id) }}"
                                                class="btn btn-sm btn-danger activeUser" data-id="{{ $e->id }}"
                                                data-method="PUT" data-toggle="tooltip" data-placement="bottom"
                                                title="Activate User Account">
                                                <i class="fa fa-fw fa-times"></i> Blocked
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center" id="userStatus-{{ $e->id }}">
                                        @if ($e->email_verified == 1)
                                            <a href="{{ route('admin.coach.email_archive', $e->id) }}"
                                                class="btn btn-sm btn-success blockUserEmail" data-id="{{ $e->id }}"
                                                data-method="PUT" data-toggle="tooltip" data-placement="bottom"
                                                title="Email Not Verified">
                                                <i class="fa fa-fw fa-check"></i> Verified
                                            </a>
                                        @else
                                            <a href="{{ route('admin.coach.email_restore', $e->id) }}"
                                                class="btn btn-sm btn-danger activeUserEmail" data-id="{{ $e->id }}"
                                                data-method="PUT" data-toggle="tooltip" data-placement="bottom"
                                                title="Email Verified">
                                                <i class="fa fa-fw fa-times"></i> Not Verified
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center" id="userStatus-{{ $e->id }}">
                                        @if (!empty($e->mobile))
                                            @if ($e->mobile_verified == 1)
                                                <a href="{{ route('admin.coach.mobile_archive', $e->id) }}"
                                                    class="btn btn-sm btn-success blockUserMobile"
                                                    data-id="{{ $e->id }}" data-method="PUT" data-toggle="tooltip"
                                                    data-placement="bottom" title="Mobile Not Verified">
                                                    <i class="fa fa-fw fa-check"></i> Verified
                                                </a>
                                            @else
                                                <a href="{{ route('admin.coach.mobile_restore', $e->id) }}"
                                                    class="btn btn-sm btn-danger activeUserMobile"
                                                    data-id="{{ $e->id }}" data-method="PUT" data-toggle="tooltip"
                                                    data-placement="bottom" title="Mobile Verified">
                                                    <i class="fa fa-fw fa-times"></i> Not Verified
                                                </a>
                                            @endif
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.coach.show', $e->id) }}"
                                                class="btn btn-xs btn-info">View</a>
                                            @if ($e->id != 1)
                                                <a href="{{ route('admin.coach.edit', $e->id) }}"
                                                    class="btn btn-xs btn-warning">Edit</a>
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
                            {{ $data->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('myscript')
    @include('admin.scripts.coach')
@endsection
