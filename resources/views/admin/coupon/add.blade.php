@extends('admin.layout.app')

@section('title', "Make Coupon")

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
                    <h3 class="box-title">Coupon Make From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.coupon.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form name="couponCreateFrm" id="couponCreateFrm" action="{{ route('admin.coupon.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Coupon Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="coupon_title" name="coupon_title" placeholder="Coupon Title">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="coupon_for">Coupon for?</label>
                                    <select class="form-control" name="coupon_for" id="coupon_for">
                                        <option></option>
                                        <option value="Employer">Employer</option>
                                        <option value="Coach">Coach</option>
                                    </select>
                                </div>
                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="coupon_amount">Coupon Amount</label>
                                    <input type="text" autocomplete="off" class="form-control" id="coupon_amount" name="coupon_amount" placeholder="Coupon amount">
                                     <small id="helpId" class="form-text text-muted">*Deductible amount not more then any plan amount.</small>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="coupon_start_date">Coupon Valid From</label>
                                    <input type="text" autocomplete="off" class="form-control" id="coupon_start_date" name="coupon_start_date" placeholder="Coupon Valid From">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="coupon_expiry_date">Coupon Valid To</label>
                                    <input type="text" autocomplete="off" class="form-control" id="coupon_expiry_date" name="coupon_expiry_date" placeholder="Coupon Valid To">
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">                           
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="description">Description</label>
                                  <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn waves-effect waves-light btn-primary">Save</button>
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
