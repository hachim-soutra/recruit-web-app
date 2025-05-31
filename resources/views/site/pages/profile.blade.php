@extends('site.layout.app')
@section('title', 'Profile')
@section('mystyle')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection
@section('content')
    <!-- banner-block -->
    <style>
        .js-example-basic-multiple {
            width: 300px !important;
        }

        .skillsubmit {
            border-radius: 25px;
            height: 32px;
            background-color: white;
            color: black;
            font-size: smaller;
        }

        .edusubmit {
            border-radius: 25px;
            height: 32px;
            background-color: white;
            color: black;
            font-size: smaller;
        }

        .langsubmit {
            border-radius: 25px;
            height: 32px;
            background-color: white;
            color: black;
            font-size: smaller;
        }

        .spantext {
            color: #eb1829 !important;
        }

        .submitformbtn {
            display: flex;
            height: 46px;
            justify-content: center;
            margin-top: 30px;
            align-content: stretch;
            align-items: center;
        }

        .uploads {
            background-size: 20%;
            background-clip: border-box;
            background-repeat: no-repeat;
        }

        .rowpadding {
            padding: 0 0 15px 0;
        }

        .rowpadding input,
        .rowpadding textarea {
            height: 45px;
            font-size: 13px;
        }

        .rowpadding textarea {
            height: 100px;
        }

        input[type=checkbox],
        input[type=radio] {
            height: auto;
            margin-right: 6px;
            position: relative;
            top: 2px;
        }

        .prf-serch {
            overflow: hidden;
            display: flex;
            justify-content: center;
            margin: 5px;
            border-bottom: 1px solid;
            width: 95%;
            margin-left: 14px;
            border-bottom: solid 1px #E8E8E8;
        }

        .prf-serch button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 12px 25px;
            transition: 0.3s;
            font-size: 14px;
            border-bottom: solid 2px transparent;
            font-weight: 600;
        }

        .prf-serch button.active {
            . color: var(--red-color);
            border-bottom: solid 2px var(--red-color);
        }

        .tabcontent {
            display: none;
            padding: 6px 12px;
            border-top: none;
        }

        .item-col {
            margin: 0 0 21px 0;
            padding: 15px 10px 0px 10px;
            background: #FFFFFF;
            border: 1px solid #E9E9E9;
            border-radius: 5px;
        }

        .top {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
        }

        .middle {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
        }

        .lt .rt {
            margin: 0;
            padding: 0;
        }

        .img-sec {
            margin: 0;
            padding: 0;
            width: 50px;
            height: 50px;
            border-radius: 50px;
            background: #114F8B19;
            overflow: hidden;
        }

        .bottom {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
        }

        .bordertext {
            border: 1px solid;
            width: 40%;
            text-align: center;
            border-radius: 10px;
        }

        .select2-container {
            width: 100% !important;
        }

        .ck-content {
            height: 200px !important;
        }

        .fa.fa-camera {
            font-size: 22px;
            color: #837f7f;
            margin-top: 45px;
            margin-left: -50px;
            border: 1px solid;
            border-radius: 13px;
            padding: 5px;
            background: #ddd;
            border: 0;
            width: 32px;
            height: 32px;
            font-size: 12px;
            line-height: 32px;
            padding: 0;
            border-radius: 50%;
            right: -23px;
        }

        .fa.fa-camera:hover {
            background: #837f7f;
            color: #fff;
        }

        textarea#company_detail {
            height: 150px;
        }

        .mail-sctm {
            border: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            padding-left: 0 !important;
            background: unset !important;
            padding-right: 5px !important;
        }

        .post-resume-block .bd-block .item-headline .ck-content p {
            height: auto;
        }
    </style>
    <script>
        // function activeEdit(){
        // 	 toastr.success("Edit Mode Active.");
        // 	$('#editsection').find(':input').each(function(){
        // 		$(this).prop('disabled', false);
        // 		$(this).prop('readonly', false);
        // 		$(this).addClass('focusBorder');
        // 	});
        // 	$('#fullname').focus();
        // 	$('.submitformbtn').show();
        // }
    </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <!-- post-resume-block -->
    <div class="post-resume-block">
        <div class="container">
            @if (!empty($errors))
                @foreach ($errors->all() as $error)
                    <script>
                        toastr.warning("{{ $error }}");
                    </script>
                @endforeach
            @endif
            @if (session('success'))
                <script>
                    //toastr.success("{{ session('success') }}");
                </script>
            @endif
            @if (session('error'))
                <script>
                    toastr.error("{{ session('error') }}");
                </script>
            @endif

            @if (Auth::user()->user_type === 'candidate')
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
                    <div class="item-headline">
                        <h4>Information</h4>
                        <p>Address: {{ @$data['coachDetail']->address }}</p>
                    </div>
                    <div class="tab prf-serch">
                        <button class="tablinks profile active btn-cvupload"
                            onclick="openTab(event, 'profile')">Profile</button>
                        <button class="tablinks jobactivity btn-cvupload" onclick="openTab(event, 'jobactivity')">Job
                            Activity</button>
                    </div>
                    <div id="profile" class="tabcontent active" style="display:block;">
                        <div class="upload-block uploads">
                            <h5>Upload your CV</h5>
                            <p>Upload DOCS / PDF up to 1 MB</p>
                            @if (@$data['candidateDetail']->resume != '')
                                <a target="_blank" href="{{ $data['candidateDetail']->resume }}">
                                    <p style="color:blue;"><img style="height: 18px;margin-top: -4px;"
                                            src="{{ asset('frontend/images/eye-outline.svg') }}">&nbsp;<strong>View
                                            CV</strong></p>
                                </a>
                            @endif
                            <form action="{{ route('profile-file-upload') }}" method="post" id="resume_upload_form"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="btn-wrapper">
                                    <button class="btn">Upload</button>
                                    <input type="file" accept="docs/*,pdf/*" name="resume_upload" id="resume_upload" />
                                    <input type="hidden" name="upload" value="resume">
                                    <input type="hidden" name="fieldname" value="resume_upload">
                                    <input type="hidden" name="oldfile" value="{{ @$data['candidateDetail']->resume }}">
                                </div>
                            </form>
                        </div>
                        <div class="upload-block uploads">
                            <form action="{{ route('profile-coverletter-update') }}" method="post" id="cover_upload_form"
                                enctype="multipart/form-data">
                                @csrf
                                <h5>Update your Cover Letter</h5><br>
                                @if (@$data['candidateDetail']->cover_letter != '')
                                    <a target="_blank" href="{{ $data['candidateDetail']->cover_letter }}">
                                        <p style="color:blue;"><img style="height: 18px;margin-top: -4px;"
                                                src="{{ asset('frontend/images/eye-outline.svg') }}">&nbsp;<strong>View
                                                Cover Letter</strong></p>
                                    </a>
                                @endif

                                <div class="btn-wrapper">
                                    <button class="btn">Upload</button>
                                    <input type="file" accept="docs/*,pdf/*" name="cover_upload" id="cover_upload" />
                                    <input type="hidden" name="upload" value="coverletter">
                                    <input type="hidden" name="fieldname" value="coverletter_upload">
                                    <input type="hidden" name="oldfile"
                                        value="{{ @$data['candidateDetail']->cover_letter }}">
                                </div>

                            </form>
                        </div>
                        <div class="work-experience">
                            <!-- work exp -->
                            <div class="item">
                                <div class="top-text">
                                    <div class="txt">
                                        <h4>Work experience</h4>
                                        <span>Share details about jobs you've worked</span>
                                    </div>
                                    <div class="btn-block experiencebtn" onclick="expbtnclick()">
                                        <a href="javascript:void(0);">Add</a>
                                    </div>
                                </div>

                                <div class="col-md-12 addsection_workexp mt-3" style="padding: 0 0 0 0;">
                                    <form action="{{ route('profile-work-experience') }}" id="workExpForm" method="post">
                                        @csrf
                                        <div class="row rowpadding">
                                            <div class="col-md-6">
                                                <input type="text" name="job_role" value="{{ old('job_role') }}"
                                                    class="form-control" placeholder="Your Job Role." id="job_role">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="company_name"
                                                    value="{{ old('company_name') }}" class="form-control"
                                                    placeholder="Previous Company Name." id="company_name">
                                            </div>
                                        </div>
                                        <div class="row rowpadding">
                                            <div class="col-md-12">
                                                <textarea name="company_address" value="{{ old('company_address') }}" id="company_address" class="form-control"
                                                    placeholder="Enter Company Address."></textarea>
                                            </div>
                                        </div>
                                        <div class="row rowpadding workingdiv">
                                            <div class="col-md-6">
                                                <label for="">Currently Working Here</label>&nbsp;&nbsp;
                                                <label class="mr-3">
                                                    <input type="radio" class="current_work_here_yes"
                                                        onclick="currentlyWorking(this.value)" name="current_work_here"
                                                        id="current_work_here_yes" value="1">Yes
                                                </label>

                                                <label class="mr-3">
                                                    <input type="radio" class="current_work_here_no"
                                                        onclick="currentlyWorking(this.value)" name="current_work_here"
                                                        id="current_work_here_no" value="0">No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row rowpadding datediv">
                                            <div class="col-md-6">
                                                <label for="">From Date</label>
                                                <input type="date" max="<?php echo date('Y-m-d'); ?>" name="form_date"
                                                    value="{{ old('form_date') }}" class="form-control" id="form_date">
                                            </div>
                                            <div class="col-md-6 todatediv">
                                                <label for="">To Date</label>
                                                <input type="date" onclick="toDateGtFromDate()"
                                                    onchange="removedDate()" name="to_date" value="{{ old('to_date') }}"
                                                    class="form-control" id="to_date">
                                            </div>
                                        </div>
                                        <div class="row rowpadding">
                                            <div class="col-md-12">
                                                <textarea name="company_detail" value="{{ old('company_detail') }}" id="company_detail" class="form-control"
                                                    placeholder="Enter Some Details About Your Previous Company.">{{ old('company_detail') }}</textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" name="exprowid" value="{{ old('exprowid') }}"
                                            id="exprowid">
                                        <div class="row rowpadding">
                                            <div class="col-md-12 ">
                                                <button type="submit" class="btn btn-red-cs"><i
                                                        class="fa fa-send-o"></i> Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="row">
                                    @foreach ($data['candidateWorks'] as $we)
                                        <div class="col-md-6">
                                            <div class="col-sec">
                                                <div class="top-sec">
                                                    <a href="javascript:void(0);"
                                                        onclick="worksedit('{{ $we->id }}')"></a>
                                                    <span onclick="worksdelete('{{ $we->id }}')"><i
                                                            class="fa fa-trash"></i></span>
                                                    <p>{{ $we->job_title }}</p>
                                                </div>
                                                <div class="b-txt">
                                                    <p>{{ $we->company }}</p>
                                                    <p>
                                                        {{ date('jS M, y', strtotime($we->from_date)) }} -
                                                        @if ($we->currently_work_here != '1')
                                                            {{ date('jS M, y', strtotime($we->end_date)) }}
                                                        @else
                                                            Currently Working
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- skills  -->
                            <div class="item">
                                <div class="top-text">
                                    <div class="txt">
                                        <h4>Skill</h4>
                                        <?php
                                        $skill_ids = $str_skills = '';
                                        for ($i = 0; $i < count(@$data['userSkill']); $i++):
                                            $str_skills .= @$data['userSkill'][$i]->skill->name;
                                            $skill_ids .= @$data['userSkill'][$i]->skill->id;
                                            if ($i < count(@$data['userSkill']) - 1):
                                                $str_skills .= ', ';
                                                $skill_ids .= ', ';
                                            endif;
                                        endfor;
                                        ?>
                                        <span class="spantext">{!! @$str_skills !!}</span>
                                        <span>Share details about jobs you've worked</span>
                                    </div>
                                    <div class="btn-block skillbtn">
                                        <a href="javascript:void(0);">Add</a>
                                    </div>
                                </div>
                                <br>
                                <div class="row addsection_skill">
                                    <div class="col-md-3">
                                        <span>Add Skills</span>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="js-example-basic-multiple skillmultiple" name="skill[]"
                                            multiple="multiple">
                                            <option disabled readonly>Choose a skill</option>
                                            @foreach ($data['skills'] as $sk)
                                                <option value="{{ $sk->id }}">{{ $sk->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" id="skillsubmit" class="btn btn-red-cs"><i
                                                class="fa fa-send-o"></i>&nbsp;&nbsp;Submit</button>
                                    </div>
                                </div>
                            </div>
                            <!-- education -->
                            <div class="item">
                                <div class="top-text">
                                    <div class="txt">
                                        <h4>Education</h4>
                                        <?php
                                        $edu_ids = $str_edus = '';
                                        for ($i = 0; $i < count(@$data['userEdu']); $i++):
                                            $str_edus .= @$data['userEdu'][$i]->qualification->name;
                                            $edu_ids .= $data['userEdu'][$i]->qualification->id;
                                            if ($i < count($data['userEdu']) - 1):
                                                $str_edus .= ', ';
                                                $edu_ids .= ', ';
                                            endif;
                                        endfor;
                                        ?>
                                        <span class="spantext">{{ $str_edus }}</span>
                                        <span>Add your licenses, degrees, and certificates.</span>
                                    </div>
                                    <div class="btn-block edubtn">
                                        <a href="javascript:void(0);">Add</a>
                                    </div>
                                </div>
                                <br>
                                <div class="row addsection_edu">
                                    <div class="col-md-3">
                                        <span>Add Education</span>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="js-example-basic-multiple edumultiple" name="education[]"
                                            multiple="multiple">
                                            <option disabled readonly>Choose education</option>
                                            @foreach ($data['qualification'] as $q)
                                                <option value="{{ $q->id }}">{{ $q->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" id="edusubmit" class="btn btn-red-cs"><i
                                                class="fa fa-send-o"></i>&nbsp;&nbsp;Submit</button>
                                    </div>
                                </div>
                            </div>
                            <!-- language -->
                            <div class="item">
                                <div class="top-text">
                                    <div class="txt">
                                        <h4>Languages</h4>
                                        <span class="spantext">{{ @$data['candidateDetail']->languages }}</span>
                                        <span>Add your Known Languages.</span>
                                    </div>
                                    <div class="btn-block langbtn">
                                        <a href="javascript:void(0);">Add</a>
                                    </div>
                                </div>
                                <br>
                                <div class="row addsection_lang">
                                    <div class="col-md-3">
                                        <span>Add Languages</span>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="js-example-basic-multiple langmultiple" name="language[]"
                                            multiple="multiple">
                                            <option disabled readonly>Choose languages</option>
                                            @foreach ($data['language'] as $q)
                                                <option value="{{ $q->name }}">{{ ucfirst($q->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" id="langsubmit" class="btn btn-red-cs"><i
                                                class="fa fa-send-o"></i>&nbsp;&nbsp;Submit</button>
                                    </div>
                                </div>
                                <!---->
                            </div>
                        </div>
                    </div>
                    <div id="jobactivity" class="tabcontent">
                        @foreach ($data['userAppliedJobs'] as $aj)
                            @if (@$aj->jobs->job_title != '')
                                <div class="row" style="justify-content: center;padding: 7px;cursor: pointer;"
                                    onclick="appliedJobDetail('{{ base64_encode($aj->id) }}')">
                                    <div class="col-lg-12" style="border-radius: 10px;">
                                        <div class="item-col">
                                            <!--top-->
                                            <div class="top" style="padding: 0 0 15px;">
                                                <div class="lt toplt"><b>{{ @$aj->jobs->job_title }}</b></div>
                                                <span>{{ @$aj->jobs->company_name }}</span>
                                            </div>
                                            <!--top-->
                                            <!-- middle -->
                                            <div class="middle">
                                                <div class="lt">
                                                    <span><i class="fa fa-map-marker"></i>
                                                        {{ @$aj->jobs->job_location }}</span>
                                                    <p>{{ @$aj->jobs->preferred_job_type }}</p>
                                                </div>
                                                <div class="rt">
                                                    <div class="img-sec">
                                                        <img src="{{ @$aj->jobs->company_logo }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- middle -->
                                            <div class="bottom">
                                                <p>{{ @$aj->jobs->salary_currency }}{{ @$aj->jobs->salary_from }} -
                                                    {{ @$aj->jobs->salary_currency }}{{ @$aj->jobs->salary_to }}
                                                    {{ @$aj->jobs->salary_period }}</p>
                                                <p><b>Applied: </b> {{ date('jS F, y', strtotime(@$aj->created_at)) }}</p>
                                                <p class="pstatus <?php if (@$aj->status == 'Applied') {
                                                    echo 'pstatus_applied_color';
                                                } else {
                                                    echo 'pstatus_approved_color';
                                                } ?>">{{ ucfirst(@$aj->status) }}</p>
                                                <span><b>Posted
                                                        :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime(@$aj->jobs->created_at))->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        {{ $data['userAppliedJobs']->appends(Request::except('page'))->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @else
                <div class="bd-block">
                    <div class="col-top">
                        <div class="figure">
                            @if (Auth::user()->avatar != '')
                                <img src="{{ asset(Auth::user()->avatar) }}">
                            @else
                                <img src="{{ asset('frontend/images/icon11.png') }}">
                            @endif
                            <div class="pro-pic-up">
                                <?php $feild_id = ''; ?>
                                @if (Auth::user()->user_type === 'employer')
                                    <?php $feild_id = 'employer'; ?>
                                @endif
                                @if (Auth::user()->user_type === 'coach')
                                    <?php $feild_id = 'coach'; ?>
                                @endif
                                <form action="{{ route('upload-file') }}" enctype="multipart/form-data"
                                    id="{{ $feild_id }}_profile_pic_upload" method="post">
                                    @csrf
                                    <label for="files" class="btn"><i class="fa fa-camera"
                                            aria-hidden="true"></i></label>
                                    <input type="file" id="files"
                                        onchange="upload('{{ $feild_id }}_profile_pic_upload')" style="display:none;"
                                        name="upload_image" id="upload_image">
                                </form>
                            </div>
                        </div>
                        <div class="text">
                            <h4>{{ Auth::user()->name }}</h4>
                            <p><a class="mail-sctm"
                                    href="mailto:{{ Auth::user()->email }}">{{ Auth::user()->email }}</a>&nbsp;|&nbsp;<i
                                    class="fa fa-user"></i> {{ ucfirst(Auth::user()->user_type) }}</p>
                            <a href="javascript:void(0);" onclick="activeEdit()">Edit</a>
                        </div>
                        <div></div>
                        @if (Auth::user()->user_type === 'employer')
                            <div class="logo">
                                <p>Company Logo</p>
                                <img src="{{ @$data['employeeDetail']->company_logo }}" alt="">
                            </div>
                        @endif
                        @if (Auth::user()->user_type === 'coach')
                            <div class="logo">
                                <p>Banner Image</p>
                                <img src="{{ $data['coachDetail']->coach_banner }}"
                                    id="dash-coach-banner-img-update" class="img" alt="" />
                            </div>
                        @endif
                    </div>
                    <!-- error show  -->
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
                    <!-- error end  -->
                    <!-- item -->
                    <div class="item-headline">
                        <h4>Information</h4>
                        @if (Auth::user()->user_type === 'employer')
                            <p>Address: {{ @$data['employeeDetail']->address }}</p>
                        @endif
                        @if (Auth::user()->user_type === 'coach')
                            <p>Address: {{ @$data['coachDetail']->address }}</p>
                        @endif
                        <form id="editsection" action="{{ route('profile-update') }}" method="POST"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Gender</label>
                                    <select name="gender" class="form-control gender" id="gender">
                                        <option selected disabled readonly>Choose gender</option>
                                        <option value="Male"
                                            <?= @$data['coachDetail']->gender == 'Male' ? 'selected' : '' ?>>Male
                                        </option>
                                        <option value="Female"
                                            <?= @$data['coachDetail']->gender == 'Female' ? 'selected' : '' ?>>Female
                                        </option>
                                        <option value="Prefer to not say"
                                            <?= @$data['coachDetail']->gender == 'Prefer to not say' ? 'selected' : '' ?>>
                                            Prefer to not say</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Name *</label>
                                    <input type="text" required value="{{ Auth::user()->name }}"
                                        placeholder="Enter Your Full Name" id="fullname" name="fullname">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Mobile Number *</label>
                                    <input type="text" required value="{{ Auth::user()->mobile }}" class="numberonly"
                                        placeholder="Enter Your Mobile Number" id="mobileno" name="mobileno">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 emaildiv">
                                    <label for="">Email *</label>
                                    <input type="text" readonly style="background: #9e9e9e40;"
                                        value="{{ Auth::user()->email }}" placeholder="Enter Your Email-ID"
                                        id="emailid" name="emailid">
                                </div>
                            </div>

                            @if (Auth::user()->user_type === 'employer')
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Established In</label>
                                        <input type="date" name="established_in"
                                            max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                            value="{{ @$data['employeeDetail']->established_in != '' ? @$data['employeeDetail']->established_in : old('established_in') }}"
                                            id="established_in" Placeholder="E.g., ESTD 1990" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Company Logo</label>
                                        <input type="file" name="company_logo" id="company_logo"
                                            class="form-control">
                                        <input type="hidden" name="company_logo_old"
                                            value="{{ basename(@$data['employeeDetail']->company_logo) }}">
                                    </div>
                                </div>
                                <!-- address  -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Country</label>
                                        <select name="country" id="country" onchange="chooseStateFromCountry(this)"
                                            class="form-control countrymultiple_coach">
                                            <option disabled selected readonly>Choose Country</option>
                                            @foreach ($data['country'] as $c)
                                                <option value="{{ $c->name }}"
                                                    data-country_id="{{ $c->id }}" <?php if ($c->name == @$data['employeeDetail']->country) {
                                                        echo 'selected';
                                                    } ?>>
                                                    {{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 statediv">
                                        <label for="">State</label>
                                        <span style="color:#c12128" class="statevaluesget"
                                            id="statevaluesget"><small>Please Select Country First.</small></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">City</label>
                                        <input type="text" name="city" id="city"
                                            value="{{ @$data['employeeDetail']->city != '' ? @$data['employeeDetail']->city : old('city') }}"
                                            class="form-control" placeholder="Enter Your City.">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">EIR /Zip Code</label>
                                        <input type="text" name="zipcode" id="zipcode"
                                            value="{{ @$data['employeeDetail']->zip != '' ? @$data['employeeDetail']->zip : old('zipcode') }}"
                                            placeholder="EIR /Zip Code" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Company Name <span>*</span></label>
                                        <input type="text" required name="company_name"
                                            value="{{ @$data['employeeDetail']->company_name }}" id="company_name"
                                            class="form-control" placeholder="Enter Company Name">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Number Of Employee</label>
                                        <input type="text" name="number_of_employees"
                                            value="{{ @$data['employeeDetail']->number_of_employees }}"
                                            id="number_of_employees" class="form-control numberonly"
                                            placeholder="Employees Number In Organisation">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Website <span>*</span></label>
                                        <input type="text" required name="website_link"
                                            value="{{ @$data['employeeDetail']->website_link }}" id="website_link"
                                            class="form-control" placeholder="Company Website.">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">LinkedIn</label>
                                        <input type="text" name="linkedin_link"
                                            value="{{ @$data['employeeDetail']->linkedin_link }}" id="linkedin_link"
                                            class="form-control" placeholder="LinkedIn Link.">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Tagline</label>
                                        <input type="text" name="tag_line"
                                            value="{{ @$data['employeeDetail']->tag_line }}" id="tag_line"
                                            class="form-control" placeholder="Enter A Tag Line.">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="company_details">Company Description <span>*</span></label>
                                        <textarea required name="company_details" id="company_details" class="form-control"
                                            placeholder="Enter Company Details.">{{ @$data['employeeDetail']->company_details }}</textarea>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 25px;">
                                    <div class="col-md-6">
                                        <label for="">CEO</label>
                                        <input type="text" name="company_ceo" id="company_ceo"
                                            value="{{ @$data['employeeDetail']->company_ceo }}" class="form-control"
                                            placeholder="Enter Company CEO.">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Phone (Along With Country Code)</label>
                                        <input type="text" name="phone_number" id="phone_number"
                                            value="{{ @$data['employeeDetail']->phone_number }}"
                                            class="form-control numberonly" placeholder="Enter Phone Number.">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Address <span>*</span></label>
                                        <input type="text" required name="address"
                                            value="{{ @$data['employeeDetail']->address }}" id="address"
                                            placeholder="Enter your full address." class="form-control">
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->user_type === 'coach')

                                <!-- exp month/ yr/ hq  -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Company Name</label>
                                        <input type="text" name="university_or_institute"
                                            value="{{ @$data['coachDetail']->university_or_institute != '' ? @$data['coachDetail']->university_or_institute : old('university_or_institute') }}"
                                            id="university_or_institute" placeholder="Enter institute name"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Experience</label>
                                        <input type="number" class="form-control" oninput="format(this)"
                                            value="{{ @$data['coachDetail']->total_experience_month != '' ? @$data['coachDetail']->total_experience_month : old('total_experience_month') }}"
                                            placeholder="Experience in Month" id="total_experience_month"
                                            name="total_experience_month">
                                    </div>

                                </div>
                                <!-- address  -->
                                <div class="row" style="margin-top: 25px;">
                                    <div class="col-md-6">
                                        <label for="">Country</label>
                                        <select name="country" id="country" onchange="chooseStateFromCountry(this)"
                                            class="form-control countrymultiple_coach">
                                            <option disabled selected readonly>Choose Country</option>
                                            @foreach ($data['country'] as $c)
                                                <option value="{{ $c->name }}"
                                                    data-country_id="{{ $c->id }}" <?php if ($c->name == @$data['coachDetail']->country) {
                                                        echo 'selected';
                                                    } ?>>
                                                    {{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 statediv">
                                        <label for="">State</label>
                                        <span style="color:#c12128" class="statevaluesget"
                                            id="statevaluesget"><small>Please Select Country First.</small></span>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 25px;">
                                    <div class="col-md-6">
                                        <label for="">City</label>
                                        <input type="text" name="city" id="city"
                                            value="{{ @$data['coachDetail']->city != '' ? @$data['coachDetail']->city : old('city') }}"
                                            class="form-control" placeholder="Enter Your City.">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">EIR /Zip Code</label>
                                        <input type="text" name="zipcode" id="zipcode"
                                            value="{{ @$data['coachDetail']->zip != '' ? @$data['coachDetail']->zip : old('zipcode') }}"
                                            placeholder="EIR /Zip Code" class="form-control">
                                    </div>
                                </div>
                                <!-- address/ specialiazion  -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Full Address</label>
                                        <input type="text" name="address"
                                            value="{{ @$data['coachDetail']->address != '' ? @$data['coachDetail']->address : old('address') }}"
                                            id="address" placeholder="Enter your full address" class="form-control">
                                    </div>

                                </div>

                                <!-- skill / about us -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Banner</label>
                                        <input type="file" accept="image/*" name="coach_banner" id="coach_banner"
                                            class="form-control">
                                        <input type="hidden" name="coach_banner_old" id="coach_banner_old"
                                            value="{{ basename(@$data['coachDetail']->coach_banner) }}">
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 25px;">
                                    <div class="col-md-6">
                                        <label for="">LinkedIn</label>
                                        <input type="text" name="coach_linkedin_link"
                                            value="{{ @$data['coachDetail']->linkedin_link }}" id="linkedin_link"
                                            class="form-control" placeholder="LinkedIn Link.">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Facebook</label>
                                        <input type="text" name="coach_facebook_link"
                                            value="{{ @$data['coachDetail']->facebook_link }}" id="facebook_link"
                                            class="form-control" placeholder="facebook Link.">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Instagram</label>
                                        <input type="text" name="coach_instagram_link"
                                            value="{{ @$data['coachDetail']->instagram_link }}" id="instagram_link"
                                            class="form-control" placeholder="instagram Link.">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">contact_link</label>
                                        <input type="text" name="coach_contact_link"
                                            value="{{ @$data['coachDetail']->contact_link }}" id="contact_link"
                                            class="form-control" placeholder="Contact Link.">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <label for="">About Us</label>
                                        <textarea name="about_us" value="{{ @$data['coachDetail']->about_us }}" id="about_us"
                                            placeholder="Tell us about youself" class="form-control">{{ @$data['coachDetail']->about_us }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">How We Help</label>
                                        <textarea name="how_we_help" value="{{ @$data['coachDetail']->how_we_help }}" id="how_we_help"
                                            placeholder="How We Can Help?" class="form-control">{{ @$data['coachDetail']->how_we_help }}</textarea>
                                    </div>
                                    <div class="col-md-12 my-3">
                                        <label for="">FAQ</label>
                                        <textarea name="faq" value="{{ @$data['coachDetail']->faq }}" id="faq" placeholder="FAQ's"
                                            class="form-control">{{ @$data['coachDetail']->faq }}</textarea>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-red-cs submitformbtn"
                                        style="background-color:#eb1829;color:white;width:100%"><i
                                            class="fa fa-edit"></i>&nbsp;Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- item -->
                </div>
            @endif
        </div>
    </div>
    <!-- post-resume-block -->
@endsection
@section('myscript')
    <script>
        var trigger = "";

        const APP_URL = '<?= env('APP_URL') ?>';

        trigger = "{{ session()->get('trigger') }}";
        if (trigger != '') {
            $('html, body').animate({
                scrollTop: $('.upload-block').offset().top
            }, 'slow');
            $('.upload-block').addClass('focusBorder');
            toastr.info("Upload Your Resume.");
            setTimeout(() => {
                $('.upload-block').removeClass('focusBorder');
            }, 4000);
        }
        $(document).ready(function() {
            let pageno = '<?= @$_GET['page'] ?>';
            if (pageno != '') {
                $('.jobactivity').trigger('onclick');
            }
            $('.statevaluesget').show();
        });
        $('#resume_upload').on('change', function() {
            $('#resume_upload_form').submit();
        });
        $('#cover_upload').on('change', function() {
            $('#cover_upload_form').submit();
        });
        $("#files").change(function() {
            filename = this.files[0].name;
        });

        function upload(feild_id) {
            $('form#' + feild_id).submit();
        }

        function chooseStateFromCountry(element) {
            let country = $(element).val();
            let country_code = element.options[element.selectedIndex].dataset.country_id;
            $('.statevaluesget').show();
            $.get("{{ route('get-state-from-country') }}" + '/' + country_code, function(data) {
                if (data.code == 200) {
                    let countries = data.data;
                    let htmloption = '<select name="state" id="state" class="form-control statemultiple_coach">';
                    htmloption += '<option disabled selected readonly>Choose State</option>';
                    for (let i = 0; i < countries.length; i++) {
                        let state = countries[i].name.replace(/"|'/g, '');
                        htmloption += '<option value="' + state + '">' + state + '</option>';
                    }
                    htmloption += '</select>';
                    $('.statediv').find('select').remove();
                    $('.statediv').append(htmloption);
                    $('.statevaluesget').hide();
                    $('.statemultiple_coach').select2();
                    let usertype = "{{ Auth::user()->user_type }}";
                    if (usertype === 'employer') {
                        let emp_state = "{{ @$data['employeeDetail']->state }}";
                        $(".statemultiple_coach").select2().val(emp_state).trigger("change");
                        $('.statemultiple_coach option[value=' + emp_state + ']').prop('selected', true);
                    } else {
                        let coach_state = "{{ @$data['coachDetail']->state }}";
                        $(".statemultiple_coach").select2().val(coach_state).trigger("change");
                        $('.statemultiple_coach option[value=' + coach_state + ']').prop('selected', true);
                    }
                }
            });
        }
        let emp_country = "{{ @$data['employeeDetail']->country }}";
        if (emp_country != '') {
            $('#country').trigger('change');
            let emp_state = "{{ @$data['employeeDetail']->state }}";
            $(".statemultiple_coach").select2().val(emp_state).trigger("change");
            $('.statemultiple_coach option[value=' + emp_state + ']').prop('selected', true);
        }
        let coach_country = "{{ @$data['coachDetail']->country }}";
        if (coach_country != '') {
            $('#country').trigger('change');
            let coach_state = "{{ @$data['coachDetail']->state }}";
            $(".statemultiple_coach").select2().val(coach_state).trigger("change");
            $('.statemultiple_coach option[value=' + coach_state + ']').prop('selected', true);
        }
        $('.numberonly').keypress(function(e) {
            var charCode = (e.which) ? e.which : event.keyCode
            if (String.fromCharCode(charCode).match(/[^0-9]/g))
                return false;
        });
        //zip code validation
        $('#zipcode').focusout(function() {
            var zip = $('#zipcode').val();
            if ((zip.length) > 9) {
                toastr.error("EIR/ Zip Code should be 9 digits maximum.");
                $('#zipcode').focus();
                return false;
            } else {
                return false;
            }
        });

        $(document).ready(function() {
            let date18 = ("0" + new Date().getDate()).slice(-2);
            let month18 = ("0" + (new Date().getMonth() + 1)).slice(-2)
            let year18 = (new Date().getFullYear() - 18);

            let dob = year18 + '-' + month18 + '-' + date18;
            $('#coach_dob').attr('max', dob);
        });
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
    <?php session()->put('trigger', ''); ?>
    @if (Auth::user()->user_type === 'candidate')
        <script>
            const APP_URL = '<?= env('APP_URL') ?>';
            let err = 0;
            err = "{{ $errors->first('err') }}";
            if (err == '1') {
                $('html, body').animate({
                    scrollTop: $('.work-experience').offset().top
                }, 'slow');
                setTimeout(() => {
                    expbtnclick();
                }, 1000);
            }
            $(document).ready(function() {
                plusExpcollapse();
                skillselect2();
                eduselect2();
                langselect2();
            });

            function plusExpcollapse() {
                $('.addsection_workexp').hide();
                $('.experiencebtn').html('<a href="javascript:void(0);">Add</a>');
                $('.experiencebtn').removeAttr('onclick', 'plusExpcollapse()');
                $('.experiencebtn').attr('onclick', 'expbtnclick()');
                $('.col-sec').show();
                expFormReset();
                $('.datediv').find('.todatediv').show();
                resetForm();
            }

            function expbtnclick() {
                $('.addsection_workexp').show();
                $('.experiencebtn').html('<a href="javascript:void(0);">collapse</a>');
                $('.experiencebtn').removeAttr('onclick', 'expbtnclick()');
                $('.experiencebtn').attr('onclick', 'plusExpcollapse()');
            }

            function skillselect2() {
                $('.skillmultiple').select2({
                    placeholder: "Select your skills."
                });
                $('.addsection_skill').hide();
                $('.skillbtn').html('<a href="javascript:void(0);">Add</a>');
                $('.skillbtn').removeAttr('onclick', 'plusSkillcollapse()');
                $('.skillbtn').on('click', function() {
                    $('.addsection_skill').show();
                    $(this).html('<a href="javascript:void(0);">collapse</a>');
                    $(this).attr('onclick', 'plusSkillcollapse()');
                    setSkillsValue();
                });
            }

            function eduselect2() {
                $('.edumultiple').select2({
                    placeholder: "Select your Education."
                });
                $('.addsection_edu').hide();
                $('.edubtn').html('<a href="javascript:void(0);">Add</a>');
                $('.edubtn').removeAttr('onclick', 'plusEducollapse()');
                $('.edubtn').on('click', function() {
                    $('.addsection_edu').show();
                    $(this).html('<a href="javascript:void(0);">collapse</a>');
                    $(this).attr('onclick', 'plusEducollapse()');
                    setEdusValue();
                });
            }

            function langselect2() {
                $('.langmultiple').select2({
                    placeholder: "Select your Language."
                });
                $('.addsection_lang').hide();
                $('.langbtn').html('<a href="javascript:void(0);">Add</a>');
                $('.langbtn').removeAttr('onclick', 'plusLangcollapse()');
                $('.langbtn').on('click', function() {
                    $('.addsection_lang').show();
                    $(this).html('<a href="javascript:void(0);">collapse</a>');
                    $(this).attr('onclick', 'plusLangcollapse()');
                    setLangsValue();
                });
            }

            function plusSkillcollapse() {
                $('.addsection_skill').hide();
                $('.skillmultiple').val('').trigger('change.select2');
                $('.skillbtn').removeAttr('onclick', 'plusSkillcollapse()');
                $('.skillbtn').html('<a href="javascript:void(0);">Add</a>');
            }

            function plusEducollapse() {
                $('.addsection_edu').hide();
                $('.edumultiple').val('').trigger('change.select2');
                $('.edubtn').removeAttr('onclick', 'plusEducollapse()');
                $('.edubtn').html('<a href="javascript:void(0);">Add</a>');
            }

            function plusLangcollapse() {
                $('.addsection_lang').hide();
                $('.langmultiple').val('').trigger('change.select2');
                $('.langbtn').removeAttr('onclick', 'plusLangcollapse()');
                $('.langbtn').html('<a href="javascript:void(0);">Add</a>');
            }
            $('#skillsubmit').on('click', function() {
                var skills = [];
                $('select[name="skill[]"] option:selected').each(function() {
                    skills.push($(this).val());
                });
                let ajax_url = APP_URL + '/profile-common-ajax';
                let data = {
                    _token: '{{ csrf_token() }}',
                    skills: skills,
                    queryfor: 'skill',
                    userid: "{{ Auth::id() }}"
                };
                let response = commomAjax(ajax_url, data, 'Skills Added.');
                if (response) {
                    skills = [];
                }
            });
            $('#edusubmit').on('click', function() {
                var edus = [];
                $('select[name="education[]"] option:selected').each(function() {
                    edus.push($(this).val());
                });
                let ajax_url = APP_URL + '/profile-common-ajax';
                let data = {
                    _token: '{{ csrf_token() }}',
                    edus: edus,
                    queryfor: 'education',
                    userid: "{{ Auth::id() }}"
                };
                let response = commomAjax(ajax_url, data, 'Education Qualification Added.');
                if (response) {
                    edus = [];
                }
            });
            $('#langsubmit').on('click', function() {
                var langs = [];
                $('select[name="language[]"] option:selected').each(function() {
                    langs.push($(this).val());
                });
                let ajax_url = APP_URL + '/profile-common-ajax';
                let data = {
                    _token: '{{ csrf_token() }}',
                    langs: langs,
                    queryfor: 'language',
                    userid: "{{ Auth::id() }}"
                };
                let response = commomAjax(ajax_url, data, 'Languages Added.');
                if (response) {
                    langs = [];
                }
            });

            function setSkillsValue() {
                let skillsid = '[{{ @$skill_ids }}]';
                $(".skillmultiple").select2().val(eval(skillsid)).trigger('change.select2');
            }

            function setEdusValue() {
                let edusid = '[{{ @$edu_ids }}]';
                $(".edumultiple").select2().val(eval(edusid)).trigger('change.select2');
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
                console.log(html);
                $(".langmultiple").select2().val(eval(html)).trigger('change.select2');
            }
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
                setTimeout(() => {
                    location.reload();
                }, 1300);
                return ajaxresponse;
            }
            /** work exp edit */
            function worksedit(expid) {
                $.ajax({
                    url: APP_URL + '/work-experience-edit',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        experienceid: expid
                    },
                    success: function(res) {
                        if (res.code == '200') {
                            $('.col-sec').hide();
                            $('#job_role').val(res.data.job_title);
                            $('#company_name').val(res.data.company);
                            $('#company_address').val(res.data.address);
                            $("input[name=current_work_here][value=" + res.data.currently_work_here + "]").prop(
                                'checked', 'checked');
                            if (res.data.currently_work_here == '1') {
                                $('.datediv').find('.todatediv').hide();
                            }
                            $('#form_date').val(res.data.from_date);
                            $('#to_date').val(res.data.end_date);
                            $('#company_detail').val(res.data.details);
                            $('#exprowid').val(res.data.id);
                            $('.experiencebtn').trigger('click');
                        }
                        if (res.code == '500') {
                            toastr.err(res.error);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            }

            function expFormReset() {
                $('#job_role').val('');
                $('#company_name').val('');
                $('#company_address').val('');
                $('#form_date').val('');
                $('#to_date').val('');
                $('#company_detail').val('');
                $('#exprowid').val('');
            }

            function currentlyWorking(workingvalue) {
                if (workingvalue == '1') {
                    $('.datediv').find('.todatediv').hide();
                } else {
                    $('.datediv').find('.todatediv').show();
                }
            }

            function resetForm() {
                $('#job_role').val('');
                $('#company_name').val('');
                $('#company_address').val('');
                $('#current_work_here_yes').prop('checked', false);
                $('#current_work_here_no').prop('checked', false);
                $('#form_date').val('');
                $('#to_date').val('');
                $('#company_detail').val('');
            }

            function worksdelete(workexpid) {
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover data!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: APP_URL + '/work-experience-delete',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    experienceid: workexpid
                                },
                                success: function(res) {
                                    if (res.code == '200') {
                                        toastr.success("Work Experience Deleted.");
                                        setTimeout(() => {
                                            location.reload();
                                        }, 1500);
                                    }
                                    if (res.code == '500') {
                                        toastr.err(res.error);
                                    }
                                },
                                error: function(err) {
                                    console.log(err);
                                }
                            });
                        } else {
                            swal("Your data is saved!");
                        }
                    });
            }

            function toDateGtFromDate() {
                let fromdate = new Date($('#form_date').val());
                let frmdate = $('#form_date').val();
                if (!frmdate || frmdate == undefined || frmdate == null) {
                    toastr.error("Form Date Required.");
                    $('#to_date').val('');
                    return false;
                }
                fromdate.setDate(fromdate.getDate() + 1);
                $('#to_date').attr('min', fromdate.toInputFormat());
                return true;
            }

            function removedDate() {
                let fromdate = $('#form_date').val();
                if (!fromdate || fromdate == undefined || fromdate == null) {
                    $('#to_date').val('');
                    return false;
                }
            }
            Date.prototype.toInputFormat = function() {
                var yyyy = this.getFullYear().toString();
                var mm = (this.getMonth() + 1).toString(); // getMonth() is zero-based
                var dd = this.getDate().toString();
                return yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]); // padding
            };
        </script>
        <script>
            function appliedJobDetail(appliedJobId) {
                location.href = "{{ route('applied-job-detail') }}" + '/' + appliedJobId;
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
    @else
        <script>
            $(document).ready(function() {
                $('#editsection').find(':input').each(function() {
                    $(this).removeClass('focusBorder');
                });
                skillselect2();
                setSkillsValue();
                select2();
            });
            setTimeout(() => {
                $('#editsection').find(':input').each(function() {
                    $(this).removeClass('focusBorder');
                });
            }, 4000);

            function skillselect2() {
                $('.skillmultiple_coach').select2({
                    placeholder: "Select your skills."
                });
            }

            function select2() {
                $('.countrymultiple_coach').select2();
                $('.citymultiple_coach').select2();
                $('.coach_type').select2();
                $('.preferred_job_type').select2();
                $('.gender').select2();
                $('.marital_status').select2();
            }

            function setSkillsValue() {
                let skills = '<?php echo json_encode(explode(',', @$data['coachDetail']->skill_details)); ?>';
                $(".skillmultiple_coach").select2().val(eval(skills)).trigger('change.select2');
            }
            ClassicEditor.create(document.querySelector('#company_details'));
        </script>
        <script>
            ClassicEditor.create(document.querySelector('#about_us'), {
                width: ['250px'],
                ckfinder: {
                    uploadUrl: '{{ route('ckeditor.upload') . '?_token=' . csrf_token() }}',
                },
                removePlugins: ['MediaEmbed'],
            });
            ClassicEditor.create(document.querySelector('#faq'), {
                width: ['250px'],
                ckfinder: {
                    uploadUrl: '{{ route('ckeditor.upload') . '?_token=' . csrf_token() }}',
                },
                removePlugins: ['MediaEmbed'],
            });
            ClassicEditor.create(document.querySelector('#coach_skill'), {
                width: ['250px'],
                ckfinder: {
                    uploadUrl: '{{ route('ckeditor.upload') . '?_token=' . csrf_token() }}',
                },
                removePlugins: ['MediaEmbed'],
            });
            ClassicEditor.create(document.querySelector('#how_we_help'), {
                width: ['250px'],
                ckfinder: {
                    uploadUrl: '{{ route('ckeditor.upload') . '?_token=' . csrf_token() }}',
                },
                removePlugins: ['MediaEmbed'],
            });
        </script>
        <script>
            function format(input) {
                if (input.value < 0) input.value = Math.abs(input.value);
                if (input.value.length > 2) input.value = input.value.slice(0, 2);
                $(input).blur(function() {});
            }
        </script>
    @endif
@endsection
