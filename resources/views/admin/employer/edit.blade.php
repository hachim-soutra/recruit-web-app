@extends('admin.layout.app')

@section('title', "Employer Edit")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.employer.list') }}">Employers</a></li>
        <li class="active">Edit</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Update Employer</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.employer.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.employer.update', $employer->id) }}" method="post" name="employer-update-frm" id="employer-update-frm" class="employer-update-frm" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="first_name">First Name</label>
                                    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name" value="{{$employer->first_name}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="last_name">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter Last Name" value="{{$employer->last_name}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="text" name="email" class="form-control" id="email" placeholder="Email" value="{{$employer->email}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="mobile">Mobile</label>
                                    <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Mobile" value="{{$employer->mobile}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="established_in">Date Of Established</label>
                                    <input type="text" name="established_in" class="form-control date_picker" id="established_in" placeholder="YYYY-MM-DD" value="{{$profile->established_in ?? ""}}" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="avatar">Avatar</label>
                                    <input type="file" name="avatar" class="form-control" id="avatar" placeholder="Enter Avater" >
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="company_name">Company Name</label>
                                    <input type="text" name="company_name" class="form-control" id="company_name" placeholder="Enter Company Name" value="{{$profile->company_name ?? ""}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="company_logo">Company Logo</label>
                                    <input type="file" name="company_logo" class="form-control" id="company_logo" placeholder="Enter Company Logo" >
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="website_link">Website Link</label>
                                    <input type="text" name="website_link" class="form-control" id="website_link" placeholder="Enter Website Link" value="{{$profile->website_link ?? ""}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="linkedin_link"> LinkedIn Link</label>
                                    <input type="text" name="linkedin_link" class="form-control" id="linkedin_link" placeholder="LinkedIn Link" value="{{$profile->linkedin_link ?? ""}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="tag_line">Tag Line </label>
                                    <input type="test" name="tag_line" class="form-control" id="tag_line" placeholder="Enter Tag Line" value="{{$profile->tag_line ?? ""}}">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="company_ceo">Contact Name</label>
                                    <input type="text" name="company_ceo" class="form-control" id="company_ceo" placeholder="Enter Contact Name" value="{{$profile->company_ceo ?? ""}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="phone_number">Contact Number</label>
                                    <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="Contact Number" value="{{$profile->phone_number ?? ""}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="zip">  EIR Code/Zip Code</label>
                                    <input type="text" name="zip" class="form-control" id="zip" placeholder="  EIR Code/Zip Code" value="{{$profile->zip ?? ""}}">
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="address"> Address</label>
                                    <input type="text" name="address" class="form-control" id="address" placeholder="Address" value="{{$profile->address ?? ""}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="company_details"> Company Details</label>
                                   <textarea name="company_details" id="company_details" class="form-control" cols="30" rows="10">{{$profile->company_details ?? ""}}</textarea>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="roles">Roles <span class="required">*</span>
                                    </label>
                                    <div class="row">
                                        @forelse ($roles as $role)
                                            <div class="col-md-3">
                                                <label class="form-check-label">
                                                    <input name="roles[]" type="checkbox" class="flat-red roles" value="{{ $role->id }}" {{ (is_array($employer->roles->pluck('id')->toArray()) && in_array($role->id, $employer->roles->pluck('id')->toArray())) ? 'checked' : '' }}> {{ ucfirst($role->display_name) }}
                                                </label>
                                            </div>
                                        @empty
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                No Roles Added
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <button id="employer-edit-btn-submit" type="submit" class="btn btn-success btn-block">Save</button>
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
@include('admin.scripts.employer')
@include('admin.scripts.location')
@endsection
