@extends('site.layout.app')
@section('title', 'Events')
@section('mystyle')
    <style>
        .img {
            border-radius: 100px;
            width: 80px;
            height: 80px;
        }

        .heading {
            position: relative;
            width: 100%;
            min-height: 66px;
            padding-right: 15px;
            padding-left: 15px;
        }

        .coach .text {
            margin: 0;
            padding: 16px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            min-height: 0px;
        }

        .coach .img {
            width: 15%;
            height: 100%;
            object-fit: cover;
            display: inline-block;
            margin: 7px 0px 0px 10px;
            border-radius: 100px;
            width: 80px;
            height: 80px;
        }

        .coach .item {
            margin: 0;
            padding: 0;
            box-shadow: 0px 5px 20px #0000001c;
            border: 1px solid #F3F3F3;
            border-radius: 6px;
        }

        .coach .text h4,
        p {
            margin-bottom: 4px;
        }

        .spantext {
            font-size: small;
            font-weight: 900;
        }
    </style>
@endsection
@section('content')

    <!-- post-resume-block -->
    <div class="<?php if (count($coach) > 0) {
        echo 'post-resume-one';
    } else {
        echo 'login-block';
    } ?>">
        <div class="container">
            <div class="bd-block">
                <div class="section-titel text-center mb-4 mb-lg-5">
                    <h4>Career Coaches</h4>
                </div>

                <div class="row">
                    @forelse($coach as $c)
                        <div class="col-lg-4 coach" style="padding: 10px;cursor: pointer;"
                            onclick="showCoachDetail('{{ $c->user_id }}')">
                            <div class="item">
                                <div class="figure box" style="display:inline-block">
                                    @if (@$c->coach_banner != '')
                                        <img class="img"
                                            src="{{ asset(str_replace('sample', 'appbackend', @$c->coach_banner)) }}">
                                    @else
                                        <img class="img"
                                            src="{{ asset('https://recruit.ie/appbackend/uploads/coach_banner/no_banner.jpg') }}">
                                    @endif
                                </div>
                                <div class="text" style="min-height: 0px;">
                                    <h4><span>Name:</span> <span>{{ @$c->name != '' ? @$c->name : '' }}</span></h4>
                                    <p><span class="spantext">University&nbsp;&nbsp;&nbsp;:</span>
                                        <span>{{ @$c->university_or_institute }}</span>
                                    </p>
                                    <p><span class="spantext">Email&nbsp;&nbsp;&nbsp;:</span>
                                        <span>{{ @$c->email != '' ? @$c->email : '' }}</span>
                                    </p>
                                    <p><span class="spantext">Mobile NO&nbsp;&nbsp;&nbsp;:</span>
                                        <span>{{ @$c->mobile != '' ? @$c->mobile : '' }}</span>
                                    </p>
                                    <p><span class="spantext">Skills&nbsp;&nbsp;&nbsp;:</span>
                                        <span>{{ @$c->skill_details != '' ? @$c->skill_details : '' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <h3 class="text-center" style="color:red;width: 100%;">No Data Found.</h3>
                    @endforelse
                </div>
                <div class="col-md-12" style="display:flex;justify-content:center;margin-top: 40px;">
                </div>
            </div>
        </div>
    </div>
    <!-- post-resume-block -->

@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';

        function showCoachDetail(coachid) {
            location.href = "{{ route('coach-detail') }}" + '/' + coachid;
        }
    </script>
@endsection
