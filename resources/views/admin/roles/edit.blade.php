@extends('admin.layout.app')

@section('title', 'Edit Role')

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.roles') }}"> Roles</a></li>
        <li class="active">Edit Role</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Role Edit From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.roles') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form name="role-edit-form" action="{{ route('role.update', $role->id) }}" method="POST" id="role-edit-form">
                        @method('patch')
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label" for="display_name">Display Name <span class="required">*</span>
                                    </label>
                                    <input type="text" name="display_name" id="display_name" class="form-control" value="{{ $role->display_name }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label" for="mobile">Description </label>
                                    <input type="text" name="description" id="description" class="form-control" value="{{ $role->description }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="roles">Add Permission <span class="required">*</span>
                                    </label>
                                    <div class="row ml-1">
                                        @forelse ($permissions as $e)
                                            <div class="col-md-3 form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <input name="permissions[]" type="checkbox" class="flat-red permissions" value="{{ $e->id }}" {{ (is_array($role->permissions->pluck('id')->toArray()) && in_array($e->id, $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}> {{ ucfirst(str_replace('_', ' ', $e->name)) }}
                                                </label>
                                            </div>
                                        @empty
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                No Permission Added
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button id="btn-submit" type="submit" class="btn btn-success btn-block">Save</button>
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
@include('admin.scripts.role')
@endsection
