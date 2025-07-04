@extends('admin.layout.app')

@section('title', "Add Manage Job Post")

@section('mystyle')
<style>
    .add-edu{
        margin-top: 25px;
    }
</style>
@endsection

@section('content')
<script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('LOCATION_API') }}&libraries=places&callback=Function.prototype"></script>
<section class="content-header">
    <h1>
        Manage Job Post
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.job.list') }}">Jobs</a></li>
        <li class="active">Create</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Post Add From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.job.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form name="jobCreateFrm" id="jobCreateFrm" action="{{ route('admin.job.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="employer">Employer</label>
                                    <select class="form-control" name="employer" id="employer">
                                        <option></option>
                                        @forelse ($employers as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->name }}
                                            </option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" style="top: 25px;">
                                <div class="form-group">
                                    <a href="{{ route('admin.employer.create')}}">
                                    <input type="button" value="Add Employer" class="btn btn-primary">  </a>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="Job Title">Job Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="job_title" name="job_title" placeholder="Job Title">

                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="job_expiry_date">Job Expiry Date</label>
                                    <input type="text" autocomplete="off" class="form-control" id="job_expiry_date" name="job_expiry_date" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" value="<?php echo Carbon::now()->addMonths(6); ?>">
                                </div>
                            </div>

                        </div>
                        <div class="row">


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="hide_salary">Hide Salary</label>
                                    <select class="form-control" name="hide_salary" id="hide_salary">
                                        <option></option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="salary_from">Salary From</label>
                                    <input type="text" name="salary_from" class="form-control" id="salary_from" placeholder="Salary From">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="salary_to">Salary To</label>
                                    <input type="text" name="salary_to" class="form-control" id="salary_to" placeholder="Salary To">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="salary_currency">Salary Currency</label>
                                    <input type="text" name="salary_currency" class="form-control" id="salary_currency" placeholder="Salary Currency" value="€">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="salary_period">Salary Period</label>

                                    <select class="form-control" name="salary_period" id="salary_period">
                                        <option></option>
                                        <option value="Yearly">Yearly</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Weekly">Weekly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="job_location">Job Location</label>
                                    <input type="text" class="form-control AutocompleteService" id="job_location" name="job_location" placeholder="Job Location" />

									<input type="hidden" id="start_latitude" name="start_latitude" value="" />
									<input type="hidden" id="start_longitude" name="start_longitude" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- <div class="col-md-3">
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
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="zip">  EIR Code/Zip Code</label>
                                    <input type="text" name="zip" class="form-control" id="zip" placeholder="  EIR Code/Zip Code">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="job_skills">Job Skills</label>
                                    <select class="form-control" name="job_skills[]" id="job_skills" multiple>
                                        <option></option>
                                        @forelse ($skills as $row)
                                            <option value="{{ $row->name }}">
                                                {{ $row->name }}
                                            </option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="functional_area">Industry</label>
                                    <select class="form-control" name="functional_area" id="functional_area">
                                        <option></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" style="top: 25px;">
                                <div class="form-group">
                                    {{-- <a href="{{ route('admin.employer.create')}}">
                                    <input type="button" value="Industry" class="btn btn-success btn-block">  </a> --}}
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Industry</button>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="preferred_job_type">Preferred Job Type</label>
                                    <select class="form-control" name="preferred_job_type" id="preferred_job_type">
                                        <option></option>
                                        <option value="Full time">Full Time</option>
                                        <option value="Part time">Part Time</option>
                                        <option value="work from home">Work From Home</option>
                                        <option value="remote">Remote</option>
                                        <option value="temporarily remote">Temporarily Remote</option>
                                        <option value="Permanent">Permanent</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="preferred_job_type">Preferred Qualification</label>
                                    <select class="form-control" name="qualifications[]" id="qualifications" multiple>
                                        <option></option>
                                        @forelse ($qualifications as $row)
                                            <option value="{{ $row->name }}">
                                                {{ $row->name }}
                                            </option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="experience">Experience</label>
                                    <input type="text" autocomplete="off" class="form-control" id="experience" name="experience" placeholder="Experience">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for="total_hire">Total Hires</label>
                                  <input type="text" autocomplete="off" class="form-control" id="total_hire" name="total_hire" placeholder="Total Hires">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label for="post_job_type">Job Posting Platform</label>
                                <select name="post_job_type" id="post_job_type" class="form-control @error('post_job_type') is-invalid @enderror" onchange="toggleApplicationUrlField()">
                                    <option disabled selected>Choose option</option>
                                    <option value="recruit_ie" {{ old('post_job_type') == 'recruit_ie' ? 'selected' : '' }}>Recruit.ie</option>
                                    <option value="career_website" {{ old('post_job_type') == 'career_website' ? 'selected' : '' }}>Career Website</option>
                                </select>
                                @error('post_job_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mt-3 mt-md-0" id="application_url_wrapper" style="display: {{ old('post_job_type') == 'career_website' ? 'block' : 'none' }};">
                                <label for="application_url">Application URL</label>
                                <input type="url" name="application_url" id="application_url" value="{{ old('application_url') }}" class="form-control @error('application_url') is-invalid @enderror" placeholder="https://example.com/apply">
                                @error('application_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="job_details">Details</label>
                                  <textarea class="textarea" name="job_details" id="job_details" placeholder="Place some text here"
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                </div>
                            </div>
                        </div>
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


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Industry</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="instituteCreateFrm" id="instituteCreateFrm" action="{{ route('admin.industry.store') }}" method="POST">
                        @csrf
          <div class="form-group">
            <label for="name" class="col-form-label">Name</label>
            <input type="text" name="name" class="form-control" id="name" required>
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Description</label>
            <textarea class="form-control" name="description" id="description" required></textarea>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('myscript')<!-- InputMask -->
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
<script>
    function initialize() {
        var address = (document.getElementById('job_location'));
        var autocomplete = new google.maps.places.Autocomplete(address);
        autocomplete.setTypes(['geocode']);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }
            document.getElementById("start_latitude").value = place.geometry.location.lat();
            document.getElementById("start_longitude").value = place.geometry.location.lng();
            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
            }
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);

    ClassicEditor.create( document.querySelector( '#job_details' ) )
        .catch( error => {
            console.error( error );
    });
    function toggleApplicationUrlField() {
        const selected = document.getElementById('post_job_type');
        const wrapper = document.getElementById('application_url_wrapper');

        if (selected.value === 'career_website') {
            wrapper.style.display = 'block';
        } else {
            wrapper.style.display = 'none';
        }
    }
</script>
@include('admin.scripts.job')
@include('admin.scripts.location')
@endsection
