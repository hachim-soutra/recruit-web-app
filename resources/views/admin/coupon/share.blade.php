@extends('admin.layout.app')

@section('title', "Share Coupon")

@section('mystyle')
<style>
    .add-edu{
        margin-top: 25px;
    }
</style>
@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Coupon
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.coupon.list') }}">Coupon</a></li>
        <li class="active">Create</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Share Coupon From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.coupon.share', $coupon->id) }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
					<p >{{ $coupon->coupon_title }} - {{ $coupon->code }} </p>
                    <form name="couponShareFrm" id="couponShareFrm" action="{{ route('admin.coupon.share_to_user', $coupon->id) }}" method="POST">
                        @csrf

                        <div class="row">
                            @if ($coupon->coupon_for == 'Employer')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="employer">Coupon Share to Employer</label>
                                    <select class="form-control" name="employer[]" id="employer" multiple>
                                        <option></option>                                       
										@forelse ($employers as $item)
										<option value="{{ $item->id }}">{{$item->name }} - {{ $item->email}}</option>
										@empty
											No Employer Found!
										@endforelse
                                    </select>
                                </div>
                            </div>
                            @else
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="coach">Coupon Share to Coach</label>
                                    <select class="form-control" name="coach[]" id="coach" multiple>
                                        <option></option>
										@forelse ($coaches as $item)
										<option value="{{ $item->id }}">{{$item->name }} - {{ $item->email}}</option>
										@empty
											No Employer Found!
										@endforelse
                                    </select>
                                </div>                                
                            </div>  
                            @endif
                           
                                                     
                        </div>
                        
                        {{-- <div class="row">                           
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="description">Description</label>
                                  <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                                </div>
                            </div>
                        </div> --}}
                        <hr>
                        <button type="submit" class="btn waves-effect waves-light btn-primary">Share</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('myscript')
@include('admin.scripts.coupon')
@endsection
