@extends('admin.layout.app')

@section('title', "Add Manage Package")

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
        Manage Packageb
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.package.list') }}">Pckage</a></li>
        <li class="active">Create</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Package Add From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.package.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form name="packageCreateFrm" id="packageCreateFrm" action="{{ route('admin.package.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Package Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="title" name="title" placeholder="Package Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="price">Package Price(In EUR)</label>
                                    <input type="text" autocomplete="off" class="form-control" id="price" name="price" placeholder="Package Price">
                                    <small id="helpId" class="form-text text-muted">*Exclude the VAT (@23%)</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="vat">Vat Price</label>
                                    <input type="text" autocomplete="off" class="form-control" id="vat" name="vat" value="23" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="package_for">Package For</label>                       
                                      <select class="form-control" id="package_for" name="package_for">
                                        <option></option>
                                        <option value="employer">Employer</option>
                                        <option value="coach">Coach</option>
                                      </select>
                                   
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="plan_interval">Plan Interval</label>
                                    <select class="form-control" id="plan_interval" name="plan_interval">
                                        <option></option>
                                        <option value="week">Week</option>
                                        <option value="month">Month</option>
                                        <option value="year">Year</option>
                                      </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="number_of_job_post">Num of Job Post</label>
                                    <input type="text" autocomplete="off" class="form-control" id="number_of_job_post" name="number_of_job_post" placeholder="Num of Job Post">
                                     <small id="helpId" class="form-text text-muted">*On how many jobs post a employer can apply. For unlimited Listings type -1.</small>
                                </div>

                            </div>
                           
                            {{-- <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body" id="package_for">
                                        <h4 class="card-title">Package for?</h4>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="customControlValidation2" name="package_for" value="coach">
                                                <label class="custom-control-label" for="customControlValidation2">Coach</label>
                                            </div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="customControlValidation3" name="package_for" value="employer" checked>
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
                                  <textarea class="form-control" name="details" id="details" rows="3"></textarea>
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
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
@include('admin.scripts.package')
<script>
    $(document).ready(function(){
        $('#details').wysihtml5()
    });
</script>
@endsection
