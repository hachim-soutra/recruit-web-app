@extends('admin.layout.app')

@section('title', "Update Package")

@section('mystyle')
@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.package.list') }}">Package</a></li>
        <li class="active">Update</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Package Update From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.package.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.package.update', $package->id) }}" method="post" name="package-edit-frm" id="package-edit-frm">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Package Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="title" name="title" placeholder="Package Name" value="{{ $package->title }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Package Price(In EUR)</label>
                                    <input type="text" autocomplete="off" class="form-control" id="price" name="price" value="{{ $package->price }}">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                               
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="package_for">Package For</label>                       
                                      <select class="form-control" id="package_for" name="package_for">
                                        <option></option>
                                        <option value="employer" {{ ($package->package_for == 'employer')?"selected" : ""}}>Employer</option>
                                        <option value="coach" {{ ($package->package_for == 'coach')?"selected" : ""}}>Coach</option>
                                      </select>
                                   
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="plan_interval">Plan Interval</label>                       
                                      <select class="form-control" id="plan_interval" name="plan_interval">
                                        <option></option>
                                        <option value="week" {{ ($package->plan_interval == 'week')?"selected" : ""}}>Week</option>
                                        <option value="month" {{ ($package->plan_interval == 'month')?"selected" : ""}}>Month</option>
                                        <option value="year" {{ ($package->plan_interval == 'year')?"selected" : ""}}>Year</option>
                                      </select>
                                   
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="number_of_job_post">Num of Job Post</label>
                                    <input type="text" autocomplete="off" class="form-control" id="number_of_job_post" name="number_of_job_post" placeholder="Num of Job Post" value="{{ $package->number_of_job_post }}">
                                     <small id="helpId" class="form-text text-muted">*On how many jobs post a employer can apply</small>
                                </div>

                            </div>
                            {{-- <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body" id="package_for">
                                        <h4 class="card-title">Package for?</h4>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="customControlValidation2" name="package_for" value="coach" {{ $package->package_for == "coach" ? "checked":"" }}>
                                                <label class="custom-control-label" for="customControlValidation2">Coach</label>
                                            </div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="customControlValidation3" name="package_for" value="employer" {{ $package->package_for == "employer" ? "checked": ""}}>
                                                <label class="custom-control-label" for="customControlValidation3">Job Post</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div> --}}
                        </div>
                       
                        <div class="row">
                           
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="details">Details</label>
                                  <textarea class="form-control" name="details" id="details" rows="3">{{ $package->details }}</textarea>
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
@include('admin.scripts.package')
@endsection
