@extends('admin.layout.app')

@section('title', "User Add")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.users') }}">Users</a></li>
        <li class="active">Create</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">User Create From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.users') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.user.store') }}" method="post" name="user-add-frm" id="user-add-frm">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="first_name">First name</label>
                                    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="First name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="last_name">Last name</label>
                                    <input type="text" name="last_name" class="form-control" id="last_name" placeholder="last name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="text" name="email" class="form-control" id="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="mobile">Contact number</label>
                                    <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Contact number">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="dob">Date Of Birth</label>
                                    <input type="text" name="dob" class="form-control date_picker" id="dob" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="type">Gender</label>
                                    <select class="form-control" name="gender" id="type">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Prefer to not say">Prefer to not say</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="country">Country</label>
                                    <select class="form-control" name="country" id="country">
                                        <option></option>
                                        @forelse ($countries as $row)
                                            <option value="{{ $row->name }}" data-country="{{ $row->id }}">
                                                {{ $row->name }}
                                            </option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="state">State</label>
                                    <select class="form-control" name="state" id="state">
                                        <option></option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="city">City</label>
                                    <select class="form-control" name="city" id="city">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="zip">  EIR Code/Zip Code</label>
                                    <input type="text" name="zip" class="form-control" id="zip" placeholder="  EIR Code/Zip Code">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="address"> Address</label>
                                    <input type="text" name="address" class="form-control" id="address" placeholder="Street Address">
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="roles">Roles <span class="required">*</span>
                                    </label>
                                    <div class="row">
                                        @forelse ($roles as $role)
                                            <div class="col-md-3">
                                                <label class="form-check-label">
                                                    <input name="roles[]" type="checkbox" class="flat-red roles" value="{{ $role->id }}"> {{ ucfirst($role->display_name) }}
                                                </label>
                                            </div>
                                        @empty
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                No Roles Added
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button id="user-btn-submit" type="submit" class="btn btn-success btn-block">Save</button>
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
@include('admin.scripts.user')
@include('admin.scripts.location')
@endsection
