@extends('site.layout.app')
@section('title', 'Education Qualification')
@section('mystyle')
    <style>
        .bd-block {
            width: 66% !important;
        }
    </style>
@endsection
@section('content')
    <!-- banner-block -->
    <style>
        /*.js-example-basic-multiple {
      width: 300px !important;
     }
     .skillsubmit{
      border-radius: 25px;
      height: 32px;
      background-color: white;
      color: black;
      font-size: smaller;
     }
     .edusubmit{
      border-radius: 25px;
      height: 32px;
      background-color: white;
      color: black;
      font-size: smaller;
     }
     .langsubmit{
      border-radius: 25px;
      height: 32px;
      background-color: white;
      color: black;
      font-size: smaller;
     }
     .spantext{
      color: #eb1829 !important;
     }
     .submitformbtn{
      display: flex;
      height: 46px;
      justify-content: center;
      align-content: stretch;
        align-items: center;
     }
     .focusBorder{
      border: 1px solid #0d8f82 !important;
     }
        .select2-container{
            width: 100% !important;
        }
        .select2-container .select2-selection--single{
      display: flex !important;
        height: 45px !important;
     }
        .fa.fa-upload{
      font-size: 22px;
      color: #eb1829;
      margin-top: 30px;
      margin-left: -90px;
      border: 1px solid;
      border-radius: 13px;
     }*/
        .mail-sctm {
            border: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            padding-left: 0 !important;
            background: unset !important;
            padding-right: 5px !important;
        }
    </style>
    <script>
        function activeEdit() {
            toastr.success("Edit Mode Active.");
            $('#editsection').find(':input').each(function() {
                $(this).addClass('focusBorder');
            });
            $('#fullname').focus();
            $('.submitformbtn').show();
        }
    </script>
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
                    <div>
                        <form action="{{ route('upload-file') }}" enctype="multipart/form-data" id="profile_pic_upload"
                            method="post">
                            @csrf
                            <label for="files" class="btn"><i class="fa fa-camera" aria-hidden="true"></i></label>
                            <input type="file" id="files" onchange="upload()" style="display:none;"
                                name="upload_image" id="upload_image">
                        </form>
                    </div>
                    <div class="text">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p><a class="mail-sctm" href="mailto:{{ Auth::user()->email }}">{{ Auth::user()->email }}</a>
                            &nbsp;&nbsp;|&nbsp; <i class="fa fa-user mr-2"></i> {{ ucfirst(Auth::user()->user_type) }}</p>
                        <a href="javascript:void(0);" onclick="activeEdit()">Edit</a>
                    </div>
                </div>
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-warning" role="alert">
                            {{ $error }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <script>
                            activeEdit();
                        </script>
                    @endforeach
                @endif
                @if (session('success'))
                    <script>
                        toastr.success("{{ session('success') }}");
                    </script>
                @endif
                <div class="item-headline">
                    <div class="d-flex flex-wrap">
                        <h4>Education Qualification</h4>
                        <div class="ml-auto">
                            <a href="{{ URL::previous() }}">
                                <button type="button" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i>
                                    Back</button>
                            </a>
                        </div>
                    </div>



                    <form id="editsection" action="{{ route('profile-update-candidate') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">First Name</label>
                                <input type="text" name="first_name" id="first_name"
                                    value="{{ Auth::user()->first_name }}" class="form-control"
                                    placeholder="Enter First Name.">
                            </div>
                            <div class="col-md-6">
                                <label for="">Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="{{ Auth::user()->last_name }}"
                                    class="form-control" placeholder="Enter Last Name.">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Your Address</label>
                                <input type="text" name="address" value="{{ @$data['candidateDetail']->address }}"
                                    id="address" placeholder="Enter your address" class="form-control" />

                                <input type="hidden" id="start_latitude" name="start_latitude" value="" />
                                <input type="hidden" id="start_longitude" name="start_longitude" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Gender</label>
                                <select name="gender" class="form-control gender" id="gender">
                                    <option selected disabled readonly>Choose gender</option>
                                    <option value="Male"
                                        <?= @$data['candidateDetail']->gender == 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female"
                                        <?= @$data['candidateDetail']->gender == 'Female' ? 'selected' : '' ?>>Female
                                    </option>
                                    <option value="Prefer to not say"
                                        <?= @$data['candidateDetail']->gender == 'Prefer to not say' ? 'selected' : '' ?>>
                                        Prefer to not say</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Date Of Birth</label>
                                <input type="date" name="dob" id="dob" onchange="dobGt18()"
                                    value="{{ @$data['candidateDetail']->date_of_birth }}" class="form-control">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 25px;">
                            <div class="col-md-6">
                                <label for="">Mobile (Along With Country Code)</label>
                                <input type="text" name="alternate_mobile_number"
                                    value="{{ @$data['candidateDetail']->alternate_mobile_number }}"
                                    id="alternate_mobile_number" class="form-control"
                                    placeholder="Alternate Mobile Number">
                            </div>
                            <div class="col-md-6">
                                <label for="">Email-ID</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    value="{{ Auth::user()->email }}" placeholder="Your Email-ID">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Candidate Type</label>
                                <select name="candidate_type" id="candidate_type" class="form-control candidate_type">
                                    <option selected disabled readonly>Choose a type</option>
                                    <option value="Graduate"
                                        <?= @$data['candidateDetail']->candidate_type == 'Graduate' ? 'selected' : '' ?>>
                                        Graduate</option>
                                    <option value="Entry level"
                                        <?= @$data['candidateDetail']->candidate_type == 'Entry level' ? 'selected' : '' ?>>
                                        Entry level</option>
                                    <option value="Manager"
                                        <?= @$data['candidateDetail']->candidate_type == 'Manager' ? 'selected' : '' ?>>
                                        Manager</option>
                                    <option value="Director"
                                        <?= @$data['candidateDetail']->candidate_type == 'Director' ? 'selected' : '' ?>>
                                        Director</option>
                                    <option value="C-Level"
                                        <?= @$data['candidateDetail']->candidate_type == 'C-Level' ? 'selected' : '' ?>>
                                        C-Level</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 25px;">
                            <div class="col-md-6">
                                <label for="">Current Salary</label>
                                <input type="text" name="current_salary"
                                    value="{{ @$data['candidateDetail']->current_salary }}" id="current_salary"
                                    class="form-control" placeholder="Current Salary">
                            </div>
                            <div class="col-md-6">
                                <label for="">Notice Period (In Days)</label>
                                <input type="text" name="notice_period"
                                    value="{{ @$data['candidateDetail']->notice_period }}" id="notice_period"
                                    class="form-control" placeholder="Notice Period">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Work Permit Status</label>
                                <input type="text" name="visa_status"
                                    value="{{ @$data['candidateDetail']->visa_status }}" id="visa_status"
                                    class="form-control" placeholder="Visa Status">
                            </div>
                            <div class="col-md-6">
                                <label for="">Linkedin Url</label>
                                <input type="text" name="linkedin_link"
                                    value="{{ @$data['candidateDetail']->linkedin_link }}" id="linkedin_link"
                                    class="form-control" placeholder="Linkedin Link">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Portfolio Links Or Video CV</label>
                                <input type="text" name="portfolio_link"
                                    value="{{ @$data['candidateDetail']->portfolio_link }}" id="portfolio_link"
                                    class="form-control" placeholder="Portfolio Links Or Video CV">
                            </div>
                            <div class="col-md-6">
                                <label for="">Language(S) You Know</label>
                                <select class="js-example-basic-multiple langmultiple" name="languages[]"
                                    multiple="multiple">
                                    <option disabled readonly>Choose one</option>
                                    @foreach ($data['language'] as $l)
                                        <option value="{{ $l->name }}" <?php if (@$data['candidateDetail']->languages == $l->name) {
                                            echo 'selected';
                                        } ?>>{{ $l->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Job Role</label>
                                <select name="functional_id" id="functional_id" class="form-control functional_id">
                                    <option disabled readonly selected>Choose a role</option>
                                    @foreach ($data['industry'] as $i)
                                        <option value="{{ $i->id }}"
                                            <?= @$data['candidateDetail']->functional_id == $i->id ? 'selected' : '' ?>>
                                            {{ $i->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 25px;">
                            <div class="col-md-6">
                                <label for="">Preferred Job Type</label>
                                <select name="preferred_job_type" class="form-control preferred_job_type"
                                    id="preferred_job_type">
                                    <option selected disabled readonly>Choose a type</option>
                                    <option value="Part time"
                                        <?= @$data['candidateDetail']->preferred_job_type == 'Part time' ? 'selected' : '' ?>>
                                        Part time</option>
                                    <option value="Full time"
                                        <?= @$data['candidateDetail']->preferred_job_type == 'Full time' ? 'selected' : '' ?>>
                                        Full time</option>
                                    <option value="Part time & Full time"
                                        <?= @$data['candidateDetail']->preferred_job_type == 'Part time & Full time' ? 'selected' : '' ?>>
                                        Part time & Full time</option>
                                    <option value="Work from home"
                                        <?= @$data['candidateDetail']->preferred_job_type == 'Work from home' ? 'selected' : '' ?>>
                                        Work from home</option>
                                    <option value="Remote"
                                        <?= @$data['candidateDetail']->preferred_job_type == 'Remote' ? 'selected' : '' ?>>
                                        Remote</option>
                                    <option value="Temporarily remote"
                                        <?= @$data['candidateDetail']->preferred_job_type == 'Temporarily remote' ? 'selected' : '' ?>>
                                        Temporarily remote</option>
                                    <option value="Freelance"
                                        <?= @$data['candidateDetail']->preferred_job_type == 'Freelance' ? 'selected' : '' ?>>
                                        Freelance</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Expected Salary</label>
                                <input type="text" name="expected_salary"
                                    value="{{ @$data['candidateDetail']->expected_salary }}" id="expected_salary"
                                    class="form-control" placeholder="Expected Salary">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-red-cs submitformbtn w-100"><i
                                        class="fa fa-edit mr-1"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- post-resume-block -->

@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';
        $(document).ready(function() {
            skillselect2();
            setLangsValue();
            select2();
            $('.submitformbtn').show();
            // $('#editsection').find(':input').each(function(){
            //     $(this).removeClass('focusBorder');
            // });
        });

        function upload() {
            $('form#profile_pic_upload').submit();
        }

        function textInput(event) {
            var value = String.fromCharCode(event.which);
            var pattern = new RegExp(/[a-zåäö ]/i);
            return pattern.test(value);
        }
        $('#first_name').bind('keypress', textInput);
        $('#last_name').bind('keypress', textInput);
        /* common ajax */
        function commomAjax(ajax_url, data, msg) {
            let ajaxresponse = false;
            $.ajax({
                url: ajax_url,
                type: 'POST',
                data: data,
                success: function(res) {
                    if (res == true) {
                        toastr.success(msg);
                        if (data.queryfor == 'skill') {
                            plusSkillcollapse();
                        }
                        if (data.queryfor == 'education') {
                            plusEducollapse();
                        }
                        if (data.queryfor == 'language') {
                            plusLangcollapse();
                        }
                    }
                    ajaxresponse = true;
                },
                error: function(err) {
                    console.log(err);
                    ajaxresponse = false;
                }
            });
            return ajaxresponse;
        }
        setTimeout(() => {
            $('#editsection').find(':input').each(function() {
                $(this).removeClass('focusBorder');
            });
        }, 4000);

        function skillselect2() {
            $('.langmultiple').select2({
                placeholder: "Select your languages."
            });
        }

        function setLangsValue() {
            let langs = "{{ @$data['candidateDetail']->languages }}";
            langs = langs.split(',');
            let html = '';
            for (let i = 0; i < langs.length; i++) {
                if (i == 0) {
                    html += "[";
                }
                html += "'" + $.trim(langs[i]) + "'";
                if (i < (langs.length - 1)) {
                    html += ",";
                }
                if (i == (langs.length - 1)) {
                    html += "]";
                }
            }
            $(".langmultiple").select2().val(eval(html)).trigger('change.select2');
        }

        function select2() {
            $('.gender').select2();
            $('.candidate_type').select2();
            $('.functional_id').select2();
            $('.preferred_job_type').select2();
        }

        function dobGt18() {
            let dob = new Date($('#dob').val());
            let dobYr = dob.getFullYear();
            let toyear = new Date().getFullYear();
            if ((parseInt(toyear) - parseInt(dobYr)) < 18) {
                toastr.warning("Age must be grater then 18.");
                $('#dob').val('');
                return false;
            }
            return true;
        }
    </script>
    <script>
        function initialize() {
            var address = (document.getElementById('address'));
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
@endsection
