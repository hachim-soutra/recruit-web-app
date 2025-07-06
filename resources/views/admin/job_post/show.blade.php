@extends('admin.layout.app')

@section('title', "Job Post Details")

@section('mystyle')
@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.job.list') }}">Job Post Details</a></li>
        <li class="active"></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Post Details</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.job.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Job Title">Job Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="job_title" name="job_title" placeholder="Job Title" value="{{$job->job_title}}" readonly>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="job_location">Job Location</label>
                                    <input type="text" autocomplete="off" class="form-control" id="job_location" name="job_location" placeholder="Job Location" value="{{$job->job_location}}" readonly />

									<input type="hidden" id="start_latitude" name="start_latitude" value="" />
									<input type="hidden" id="start_longitude" name="start_longitude" value="" />
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="job_expiry_date">Job Expiry Date</label>
                                    <input type="text" autocomplete="off" class="form-control" id="job_expiry_date" name="job_expiry_date" value="{{$job->job_expiry_date}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hide_salary">Hide Salary</label>
                                    <select class="form-control" name="hide_salary" id="hide_salary" readonly>
                                        <option></option>
                                        <option value="yes" {{ ($job->hide_salary == "yes")? "selected":"" }}>Yes</option>
                                        <option value="no" {{ ($job->hide_salary == "no")? "selected":"" }}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="salary_from">Salary From</label>
                                    <input type="text" name="salary_from" class="form-control" id="salary_from" placeholder="Salary From" value="{{$job->salary_from}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="salary_to">Salary To</label>
                                    <input type="text" name="salary_to" class="form-control" id="salary_to" placeholder="Salary To" value="{{$job->salary_to}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="salary_currency">Salary Currency</label>
                                    <input type="text" name="salary_currency" class="form-control" id="salary_currency" placeholder="Salary Currency" value="{{$job->salary_currency? $job->salary_currency: "€"}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="salary_period">Salary Period</label>
                                    <select class="form-control" name="salary_period" id="salary_period" readonly>
                                        <option></option>
                                        <option value="Yearly" {{ ($job->salary_period == "Yearly")? "selected":"" }}>Yearly</option>
                                        <option value="Monthly" {{ ($job->salary_period == "Monthly")? "selected":"" }}>Monthly</option>
                                        <option value="Weekly" {{ ($job->salary_period == "Weekly")? "selected":"" }}>Weekly</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="zip">  EIR Code/Zip Code</label>
                                    <input type="text" name="zip" class="form-control" id="zip" placeholder="  EIR Code/Zip Code" value="{{ $job->zip }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="preferred_job_type">Preferred Job Type</label>
                                    <select class="form-control" name="preferred_job_type" id="preferred_job_type" readonly>
                                        <option></option>
                                        <option value="Full time" {{ ($job->preferred_job_type == "Full time") ? "selected" :""}}>Full Time</option>
                                        <option value="Part time" {{ ($job->preferred_job_type == "Part time") ? "selected" :""}}>Part Time</option>

                                        <option value="work from home" {{ ($job->preferred_job_type == "work from home") ? "selected" :""}}>Work From Home</option>
                                        <option value="remote" {{ ($job->preferred_job_type == "remote") ? "selected" :""}}>Remote</option>
                                        <option value="temporarily remote" {{ ($job->preferred_job_type == "temporarily remote") ? "selected" :""}}>Temporarily Remote</option>
                                        <option value="Permanent" {{ ($job->preferred_job_type == "Permanent") ? "selected" :""}}>Permanent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="experience">Experience</label>
                                    <input type="text" autocomplete="off" class="form-control" id="experience" name="experience" placeholder="Experience" value="{{ $job->experience}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for="total_hire">Total Hires</label>
                                  <input type="text" autocomplete="off" class="form-control" id="total_hire" name="total_hire" placeholder="Total Hires" value="{{ $job->total_hire}}" readonly>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="job_details">Skill : </label>
                                  @forelse ($skill as $item)
                                    {{$item}}
                                  @empty

                                  @endforelse
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="job_details">Qualification : </label>
                                  @forelse ($qualification as $item)
                                    {{$item}}
                                  @empty

                                  @endforelse
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="job_details">Industry : </label>{{ $job->functional_area }}
                                </div>
                            </div>
                            @if($job->application_url)
                                <div class="col-md-12">
                                    <div class="form-group">
                                    <label for="job_details">Application URL : </label>
                                    <a href="{{ $job->application_url }}" target="_blank">{!! $job->application_url !!}</a>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="job_details">Details : </label>{!! $job->job_details !!}
                                </div>
                            </div>
                        </div>
                        <hr/>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('myscript')
@endsection
