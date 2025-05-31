@extends('site.layout.app')
@section('title', 'Dashboard')
@section('mystyle')
    <style>
        .heading {
            position: relative;
            width: 100%;
            min-height: 66px;
            padding-right: 15px;
            padding-left: 15px;
        }

        .appliedbtn {
            border-radius: 25px;
            font-weight: lighter;
            font-size: 13px;
            height: 37px;
            padding-left: 20px;
            padding-right: 20px;
        }

        .appliedbtn i {
            font-size: 12px !important;
        }

        .appliedtext {
            border: 1px solid;
            border-radius: 10px;
            width: 15%;
            text-align: center;
            color: green !important;
            font-size: 12px;
            font-weight: 500;
        }

        .profileimg {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            margin-right: 10px;
            margin-top: -10px;
        }

        .socialicon {
            font-size: 24px;
            border: 1px solid;
            width: 25%;
            display: flex;
            border-radius: 50%;
            justify-content: center;
            height: 22%;
        }

        .banner-block {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            min-height: 370px !important;
            padding: 25px 0 0 0 !important;
        }

        .applicants {
            border-radius: 11px;
            height: 35px;
            float: right;
        }
    </style>
    <style>
        .img {
            width: 100%;
            height: 400px;
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
        }

        .university h3 {
            font-size: 16px;
            font-weight: 500;
        }

        .tablinks {
            border: none;
            margin-right: 80px;
            border-top: none;
            cursor: pointer;
        }

        .tablinks:focus {
            box-shadow: none;
            outline: none;
            border: 0px;
        }

        .tablinks.last {
            margin-right: 0px !important;
        }

        .tabrow {
            border-bottom: 1px solid #8080801f;
            margin-left: -2px;
            margin-top: 4%;
        }

        .tab .active {
            color: #c12128 !important;
        }

        .tabcontent {
            padding: 1%;
            display: none;
        }

        .findbtn {
            width: 101%;
            color: white;
            background-color: #c12128;
            margin-left: -5px;
            margin-top: 15px;
        }

        .pagination {
            width: 100%;
        }

        .select2-container .select2-container--default .select2-container--open {
            top: 137px !important;
            left: 786.219px !important;
        }

        .select2-container--open .select2-dropdown--below {
            width: 336.016px !important;
            margin-left: -31px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px !important;
        }

        .select2-container--default .select2-selection--single {
            border: none !important;
        }

        .select2-container .select2-selection--single {
            height: 50px !important;
            display: flex !important;
            align-items: center !important;
        }

        .inner-form-custom .banner-block .banner-bd h1 {
            display: block !important;
        }

        .skill-listing {
            margin-left: 5%;
        }

        .skill-listing li {
            font-size: 15px;
            font-weight: 600;
        }

        .h5span {
            color: #ed1c24;
            font-size: 25px;
            font-weight: 900;
        }
    </style>
@endsection
@section('content')
    <div class="post-resume-one pt-5">
        <div class="container">
            <div class="bd-block">
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
                @if (session('errors'))
                    <script>
                        toastr.error("{{ session('errors') }}");
                    </script>
                @endif
                @if (Auth::user()->user_type === 'employer')
                    @if (isset($data['validSubscription']) && $data['validSubscription'])
                        <div class="row mb-3">
                            <div class="ml-auto col-12 col-md-4">
                                <a href="{{ route('post-job') }}" type="button" class="btn btn-submit btn-md btn-block"
                                    style="background-color:#eb1829;color:white;width:100%;">
                                    <i class="fa fa-plus"></i>
                                    Post New Job
                                </a>
                            </div>
                        </div>
                    @elseif (isset($data['waitingSubscription']) && $data['waitingSubscription'])
                        <div class="row mb-3">
                            <div class="ml-auto col-12 col-md-4">
                                <a href="{{ route('welcome') }}" type="button" class="btn btn-submit btn-md btn-block"
                                    style="background-color:#eb1829;color:white;width:100%;">
                                    <i class="fa fa-ticket"></i>
                                    Go To Home
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="row mb-3">
                            <div class="ml-auto col-12 col-md-4">
                                <a href="{{ route('subscription') }}" type="button" class="btn btn-submit btn-md btn-block"
                                    style="background-color:#eb1829;color:white;width:100%;">
                                    <i class="fa fa-ticket"></i>
                                    Go To Subscription
                                </a>
                            </div>
                        </div>
                    @endif

                    @if (isset($data['validSubscription']) && $data['validSubscription'] && count(@$data['jobPost']) > 0)

                        <div class="row">
                            <div class="col-lg-5">
                                @foreach ($data['jobPost'] as $jp)
                                    <!-- item-col 1-->
                                    <div class="item-col <?php if ($jp->id == @$data['firstJobDetail']->id) {
                                        echo 'active-div';
                                    } ?>"
                                        onclick="showJobPostFromDashboard('<?php echo $jp->id; ?>')">
                                        <!--top-->
                                        <div class="top">
                                            <div class="lt" style="width:100%;">

                                                <h3 title="JOB-Edit" style="cursor:pointer;">
                                                    {{ $jp->job_title }}
                                                    <a href="{{route('edit-job', ['jobid' => $jp->id ])}}">
                                                        <i class="fa fa-edit" style="color: black; float: right;"></i>
                                                    </a>
                                                </h3>

                                                <span>{{ $jp->company_name }}</span>
                                            </div>
                                        </div>
                                        <!--top-->
                                        <!-- middle -->
                                        <div class="middle" title="INDIVIDUAL-JOB-SHOW" style="cursor:pointer;">
                                            <div class="lt">
                                                <span>{{ $jp->job_location }}</span>
                                                <p>{{ $jp->preferred_job_type }}</p>
                                            </div>
                                            <div class="rt">
                                                <div class="img-sec">
                                                    <img src="{{ $jp->company_logo }}">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- middle -->
                                        <!-- bottom -->
                                        <div class="bottom" title="INDIVIDUAL-JOB-SHOW" style="cursor:pointer;">
                                            <p>{{ $jp->salary_currency }}{{ $jp->salary_from }} -
                                                {{ $jp->salary_currency }}{{ $jp->salary_to }}
                                                {{ $jp->salary_period }}</p>
                                            <span><b>Posted
                                                    :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime($jp->created_at))->diffForHumans() }}</span>
                                        </div>
                                        <!-- bottom -->
                                    </div>
                                    <!-- item-col -->
                                @endforeach
                                {{ $data['jobPost']->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
                            </div>
                            @if (!empty($data['firstJobDetail']))
                                <div class="col-lg-7">
                                    <!---->
                                    <div class="item-tr-sec">
                                        <div class="item-col">

                                            <div class="top">
                                                <div class="figure">
                                                    <img src="{{ $data['firstJobDetail']->company_logo }}">
                                                </div>
                                                <p>{{ $data['firstJobDetail']->company_name }}</p>
                                                <h4>{{ $data['firstJobDetail']->job_title }}</h4>
                                                <?php $jobid = $data['firstJobDetail']->id; ?>
                                                <input type="button" value="Save As Draft" name=""
                                                    onclick="saveAsDraft('{{ $jobid }}')">
                                                <a href="{{ route('job-applicant', ['jobid' => $data['firstJobDetail']->id]) }}"
                                                    target="_blank"><button type="button"
                                                        class="btn btn-primary btn-sm applicants"><i
                                                            class="fa fa-users"></i></button></a>
                                            </div>

                                            <div class="text">
                                                <div class="lt">
                                                    <span>{{ $data['firstJobDetail']->salary_period }}</span>
                                                    <p>{{ $data['firstJobDetail']->salary_currency }}{{ $data['firstJobDetail']->salary_from }}
                                                        -
                                                        {{ $data['firstJobDetail']->salary_currency }}{{ $data['firstJobDetail']->salary_to }}
                                                    </p>
                                                </div>

                                                <div class="rt">
                                                    <span><b>Posted
                                                            :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime($data['firstJobDetail']->created_at))->diffForHumans() }}</span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="item-col-txt">

                                            <div class="item location-sec">
                                                <span>Job location</span>
                                                <p>{{ $data['firstJobDetail']->job_location }}</p>
                                            </div>



                                            <div class="item job-titlec">
                                                <h5>Job Title</h5>
                                                <p>{{ $data['firstJobDetail']->job_title }}</p>
                                            </div>

                                            <div class="item job-desc">
                                                <h5>Job Descriptions</h5>
                                                <p>{!! nl2br(preg_replace('/(\r\n|\n|\r)/', '<br/>', $data['firstJobDetail']->job_details)) !!}</p>
                                            </div>

                                            <div class="item job-titlec">
                                                <h5>Qualifications</h5>
                                                <?php
                                                $replace = str_replace('[', ' ', str_replace(']', ' ', $data['firstJobDetail']->qualifications));
                                                $qualification_explode = explode('|', $replace);
                                                ?>
                                                <?php for($i = 0;$i < count($qualification_explode);$i++): ?>
                                                <p>{{ str_replace('"', ' ', $qualification_explode[$i]) }}</p>
                                                <?php endfor; ?>
                                            </div>

                                            <div class="item job-desc">
                                                <h5>Skills</h5>
                                                <?php
                                                $skill_explode = [];
                                                $replace = str_replace('[', ' ', str_replace(']', ' ', $data['firstJobDetail']->job_skills));
                                                if (str_contains($replace, '|')) {
                                                    $skill_explode = explode('|', $replace);
                                                }
                                                if (str_contains($replace, ',')) {
                                                    $skill_explode = explode(',', $replace);
                                                }
                                                ?>
                                                <ul>
                                                    <?php for($i = 0;$i < count($skill_explode);$i++): ?>
                                                    <li>{{ trim(str_replace(["\n", "\r", '"'], ' ', $skill_explode[$i])) }}.
                                                    </li>
                                                    <?php endfor; ?>
                                                </ul>
                                            </div>


                                            <div class="item job-desc">
                                                <h5>Experience</h5>
                                                <?php
                                                $replace = str_replace('[', ' ', str_replace(']', ' ', $data['firstJobDetail']->experience));
                                                $experience_explode = explode('|', $replace);
                                                ?>
                                                <ul>
                                                    <?php for($i = 0;$i < count($experience_explode);$i++): ?>
                                                    <li>{{ str_replace('"', ' ', $experience_explode[$i]) }}</li>
                                                    <?php endfor; ?>
                                                </ul>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @elseif(isset($data['validSubscription']) && $data['validSubscription'])
                        <div class="alert alert-secondary" role="alert">
                            <h4 class="alert-heading">Congratulations!</h4>
                            <p>
                                You are now a subscribed member and can start posting jobs immediately.
                                Your subscription unlocks a world of functionalities. Feel free to create job listings,
                                find top talent, and grow your network.
                            </p>
                            <hr>
                            <p class="mb-0">
                                To start posting, simply click on 'Post new job' at the top of this page.
                            </p>
                        </div>
                    @elseif (isset($data['waitingSubscription']) && $data['waitingSubscription'])
                        <div class="alert alert-secondary" role="alert">
                            <h4 class="alert-heading">Welcome to your employer account!</h4>
                            <p>
                                It has come to our attention that there is a pending subscription associated with your
                                account,
                                which may either be unpaid or paid but not yet activated. To obtain more information and
                                resolve
                                this matter promptly, we kindly ask you to reach out to our dedicated support team using the
                                contact
                                details provided below.
                            </p>
                            <p>
                                Thank you for your cooperation.
                            </p>
                            <hr>
                            <div class="d-flex flex-column flex-sm-row align-items-start justify-content-around">
                                <p class="mb-0">
                                    By Phone : <br />
                                    Phone Number (1) : 01 215 0518<br />
                                    Phone Number (2) : 087 646 3175
                                </p>
                                <p class="mb-0 mt-3 mt-sm-0">
                                    By E-mail : <br />
                                    E-mail : info@recruit.ie
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-secondary" role="alert">
                            <h4 class="alert-heading">Welcome to your employer account!</h4>
                            <p>
                                Unlock the full potential of Recruit.ie by subscribing today! Join our growing
                                community of satisfied users who have already experienced the benefits of our app.
                            </p>
                            <hr>
                            <p class="mb-0">
                                To subscribe, simply click on 'Go to subscription' at the top of this page.
                            </p>
                        </div>
                    @endif

                @endif
                @if (Auth::user()->user_type == 'coach')
                    @if (!empty(@$data['coachDetail']))
                        @include('site.pages.coach.partial.coach_contact_info', [
                            'coach' => $data['coachDetail']->user,
                            'subTitle' => $data['coachDetail']->university_or_institute,
                            'address' => $data['coachDetail']->address,
                            'linkedinLink' => $data['coachDetail']->linkedin_link,
                            'facebookLink' => $data['coachDetail']->facebook_link,
                            'instagramLink' => $data['coachDetail']->instagram_link,
                            'contactLink' => $data['coachDetail']->contact_link,
                        ])

                        @if ($data['coachDetail']->skill_details != '')
                            <div class="row" style="margin-top: 2%;">
                                <div class="col-md-4">
                                    <label for="" style="font-size: 18px;font-weight: 900;"><i
                                            class="fa fa-cogs"></i> Skills</label>
                                    <?php $explode = explode(',', $data['coachDetail']->skill_details); ?>
                                    <ol class="skill-listing">
                                        @for ($i = 0; $i < count($explode); $i++)
                                            <li>{{ $explode[$i] }}</li>
                                        @endfor
                                    </ol>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 3%;">
                                <div class="col-md-12">
                                    {!! nl2br($data['coachDetail']->coach_skill) !!}
                                </div>
                            </div>
                        @endif
                        <div class="row tabrow">
                            <div class="tab">
                                <button class="tablinks active" onclick="openTab(event, 'aboutus')">About Us</button>
                                <button class="tablinks" onclick="openTab(event, 'help')">How We Help</button>
                                <button class="tablinks last" onclick="openTab(event, 'faq')">FAQ's</button>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div id="aboutus" class="tabcontent active" style="display:block;">
                                <h4>About Us</h4>
                                {!! nl2br($data['coachDetail']->about_us) !!}
                            </div>
                            <div id="help" class="tabcontent">
                                <h4>How We Help</h4>
                                {!! nl2br($data['coachDetail']->how_we_help) !!}
                            </div>
                            <div id="faq" class="tabcontent">
                                <h4>FAQ's</h4>
                                {!! nl2br($data['coachDetail']->faq) !!}
                            </div>
                        </div>
                    @else
                        <script>
                            location.href = "{{ route('profile') }}";
                        </script>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
@section('myscript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.0.4/popper.js"></script>
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';

        function showJobPostFromDashboard(jobPostId) {
            const searchParams = new URLSearchParams(window.location.search);
            if (searchParams.toString() != '') {
                location.href = "{{ route('dashboard') }}" + '/' + jobPostId + '?' + searchParams.toString();
            } else {
                location.href = "{{ route('dashboard') }}" + '/' + jobPostId
            }
        }

        function editmode(jobid) {
            location.href = "{{ route('edit-job') }}" + '/' + jobid;
        }

        function saveAsDraft(jobid) {
            if (!confirm("Do You Want To Save This Job As Draft?")) return false;
            $.ajax({
                url: APP_URL + '/change-job-status',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    jobid: jobid,
                    status: 'Save as Draft'
                },
                success: function(res) {
                    if (res.code == '200') {
                        toastr.success(res.msg);
                        setTimeout(() => {
                            location.reload();
                        }, 400);
                    };
                    if (res.code == '401') {
                        toastr.error(res.msg);
                    };
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
    </script>
    <script>
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
        }
    </script>
    <?php
	if(!empty(@$data)){
		if(!empty(@$data['from']) && @$data['from'] == 'joblist'){
			$postid = @$data['postid'];
?>
    <script>
        $("#jobapplybtn").click();
    </script>
    <?php	}
	}
?>
@endsection
