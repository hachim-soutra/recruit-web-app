@extends('site.layout.app')
@section('title', 'Profile')
@section('mystyle')
    <style>
        .bd-block {
            width: 66% !important;
        }
        .btn-cs {
            margin: 0;
            padding: 12px 35px;
            font-family: 'Circular Std';
            font-size: 13px;
            line-height: 17px;
            border-radius: 50px;
            cursor: pointer;
            background-size: 12px;
            font-weight: 500;
        }
    </style>
@endsection
@section('content')
    <!-- banner-block -->
    <style>
        .mail-sctm {
            border: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            padding-left: 0 !important;
            background: unset !important;
            padding-right: 5px !important;
        }

        .prf-serch {
            overflow: hidden;
            display: flex;
            justify-content: center;
            border-bottom: 1px solid;
            width: 95%;
            margin: 5px 5px 5px 14px;
            border-bottom: solid 1px #E8E8E8;
        }

        .prf-serch button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 12px 25px !important;
            transition: 0.3s;
            font-size: 14px;
            border-bottom: solid 2px transparent;
            font-weight: 600;
        }

        .prf-serch button.active {
            . color: var(--red-color);
            border-bottom: solid 2px var(--red-color);
        }

    </style>
    <script>
        $(document).ready(function() {
            let hash = window.location.hash;
            hash = hash.replace('#', '');
            if (hash !== '') {
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(hash).style.display = "block";
                document.getElementsByClassName(hash).item(0).className += " active";
                if (['attach-cv', 'job-activity', 'work-experience', 'job-activity'].includes(hash)) {
                    $('#candidate-profile-submit-row').hide();
                } else {
                    $('#candidate-profile-submit-row').show();
                }
            }

        });

        function activeEdit() {
            toastr.success("Edit Mode Active.");
            $('#editsection').find(':input').each(function() {
                $(this).addClass('focusBorder');
            });
            $('#fullname').focus();
            $('.submitformbtn').show();
        }
        function openTab(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
            if (['attach-cv', 'job-activity', 'work-experience', 'job-activity'].includes(cityName)) {
                $('#candidate-profile-submit-row').hide();
            } else {
                $('#candidate-profile-submit-row').show();
            }
            window.location.hash = cityName;
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
                        <div class="pro-pic-up">
                            <form action="{{ route('upload-file') }}" enctype="multipart/form-data"
                                  id="candidate_profile_pic_upload" method="post">
                                @csrf
                                <label for="files" class="btn"><i class="fa fa-camera"
                                                                  aria-hidden="true"></i></label>
                                <input type="file" id="files" onchange="upload('candidate_profile_pic_upload')"
                                       style="display:none;" name="upload_image" id="upload_image">
                            </form>
                        </div>
                    </div>
                    <div class="text">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p><a class="mail-sctm" href="mailto:{{ Auth::user()->email }}">{{ Auth::user()->email }}</a>
                            &nbsp;&nbsp;|&nbsp; <i class="fa fa-user mr-2"></i>{{ ucfirst(Auth::user()->user_type) }}
                        </p>

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





                <div class="tab prf-serch">
                    <button class="tablinks personal-details active" onclick="openTab(event, 'personal-details')" >
                        Personal Details
                    </button>
                    <button class="tablinks career-details" onclick="openTab(event, 'career-details')">
                        Career Details
                    </button>
                    <button class="tablinks work-experience" onclick="openTab(event, 'work-experience')">
                        Work Experience
                    </button>
                    <button class="tablinks attach-cv" onclick="openTab(event, 'attach-cv')">
                        Attach CV/CL
                    </button>
                    <button class="tablinks job-activity" onclick="openTab(event, 'job-activity')">
                        Job Activity
                    </button>
                </div>

                <form id="editsection" action="{{ route('profile-update-candidate') }}" method="POST">
                    @csrf
                    @include('site.pages.seeker.profile.personal_detail')
                    @include('site.pages.seeker.profile.career_detail')
                    <div class="row" id="candidate-profile-submit-row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-red-cs submitformbtn w-100"><i
                                    class="fa fa-edit mr-1"></i> Submit</button>
                        </div>
                    </div>
                </form>
                @include('site.pages.seeker.profile.work_experience')
                @include('site.pages.seeker.profile.attach_cv')
                @include('site.pages.seeker.profile.job_activity')
            </div>
        </div>
    </div>
    <!-- post-resume-block -->

@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';
        $(document).ready(function() {
            setLangsValue();
            select2();
            $('.submitformbtn').show();
            // $('#editsection').find(':input').each(function(){
            //     $(this).removeClass('focusBorder');
            // });
        });

        function upload() {
            $('form#candidate_profile_pic_upload').submit();
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
            $('.skillmultiple').select2();
            $('.edumultiple').select2();
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
