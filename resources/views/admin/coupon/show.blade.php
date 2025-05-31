@extends('admin.layout.app')
@section('title', "Coupon Details")

@section('mystyle')
@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Coupon
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{ route('admin.coupon.list') }}">Coupon</a></li>
        <li class="active">Details</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Coupon Details</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>

                <div class="box-body">
                    <div class="col-lg-12">
                        <form name="user-update-form" class="form-horizontal form-label-left" >                           
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="coupon_title"> Coupon Title
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="coupon_title" id="coupon_title" class="form-control"  value="{{ old('coupon_title', $coupon->coupon_title) }}" readonly>
                                </div>                              
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code"> Coupon Code
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $coupon->code) }}" readonly>
                                </div>
                               
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="coupon_for">Coupon for 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="coupon_for" id="coupon_for" class="form-control" value="{{ old('coupon_for', $coupon->coupon_for) }}" readonly>
                                </div>                              
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="coupon_amount"> Coupon Discount Amount
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="coupon_amount" id="coupon_amount" class="form-control" value="{{ $coupon->coupon_amount }}" readonly>
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="total_usage"> Total Coupon Usages </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="total_usage" id="total_usage" class="form-control" value="{{ ($coupon->total_usage? $coupon->total_usage: "0") }}"required="required" readonly>
                                </div>                              
                            </div>
                           
                            {{-- <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="coupon_start_date">Coupon Valid From
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="coupon_start_date" id="coupon_start_date" class="form-control" value="{{ old('coupon_start_date', $coupon->coupon_start_date) }}"required="required" readonly>
                                </div>                               
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="coupon_expiry_date">Coupon Valid Until 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="coupon_expiry_date" id="coupon_expiry_date" class="form-control" value="{{ old('coupon_expiry_date', $coupon->coupon_expiry_date) }}"required="required" readonly>
                                </div>                               
                            </div> --}}
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description"> Description
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="description" id="description" class="form-control textarea" rows="3" readonly> {{ $coupon->description }} </textarea>
                                </div>                              
                            </div>                          

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@section('myscript')
@endsection
