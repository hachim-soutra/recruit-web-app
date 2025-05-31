@extends('site.layout.app')
@section('title', 'Applied Job Detail')
@section('mystyle')
    <style>
        .row {
            display: flex;
            justify-content: center;
        }

        .rowmargin {
            margin: 0 20px 10px;
        }

        .colstyle {
            margin-top: 1%;
        }

        .button {
            width: 32%;
            border-radius: 20px;
        }

        .deleteaccount_col {
            margin-top: 30px;
            margin-left: 2%;
        }

        .deleteaccount_btn {
            background-color: white;
            color: #eb1829;
            width: 49%;
            margin-left: -24px;
            border: 1px solid;
            border-radius: 5px;
        }

        .error {
            color: #eb1829;
        }

        .btn-sm {
            float: right;
        }

        .pstatus {
            border: 1px solid;
            width: 12%;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            font-weight: 500;
            height: 100%;
        }

        .pstatus_applied_color {
            color: red;
        }

        .pstatus_approved_color {
            color: green;
        }

        .alert-dismissible {
            display: flex;
            justify-content: space-between;
            height: 50px;
        }
    </style>
@endsection
@section('content')
    <!-- banner-block -->

    <!-- post-resume-block -->
    <div class="post-resume-one">
        <div class="container">
            <div class="bd-block">
                <div class="row">
                    <div class="col-lg-12" style="text-align: center;">
                        <h4>Applied Job Details</h4>
                    </div>
                </div>
                <div class="container" style="display: flex;justify-content: center;">
                    <div class="col-lg-10">
                        <div class="item-tr-sec">
                            <!---->
                            <div class="item-col">

                                <div class="top">
                                    <div class="figure">
                                        <img src="{{ @$detail->jobs->company_logo }}">
                                    </div>
                                    <p style="margin-left:8%;">{{ $detail->jobs->company_name }}
                                        <a href="{{ URL::previous() }}"><button type="button"
                                                class="btn btn-sm btn-deafult"><i class="fa fa-arrow-left"></i>
                                                Back</button></a>
                                    </p>
                                    <h4>{{ $detail->jobs->job_title }}</h4>

                                </div>

                                <div class="text">
                                    <div class="lt">
                                        <span>{{ $detail->jobs->salary_period }}</span>
                                        <p></p>
                                        <p>{{ $detail->jobs->salary_currency }}{{ $detail->jobs->salary_from }} -
                                            {{ $detail->jobs->salary_currency }}{{ $detail->jobs->salary_to }}
                                        </p>
                                    </div>

                                    <div class="rt">
                                        <span><b>Posted
                                                :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime($detail->jobs->created_at))->diffForHumans() }}</span>
                                    </div>
                                </div>

                            </div>
                            <!---->

                            <!---->
                            <div class="item-col-txt">

                                <div class="item location-sec">
                                    <span>Job location</span>
                                    <p>{{ $detail->jobs->job_location }}</p>
                                </div>

                                <div class="alert alert-success alert-dismissible">
                                    You have applied for this job <p class="pstatus <?php if (@$detail->status == 'Applied') {
                                        echo 'pstatus_applied_color';
                                    } else {
                                        echo 'pstatus_approved_color';
                                    } ?>">
                                        {{ ucfirst(@$detail->status) }}</p>
                                </div>

                                <div class="item job-titlec">
                                    <h5>Job Title</h5>
                                    <p>{{ $detail->jobs->job_title }}</p>
                                </div>

                                <div class="item job-desc">
                                    <h5>Job Descriptions</h5>
                                    <p>{!! nl2br(preg_replace('/(\r\n|\n|\r)/', '<br/>', $detail->jobs->job_details)) !!}</p>
                                </div>

                                <div class="item job-titlec">
                                    <h5>Qualifications</h5>
                                    <?php
                                    $replace = str_replace('[', ' ', str_replace(']', ' ', $detail->jobs->qualifications));
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
                                    $replace = str_replace('[', ' ', str_replace(']', ' ', $detail->jobs->job_skills));
                                    if (str_contains($replace, '|')) {
                                        $skill_explode = explode('|', $replace);
                                    }
                                    if (str_contains($replace, ',')) {
                                        $skill_explode = explode(',', $replace);
                                    }
                                    ?>
                                    <ul>
                                        <?php for($i = 0;$i < count($skill_explode);$i++): ?>
                                        <li>{{ trim(str_replace(["\n", "\r", '"'], ' ', $skill_explode[$i])) }}.</li>
                                        <?php endfor; ?>
                                    </ul>
                                </div>


                                <div class="item job-desc">
                                    <h5>Experience</h5>
                                    <?php
                                    $replace = str_replace('[', ' ', str_replace(']', ' ', $detail->jobs->experience));
                                    $experience_explode = explode('|', $replace);
                                    ?>
                                    <ul>
                                        <?php for($i = 0;$i < count($experience_explode);$i++): ?>
                                        <li>{{ str_replace('"', ' ', $experience_explode[$i]) }}</li>
                                        <?php endfor; ?>
                                    </ul>
                                </div>



                            </div>
                            <!---->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- post-resume-block -->

@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';
    </script>
@endsection
