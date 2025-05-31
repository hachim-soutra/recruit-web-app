@extends('admin.layout.app')

@section('title', "Admin Advertises")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Advertises
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Advertises</li>
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
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All Advertises List</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th scope="col">Company name</th>
                            <th scope="col">First name</th>
                            <th scope="col">Last name</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Phone number</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                        @forelse ($data['advertises'] as $e)
                            <tr>
                                <td>{{ $e->company_name }}</td>
                                <td>{{ $e->first_name }}</td>
                                <td>{{ $e->last_name }}</td>
                                <td>{{ $e->email }}</td>
                                <td>{{ $e->phone }}</td>
                                <td class="text-center">
                                    @if ($e->status==\App\Enum\Jobs\AdvertiseStatusEnum::READ)
                                        <a href="{{ route('admin.advertise.update_status', $e) }}" class="btn btn-sm btn-success blockIns" data-id="{{ $e->id }}" data-method="PATCH" data-toggle="tooltip" data-placement="bottom" title="Click to mark as unread">
                                            <i class="fa fa-fw fa-check"></i> Read
                                        </a>
                                    @else
                                        <a href="{{ route('admin.advertise.update_status', $e) }}" class="btn btn-sm btn-danger activeIns" data-id="{{ $e->id }}" data-method="PATCH" data-toggle="tooltip" data-placement="bottom" title="Click to mark as read">
                                            <i class="fa fa-fw fa-times"></i> Unread
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a id="advertiseRegisterFrm" href="{{ route('admin.advertise.send_registration', $e) }}" class="btn btn-sm btn-warning" data-id="{{ $e->id }}" data-method="POST" data-toggle="tooltip" data-placement="bottom" title="Send e-mail">
                                            <i class="fa fa-fw fa-pencil"></i>
                                            Send Registration
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
                        {{ $data['advertises']->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('myscript')
@include('admin.scripts.advertise')
@endsection
