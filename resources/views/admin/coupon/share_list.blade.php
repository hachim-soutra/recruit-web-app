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
                    {{-- <form method="GET" action="">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control pull-right" placeholder="Search" value="{{ (isset($request->keyword)) ? $request->keyword : null }}">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form> --}}
                </div>
                <div class="col-md-4 col-xs-12">
                </div>
                <div class="col-md-4 col-xs-12">
                    <a href="{{ route('admin.coupon.list') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All Shered Coupon User List</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th scope="col">Coupon Title</th>
                            <th scope="col">Coupon Code</th>
                            <th scope="col">Coupon Amount</th>
                            <th scope="col" class="text-center">Name</th>
                            <th scope="col" class="text-center">Email</th>
                        </tr>
						@if (count($data->coupon_user) > 0)
							@forelse ($data->coupon_user as $e)
								<tr>
									<td>{{ $data->coupon_title }}</td>
									<td>{{ $data->code }}</td>
									<td>{{ $data->coupon_amount }}</td>
									<td class="text-center">{{ $e->user->name}}</td>
									<td class="text-center">{{ $e->user->email }}</td>
									
								</tr>
							@empty
								<tr>
									<td class="text-center">No Data found</td>
								</tr>
							@endforelse
						@endif
                        
                    </table>
                    {{-- <div class="mt-5">
                        {{ $data->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('myscript')
@include('admin.scripts.coupon')
@endsection
