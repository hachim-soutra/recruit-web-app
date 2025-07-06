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
        select.form-control:not([size]):not([multiple]) {
            height: 52px !important;
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
                        <p>{{ Auth::user()->email }}&nbsp;|&nbsp;<i class="fa fa-user"></i>
                            {{ ucfirst(Auth::user()->user_type) }}</p>
                        <a href="javascript:void(0);" id="jobpostbtn">Edit Job</a>
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
                    <h4>Edit Posted Job <a href="{{ URL::previous() }}"><button type="button"
                                class="btn btn-sm btn-default" style="margin-left: 70%"><i class="fa fa-arrow-left"></i>
                                Back</button></a></h4>
                    <div class="jobpostform">
                        <form action="{{ route('employer.job_post.update', $jobDetail->id) }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Job Title</label>
                                    <input type="text" name="job_title" value="{{ $jobDetail->job_title }}"
                                        id="job_title" class="form-control" placeholder="Example: Software Developer">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Industry</label>
                                    <select name="industry" id="industry" class="form-control">
                                        <option disabled selected readonly>Choose one</option>
                                        @foreach ($data['industries'] as $i)
                                            <option value="{{ $i->name }}" <?php if ($i->name == $jobDetail->functional_area) {
                                                echo 'selected';
                                            } ?>>{{ $i->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Year Of Experience</label>
                                    <input type="text" name="experience" value="{{ $jobDetail->experience }}"
                                        id="experience" class="form-control" placeholder="Total Year Of Experience.">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Job Location</label>
                                    <input type="text" name="job_location" value="{{ $jobDetail->job_location }}"
                                        id="job_location" class="form-control" placeholder="Enter Job Location." />

                                    <input type="hidden" id="start_latitude" name="start_latitude" value="" />
                                    <input type="hidden" id="start_longitude" name="start_longitude" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Employment Type</label>
                                    <select name="preferred_job_type" class="form-control" id="job_type">
                                        <option disabled selected readonly>Choose a type</option>
                                        <option value="Permanent" <?php if ('Permanent' == $jobDetail->preferred_job_type) {
                                            echo 'selected';
                                        } ?>>Permanent</option>
                                        <option value="Temporary" <?php if ('Temporary' == $jobDetail->preferred_job_type) {
                                            echo 'selected';
                                        } ?>>Temporary</option>
                                        <option value="Fixed-Term" <?php if ('Fixed-Term' == $jobDetail->preferred_job_type) {
                                            echo 'selected';
                                        } ?>>Fixed-Term</option>
                                        <option value="Internship" <?php if ('Internship' == $jobDetail->preferred_job_type) {
                                            echo 'selected';
                                        } ?>>Internship</option>
                                        <option value="Commission-Only" <?php if ('Commission-Only' == $jobDetail->preferred_job_type) {
                                            echo 'selected';
                                        } ?>>Commission-Only</option>
                                        <option value="Freelance" <?php if ('Freelance' == $jobDetail->preferred_job_type) {
                                            echo 'selected';
                                        } ?>>Freelance</option>
                                        <option value="Part time" <?php if ('Part time' == $jobDetail->preferred_job_type) {
                                            echo 'selected';
                                        } ?>>Part time</option>
                                        <option value="Full time" <?php if ('Full time' == $jobDetail->preferred_job_type) {
                                            echo 'selected';
                                        } ?>>Full time</option>
                                        <option value="Work from home" <?php if ('Work from home' == $jobDetail->preferred_job_type) {
                                            echo 'selected';
                                        } ?>>Work from home</option>
                                        <option value="Remote" <?php if ('Remote' == $jobDetail->preferred_job_type) {
                                            echo 'selected';
                                        } ?>>Remote</option>
                                        <option value="Temporarily remote" <?php if ('Temporarily remote' == $jobDetail->preferred_job_type) {
                                            echo 'selected';
                                        } ?>>Temporarily remote</option>
                                    </select>
                                </div>
                                <div class="col-md-6 text-left text-md-center">
                                    <div class="row">
                                        <div class="col-md-6 text-left text-md-center">
                                            <label for="">Job Mode</label><br>
                                            <input type="radio" name="job_mode" id="job_mode" value="site"
                                                <?php if ($jobDetail->job_mode == 'site') {
                                                    echo 'checked';
                                                } ?>>Site
                                            <input type="radio" name="job_mode" id="job_mode" value="hybrid"
                                                <?php if ($jobDetail->job_mode == 'hybrid') {
                                                    echo 'checked';
                                                } ?>>Hybrid
                                            <input type="radio" name="job_mode" id="job_mode" value="remote"
                                                <?php if ($jobDetail->job_mode == 'remote') {
                                                    echo 'checked';
                                                } ?>>Remote
                                        </div>
                                        <div class="col-md-6 text-left text-md-center">
                                            <label for="">Hide Salary</label><br>
                                            <input type="radio" name="hide_salary" id="hide_salary" value="yes"
                                                <?php if ($jobDetail->hide_salary == 'yes') {
                                                    echo 'checked';
                                                } ?>>Yes
                                            <input type="radio" name="hide_salary" id="hide_salary" value="no"
                                                <?php if ($jobDetail->hide_salary == 'no') {
                                                    echo 'checked';
                                                } ?>>No
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row" style="padding: 22px 0 0 0;">
                                <div class="col-md-6">
                                    <label for="">Salary From</label>
                                    <input type="text" name="salary_from" value="{{ $jobDetail->salary_from }}"
                                        id="salary_from" class="form-control" placeholder="Salary from amount.">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Salary To</label>
                                    <input type="text" name="salary_to" value="{{ $jobDetail->salary_to }}"
                                        id="salary_to" class="form-control" placeholder="Salary to amount.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Salary Period</label>
                                    <select name="salary_period" id="salary_period" class="form-control">
                                        <option disabled readonly>Choose a salary type</option>
                                        <option value="Hourly" <?php if ($jobDetail->salary_period == 'Hourly') {
                                            echo 'selected';
                                        } ?>>Per Hour</option>
                                        <option value="Weekly" <?php if ($jobDetail->salary_period == 'Weekly') {
                                            echo 'selected';
                                        } ?>>Per Week</option>
                                        <option value="Monthly" <?php if ($jobDetail->salary_period == 'Monthly') {
                                            echo 'selected';
                                        } ?>>Per Month</option>
                                        <option value="Yearly" <?php if ($jobDetail->salary_period == 'Yearly') {
                                            echo 'selected';
                                        } ?>>Per Year</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Qualification</label>
                                    <select name="qualifications[]" id="qualifications"
                                        class="js-example-basic-multiple form-control qualificationmultiple"
                                        multiple="multiple">
                                        <option disabled readonly>Choose one</option>
                                        @foreach ($data['qualifications'] as $q)
                                            <option value="{{ $q->name }}"
                                                {{ $jobDetail->qualifications_array ? (in_array($q->name, $jobDetail->qualifications_array) ? 'selected' : '') : '' }}>
                                                {{ $q->name }}</option>
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
                                            <option value="{{ $sk->name }}"
                                                {{ $jobDetail->job_skills_array ? (in_array($sk->name, $jobDetail->job_skills_array) ? 'selected' : '') : '' }}>
                                                {{ $sk->name }}</option>
                                        @endforeach
                                        <option value="createnew">Create New Skill</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Benefit</label>
                                    <select name="additinal_pay[]" id="additinal_pay"
                                        class="js-example-basic-multiple form-control benefitmultiple" multiple>
                                        <option disabled readonly>Choose one</option>
                                        <option value="Bonus"
                                            {{ $jobDetail->additinal_pay_array ? (in_array('Bonus', $jobDetail->additinal_pay_array) ? 'selected' : '') : '' }}>
                                            Bonus</option>
                                        <option value="Overtime"
                                            {{ $jobDetail->additinal_pay_array ? (in_array('Overtime', $jobDetail->additinal_pay_array) ? 'selected' : '') : '' }}>
                                            Overtime</option>
                                        <option value="Commission"
                                            {{ $jobDetail->additinal_pay_array ? (in_array('Commission', $jobDetail->additinal_pay_array) ? 'selected' : '') : '' }}>
                                            Commission</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="padding: 22px 0 0 0;">
                                <div class="col-md-5">
                                    <label for="">Expire Date</label>
                                    <input type="date" name="job_expiry_date"
                                        value="{{ $jobDetail->job_expiry_date }}" id="expire_date" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Save Type</label><br>
                                    <input type="radio" name="job_status" id="job_status" value="Save as Draft"
                                        <?php if ($jobDetail->job_status == 'Save as Draft') {
                                            echo 'checked';
                                        } ?>>Save as draft<br>
                                    <input type="radio" name="job_status" id="job_status" value="Published"
                                        <?php if ($jobDetail->job_status == 'Published') {
                                            echo 'checked';
                                        } ?>>Publish
                                </div>
                                <div class="col-md-4">
                                    <label for="">No Of Hire</label><br>
                                    <input type="number" class="form-control" value="{{ $jobDetail->total_hire }}"
                                        name="total_hire" id="total_hire" placeholder="Total No Of Hires.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <label for="post_job_type">Job Posting Platform</label>
                                    <select name="post_job_type" id="post_job_type" class="form-control" onchange="toggleApplicationUrlField()">
                                        <option disabled {{ old('post_job_type', $jobDetail->post_job_type) ? '' : 'selected' }}>Choose option</option>
                                        <option value="recruit_ie" {{ old('post_job_type', $jobDetail->post_job_type) === 'recruit_ie' ? 'selected' : '' }}>Recruit.ie</option>
                                        <option value="career_website" {{ old('post_job_type', $jobDetail->post_job_type) === 'career_website' ? 'selected' : '' }}>Career Website</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0" id="application_url_wrapper"
                                    style="display: {{ old('post_job_type', $jobDetail->post_job_type) === 'career_website' ? 'block' : 'none' }};">
                                    <label for="application_url">Application URL</label>
                                    <input type="url" name="application_url" id="application_url"
                                        value="{{ old('application_url', $jobDetail->application_url) }}"
                                        class="form-control" placeholder="https://example.com/apply">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Job Details</label>
                                    <textarea class="textarea" name="job_details" value="{{ $jobDetail->job_details }}" id="job_details"
                                        placeholder="Write Down Job Details Here."
                                        style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $jobDetail->job_details }}</textarea>
                                </div>
                            </div>
                            <input type="hidden" name="rowid" id="rowid" value="{{ $jobDetail->id }}">
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
        ClassicEditor.create(document.querySelector('#job_details'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
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
            let qualifications = '{!! $jobDetail->qualifications !!}';
            $("#qualifications").select2().val(eval(qualifications)).trigger('change.select2');
            let job_skills = '{!! $jobDetail->job_skills !!}';
            $("#job_skills").select2().val(eval(job_skills)).trigger('change.select2');
            let additinal_pay = '["{!! $jobDetail->additinal_pay !!}"]';
            $("#additinal_pay").select2().val(eval(additinal_pay)).trigger('change.select2');
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
                $('.jobpostform').find(':input').addClass('focusBorder');
                setTimeout(() => {
                    $('.jobpostform').find(':input').removeClass('focusBorder');
                }, 2000);
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
            /* 333 */
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <script>
        function toggleApplicationUrlField() {
            const postJobType = document.getElementById('post_job_type').value;
            const appUrlWrapper = document.getElementById('application_url_wrapper');
            appUrlWrapper.style.display = (postJobType === 'career_website') ? 'block' : 'none';
        }

        // Trigger on page load (in case of edit or validation error)
        document.addEventListener('DOMContentLoaded', function () {
            toggleApplicationUrlField();
        });
    </script>
@endsection
