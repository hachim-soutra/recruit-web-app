@extends('admin.layout.app')

@section('title', "Manage Coupon")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Coupon
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Coupon</li>
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
                    <a href="{{ route('admin.coupon.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
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
                    <h3 class="box-title">All Coupon List</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th scope="col">Coupon Title</th>
                            <th scope="col">Coupon Code</th>
                            <th scope="col">Coupon For</th>
                            <th scope="col" class="text-center">Usage</th>
                            <th scope="col" class="text-center">Coupon Amount</th>
                            <th scope="col" class="text-center">Expire</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                        @forelse ($data as $e)
                            <tr>
                                <td>{{ $e->coupon_title }}</td>
                                <td>{{ $e->code }}</td>
                                <td>{{ $e->coupon_for }}</td>
                                <td class="text-center">{{ $e->coupon_usage?$e->coupon_usage:'0'}}</td>
                                <td class="text-center">{{ $e->coupon_amount?$e->coupon_amount:'0' }}</td>
                                <td class="text-center">{{ $e->coupon_expiry_date}}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        {{-- <a href="{{ route('admin.coupon.edit', $e->id) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" title="Edit Details">
                                            <i class="fa fa-fw fa-pencil"></i>
                                        </a> --}}
                                        <a href="{{ route('admin.coupon.show', $e->id) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Coupon Details">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                       
                                        @if ($e->coupon_expiry_date > date('Y-m-d'))
                                        <a href="{{ route('admin.coupon.share', $e->id) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" title="Share Coupon">
                                            <i class="fa fa-share-alt"></i>
                                        </a>
                                        @endif
                                        
                                        <a href="{{ route('admin.coupon.shared_users', $e->id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="bottom" title="Shared {{$e->coupon_for}}">
                                            <i class="fa fa-users"></i>
                                        </a>
                                        {{-- <a href="{{route('admin.coupon.destroy', $e->id)}}" class="btn btn-sm btn-danger js-tooltip-enabled deleteRow" data-toggle="tooltip" data-id="{{ $e->id }}" title="Delete" data-method="DELETE" data-original-title="Delete">
                                            <i class="fa fa-fw fa-times"></i>
                                        </a> --}}
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
@include('admin.scripts.coupon')
@endsection
