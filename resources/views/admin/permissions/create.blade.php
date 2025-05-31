@extends('admin.layout.app')

@section('title', 'Add Permission')

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('permissions') }}"> Permissions</a></li>
        <li class="active">Add Permission</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Permission Create From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('permissions') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form name="permission-create-form" action="{{ route('permission.store') }}" method="POST" id="permission-create-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Permission Type <span class="required">*</span> </label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-radio form-radio-flat">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="permission_type" name="permission_type" value="basic" checked> Basic Permission
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-radio form-radio-flat">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="permission_type" name="permission_type" value="curd"> CRUD Permission
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="basic_option_box">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="display_name">Diplay Name <span class="required">*</span>
                                                </label>
                                                <input type="text" name="display_name" id="display_name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="description">Description <span class="required">*</span>
                                                </label>
                                                <input type="text" name="description" id="description" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="curd_option_box">
                                    <div class="form-group">
                                        <label class="control-label" for="resource">Resource <span class="required">*</span>
                                        </label>
                                        <input type="text" name="resource" id="resource" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="curd_selected">Options <span class="required">*</span>
                                        </label>
                                        <div class="row ml-1">
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-check form-check-flat">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="flat-red curd_selected" name="curd_selected[]" value="create"> Create
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-check form-check-flat">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="flat-red curd_selected" name="curd_selected[]" value="update"> Update
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-check form-check-flat">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="flat-red curd_selected" name="curd_selected[]" value="read"> Read
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-check form-check-flat">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="flat-red curd_selected" name="curd_selected[]" value="delete"> Delete
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                        <a href="{{ route('permissions') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('myscript')
@include('admin.scripts.permission')
@endsection
