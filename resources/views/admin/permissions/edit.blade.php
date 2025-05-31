@extends('admin.layout.app')

@section('title', 'Edit Permission')

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
        <li class="active">Edit Permission</li>
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
                    <form name="permission-edit-form" action="{{ route('permission.update', $permission->id) }}" method="POST" id="permission-edit-form">
                    @method('patch')
                    @csrf
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="control-label" for="name">Name <span class="required">* This will use in ACL</span>
                                </label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $permission->name }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="control-label" for="display_name">Display Name <span class="required">*</span>
                                </label>
                                <input type="text" name="display_name" id="display_name" class="form-control" value="{{ $permission->display_name }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="control-label" for="mobile">Description </label>
                                <input type="text" name="description" id="description" class="form-control" value="{{ $permission->description }}">
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
