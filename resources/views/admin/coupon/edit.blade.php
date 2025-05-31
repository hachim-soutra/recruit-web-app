@extends('admin.layout.app')

@section('title', "Update Coupon")

@section('mystyle')
@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.coupon.list') }}">Coupon</a></li>
        <li class="active">Update</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Coupon Update From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.coupon.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="post" name="couponEditFrm" id="couponEditFrm">
                        @csrf
                        @method('PATCH')   
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Coupon Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="coupon_title" name="coupon_title" placeholder="Coupon Title" value="{{ $coupon->coupon_title }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="coupon_for">Coupon for?</label>
                                    <select class="form-control" name="coupon_for" id="coupon_for">
                                        <option></option>
                                        <option value="Employer" {{ $coupon->coupon_for == 'Employer'?'selected':''}}>Employer</option>
                                        <option value="Coach" {{ ($coupon->coupon_for== 'Coach')?'selected':''}}>Coach</option>
                                    </select>
                                </div>
                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="coupon_amount">Coupon Amount</label>
                                    <input type="text" autocomplete="off" class="form-control" id="coupon_amount" name="coupon_amount" placeholder="Coupon amount" value="{{ $coupon->coupon_amount }}">
                                     <small id="helpId" class="form-text text-muted">*Deductible amount not more then any plan amount.</small>
                                </div>

                            </div>
                        </div>
                        {{-- <div class="row">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="coupon_start_date">Coupon Valid From</label>
                                    <input type="text" autocomplete="off" class="form-control" id="coupon_start_date" name="coupon_start_date" placeholder="Coupon Valid From" value="{{ $coupon->coupon_start_date }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="coupon_expiry_date">Coupon Valid To</label>
                                    <input type="text" autocomplete="off" class="form-control" id="coupon_expiry_date" name="coupon_expiry_date" placeholder="Coupon Valid To" value="{{ $coupon->coupon_expiry_date }}"">
                                </div>
                            </div>
                           
                        </div> --}}
                        <div class="row">                           
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="description">Description</label>
                                  <textarea class="form-control" name="description" id="description" rows="3">{{ $coupon->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <hr/>
                        <div class="row">
                            <div class="col-md-12">
                                <button id="education-btn-submit" type="submit" class="btn btn-success btn-block">Save</button>
                            </div>
                        </div>
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
