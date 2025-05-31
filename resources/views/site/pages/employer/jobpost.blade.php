@extends('site.layout.app')
@section('title', 'Job Post')
@section('mystyle')
    <style>
        .focusBorder {
            border: 1px solid #0d8f82 !important;
        }

        .js-example-basic-multiple {
            width: 100% !important;
        }

        .bd-block {
            width: 66% !important;
        }

        .select2-container .select2-selection--single {
            display: flex !important;
            height: 45px !important;
        }

        .select2-container {
            width: 100% !important;
        }

        .mail-sctm {
            border: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            padding-left: 0 !important;
            background: unset !important;
            padding-right: 5px !important;
        }
    </style>
@endsection
@section('content')

    <!-- post-resume-block -->
    <div class="post-resume-block">
        <div class="container">
            <div class="bd-block">
                <div class="col-top">
                    <div class="figure">
                        @if (Auth::user()->avatar != '')
                            <img src="{{ asset(Auth::user()->avatar) }}">
                        @else
                            <img src="{{ asset('frontend/images/icon11.png') }}">
                        @endif
                    </div>
                    <div class="text">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p><a class="mail-sctm" href="mailto:{{ Auth::user()->email }}">{{ Auth::user()->email }}</a>
                            &nbsp;|&nbsp;<i class="fa fa-user"></i> {{ ucfirst(Auth::user()->user_type) }}</p>
                        <a href="javascript:void(0);" id="jobpostbtn" onclick="postJob_formShow()">Post Job</a>
                    </div>
                </div>
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <script>
                            toastr.error("{{ $error }}");
                        </script>
                    @endforeach
                @endif
                @if (session('success'))
                    <script>
                        toastr.success("{{ session('success') }}");
                    </script>
                @endif
                @if (session('error'))
                    <script>
                        toastr.error("{{ session('error') }}");
                    </script>
                @endif
                <div class="item-headline">
                    <h4>Post Job Here. <a href="{{ URL::previous() }}"><button type="button" class="btn btn-sm btn-default"
                                style="margin-left: 71%"><i class="fa fa-arrow-left"></i>
                                Back</button></a></h4>
                    <div class="jobpostform">
                        <form action="{{ route('job-post-save') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Job Title</label>
                                    <input type="text" name="job_title" value="{{ old('job_title') }}" id="job_title"
                                        class="form-control" placeholder="Example: Software Developer">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Industry</label>
                                    <select name="industry" id="industry" class="form-control industry">
                                        <option disabled selected readonly>Choose one</option>
                                        @foreach ($data['industries'] as $i)
                                            <option {{ old('industry') == $i->name ? 'selected' : '' }}
                                                value="{{ $i->name }}">{{ $i->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Year Of Experience</label>
                                    <input type="text" name="experience" id="experience" class="form-control numberonly"
                                        placeholder="Total Year Of Experience." value="{{ old('experience') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Job Location</label>
                                    <input type="text" name="job_location" id="job_location" class="form-control"
                                        placeholder="Enter Job Location." value="{{ old('job_location') }}" />

                                    <input type="hidden" id="start_latitude" name="start_latitude"
                                        value="{{ old('start_latitude') }}" />
                                    <input type="hidden" id="start_longitude" name="start_longitude"
                                        value="{{ old('start_longitude') }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Employment Type</label>
                                    <select name="preferred_job_type" class="form-control job_type" id="job_type">
                                        <option disabled selected readonly>Choose a type</option>
                                        <option {{ old('preferred_job_type') == 'Permanent' ? 'selected' : '' }}
                                            value="Permanent">Permanent</option>
                                        <option {{ old('preferred_job_type') == 'Temporary' ? 'selected' : '' }}
                                            value="Temporary">Temporary</option>
                                        <option {{ old('preferred_job_type') == 'Fixed-Term' ? 'selected' : '' }}
                                            value="Fixed-Term">Fixed-Term</option>
                                        <option {{ old('preferred_job_type') == 'Internship' ? 'selected' : '' }}
                                            value="Internship">Internship</option>
                                        <option {{ old('preferred_job_type') == 'Commission-Only' ? 'selected' : '' }}
                                            value="Commission-Only">Commission-Only</option>
                                        <option {{ old('preferred_job_type') == 'Freelance' ? 'selected' : '' }}
                                            value="Freelance">Freelance</option>
                                        <option {{ old('preferred_job_type') == 'Part time' ? 'selected' : '' }}
                                            value="Part time">Part time</option>
                                        <option {{ old('preferred_job_type') == 'Full time' ? 'selected' : '' }}
                                            value="Full time">Full time</option>
                                        <option {{ old('preferred_job_type') == 'Work from' ? 'selected' : '' }}
                                            value="Work from home">Work from home</option>
                                        <option {{ old('preferred_job_type') == 'Remote' ? 'selected' : '' }}
                                            value="Remote">Remote</option>
                                        <option {{ old('preferred_job_type') == 'Temporarily remote' ? 'selected' : '' }}
                                            value="Temporarily remote">Temporarily remote</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 text-left text-md-center">
                                            <label for="">Job Mode</label><br>
                                            <input type="radio" name="job_mode"
                                                {{ old('job_mode') == 'site' ? 'checked' : '' }} id="job_mode"
                                                value="site">Site
                                            <input type="radio" name="job_mode"
                                                {{ old('job_mode') == 'hybrid' ? 'checked' : '' }} id="job_mode"
                                                value="hybrid">Hybrid
                                            <input type="radio" name="job_mode"
                                                {{ old('job_mode') == 'remote' ? 'checked' : '' }} id="job_mode"
                                                value="remote">Remote
                                        </div>
                                        <div class="col-md-6 text-left text-md-center mt-3 mt-md-0">
                                            <label for="">Hide Salary</label><br>
                                            <input type="radio" name="hide_salary"
                                                {{ old('hide_salary') == 'yes' ? 'checked' : '' }} id="hide_salary"
                                                value="yes">Yes
                                            <input type="radio" name="hide_salary"
                                                {{ old('hide_salary') == 'no' ? 'checked' : '' }} id="hide_salary"
                                                value="no">No
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="padding: 22px 0 0 0;">
                                <div class="col-md-6">
                                    <label for="">Salary From</label>
                                    <input type="number" name="salary_from" id="salary_from"
                                        class="form-control numberonly" placeholder="Salary from amount."
                                        value="{{ old('salary_from') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Salary To</label>
                                    <input type="number" name="salary_to" id="salary_to"
                                        class="form-control numberonly" placeholder="Salary to amount."
                                        value="{{ old('salary_to') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Salary Period</label>
                                    <select name="salary_period" id="salary_period" class="form-control salary_period">
                                        <option disabled readonly>Choose a salary type</option>
                                        <option {{ old('salary_period') == 'Hourly' ? 'selected' : '' }} value="Hourly">
                                            Per Hour</option>
                                        <option {{ old('salary_period') == 'Weekly' ? 'selected' : '' }} value="Weekly">
                                            Per Week</option>
                                        <option {{ old('salary_period') == 'Monthly' ? 'selected' : '' }} value="Monthly">
                                            Per Month</option>
                                        <option {{ old('salary_period') == 'Yearly' ? 'selected' : '' }} value="Yearly">
                                            Per Year</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Qualification</label>
                                    <select name="qualifications[]" id="qualifications"
                                        class="js-example-basic-multiple form-control qualificationmultiple"
                                        multiple="multiple">
                                        <option disabled readonly>Choose one</option>
                                        @foreach ($data['qualifications'] as $q)
                                            <option {{ in_array($q->name, old('qualifications') ?? []) ? 'selected' : '' }}
                                                value="{{ $q->name }}">{{ $q->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="padding: 22px 0 0 0;">
                                <div class="col-md-6">
                                    <label for="">Select Skills</label>
                                    <select class="js-example-basic-multiple skillmultiple" id="job_skills"
                                        name="job_skills[]" multiple="multiple">
                                        <option disabled readonly>Choose One</option>
                                        @foreach ($data['skills'] as $sk)
                                            <option {{ in_array($sk->name, old('job_skills') ?? []) ? 'selected' : '' }}
                                                value="{{ $sk->name }}">{{ $sk->name }}</option>
                                        @endforeach
                                        <option value="createnew">Create New Skill</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Benefit</label>
                                    <select name="additinal_pay[]" id="additinal_pay"
                                        class="js-example-basic-multiple form-control benefitmultiple" multiple>
                                        <option disabled readonly>Choose one</option>
                                        <option {{ in_array('Bonus', old('additinal_pay') ?? []) ? 'selected' : '' }}
                                            value="Bonus">Bonus</option>
                                        <option {{ in_array('Overtime', old('additinal_pay') ?? []) ? 'selected' : '' }}
                                            value="Overtime">Overtime</option>
                                        <option {{ in_array('Commission', old('additinal_pay') ?? []) ? 'selected' : '' }}
                                            value="Commission">Commission</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="padding: 22px 0 0 0;">
                                <div class="col-md-5">
                                    <label for="">Expire Date</label>
                                    <input type="date" name="job_expiry_date" id="expire_date" class="form-control"
                                        value="{{ old('job_expiry_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Save Type</label><br>
                                    <input type="radio" name="job_status" id="job_status" value="Save as Draft"
                                        {{ old('job_status') == 'Save as Draft' ? 'checked' : '' }}>Save
                                    as draft<br>
                                    <input type="radio" name="job_status" id="job_status" value="Published"
                                        {{ old('job_status') == 'Published' ? 'checked' : '' }}>Publish
                                </div>
                                <div class="col-md-4">
                                    <label for="">No Of Hire</label><br>
                                    <input type="number" class="form-control numberonly" name="total_hire"
                                        id="total_hire" placeholder="Total No Of Hires."
                                        value="{{ old('total_hire') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Job Details</label>
                                    <textarea class="textarea" name="job_details" id="job_details" placeholder="Write Down Job Details Here."
                                        style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('job_details') }}</textarea>
                                </div>
                            </div>
                            <div class="row" style="padding: 22px 0 0 0;">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-submit"
                                        style="background-color:#eb1829;color:white;width:100%;"><i
                                            class="fa fa-send-o"></i>&nbsp;Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body"></div>

                <!-- Modal footer -->
                <div class="modal-footer">

                </div>

            </div>
        </div>
    </div>
    <!-- post-resume-block -->

@endsection
@section('myscript')
    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';
        ClassicEditor.create(document.querySelector('#job_details'));
        var trigger = "{{ session()->get('trigger') }}";
        if (trigger != '') {
            setTimeout(() => {
                let showmsg = 1;
                postJob_formShow(showmsg);
            }, 500);
        }
        $(document).ready(function() {
            $('#job_skills').select2({
                placeholder: "Select a skill."
            });
            $('#qualifications').select2({
                placeholder: "Select qualifications."
            });
            $('#additinal_pay').select2({
                placeholder: "Select any additional pay."
            });
            postJob_formShow();
        });

        function postJob_formShow(showmsg = null) {
            $('#jobpostbtn').removeAttr('onclick', 'postJob_formShow()');
            $('#jobpostbtn').attr('onclick', 'postJob_formHide()');
            $('.jobpostform').show();
            $('html, body').animate({
                scrollTop: $('.jobpostform').offset().top
            }, 'slow');
            if (showmsg == '1') {
                toastr.success("Job Post Form Enabled.");
                // $('.jobpostform').find(':input').addClass('focusBorder');
                // setTimeout(() => {
                //     $('.jobpostform').find(':input').removeClass('focusBorder');
                // }, 2000);
            }
        }

        function postJob_formHide() {
            $('#jobpostbtn').removeAttr('onclick', 'postJob_formHide()');
            $('#jobpostbtn').attr('onclick', 'postJob_formShow()');
            $('.jobpostform').hide();
        }
        $('#job_skills').on('change', function() {
            if ($(this).val() == 'createnew') {
                $('#myModal').modal('show');
                modalForm('skill');
            }
        });

        function modalForm(fieldType) {
            $('.modal-title').text('Add a skill');
            $('.modal-body').html('');
            $('.modal-footer').html('');
            let html = '';
            html += '<form id="modalform"><div class="row">';
            html += '<div class="col-md-12"><label>Skill Name *</label><input type="text" id="new_' + fieldType +
                '_name" class="form-control" placeholder="Enter a skill name."></div></div>';
            html +=
                '<div class="row"><div class="col-md-12"><label>Skill Description *</label><input type="text" id="new_' +
                fieldType + '_description" class="form-control" placeholder="Enter skill Description."></div>';
            html += '</div></form>';
            $('.modal-body').append(html);
            let buttonhtml = '';
            buttonhtml += '<div class="row">';
            buttonhtml +=
                '<div class="col-md-6"><button type="button" class="btn btn-submit" onclick="addNewSkill()" style="background-color:#eb1829;color:white;"><i class="fa fa-edit"></i>&nbsp;Submit</button></div>';
            buttonhtml += '</div>';
            $('.modal-footer').append(buttonhtml);
        }

        function addNewSkill() {
            let name = $('#new_skill_name').val();
            let description = $('#new_skill_description').val();
            $.ajax({
                url: APP_URL + '/jobpost-common-ajax',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: name,
                    description: description,
                    type: 'skill'
                },
                success: function(res) {
                    if (res.code == '403') {
                        modalForm('skill');
                        let err = res.error;
                        if (err.hasOwnProperty('name')) {
                            for (let i = 0; i < err.name.length; i++) {
                                toastr.error(err.name[i]);
                            }
                        };
                        if (err.hasOwnProperty('description')) {
                            for (let i = 0; i < err.description.length; i++) {
                                toastr.error(err.description[i]);
                            }
                        };
                    };
                    if (res.code == '200') {
                        toastr.success("New Skill Added Successfully.");
                        $('#myModal').modal('hide');
                    }
                },
                error: function(err) {
                    console.log(err);
                },
            });
        }
        $('.numberonly').keypress(function(e) {
            var charCode = (e.which) ? e.which : event.keyCode
            if (String.fromCharCode(charCode).match(/[^0-9]/g))
                return false;
        });
        setTimeout(() => {
            $('.alert').hide();
        }, 3500);
    </script>
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
    </script>
    <script>
        $(document).ready(function() {
            $('.industry').select2();
            $('.job_type').select2();
            $('.salary_period').select2();
        });
    </script>
@endsection
