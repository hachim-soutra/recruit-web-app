<?php
header('Location: https://www.recruit.ie/careers/about-us/');
exit;
?>

@extends('site.layout.app')
@section('title', 'About Us')
@section('mystyle')
@endsection
@section('content')
<!-- banner-block -->	

<!-- post-resume-block -->
	<div class="post-resume-one p-0">
        <section class="inner-banner-div">
            <img src="{{ asset('uploads/cms/about/'.@$about->banner_file) }}" alt="">
            <div class="banner-layer1"></div>
            <div class="banner-layer2"></div>
            <div class="banner-layer3"></div>
            <div class="banner-text">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6 mr-auto text-left">
                            <h2 class="heading">{{ @$about->heading }}</h2>
                            <p class="banner-short-description">{{ @$about->banner_description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="cms-pagedesign">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="section-titel">
                            <h2>{{ @$about->heading }}</h2>
                            {!! str_replace('<br>', '', nl2br(@$about->left_detail)) !!}
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="w-100">
                            <video class="w-100" src="{{ asset('uploads/cms/about/'.@$about->aboutus_video) }}" controls="" controlslist="nodownload"></video>
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="section-titel">
                            {!! nl2br(@$about->right_detail) !!}
                        </div>
                     </div>
                </div>
            </div>
        </section>
        <section class="cms-pagedesign bg-light">
            <div class="container">
                <div class="section-titel text-center mb-4">
                    <h5>MEET THE TEAM</h5>
                    <h2>Our Awesome Team</h2>
                </div>
                <div class="row">
                    @forelse($team as $t)
                    <div class="col-md-3">
                        <div class="tem-main">
                            <div class="figure">
                                <img src="{{ $t->user->avatar }}">
                            </div>
                            <div class="text">
                                <h4>{{ $t->user->name }}</h4>
                                <p>{{ $t->designation }}</p>
                                <ul>
                                    <li><a href="{{ $t->fblink }}"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="{{ $t->twlink }}"><i class="fa fa-twitter"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div><p>No Data Found.</p></div>
                    @endforelse
                    {{-- <div class="col-md-3">
                        <div class="tem-main">
                            <div class="figure">
                                <img src="https://dev8.ivantechnology.in/recruitmentapp/uploads/team/1_6290d272e4703.png">
                            </div>
                            <div class="text">
                                <h4>Cormac O'meara</h4>
                                <p>web developer</p>
                                <ul>
                                    <li><a href="https://facebook.com/janedoe3"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="https://twitter/teamone.india"><i class="fa fa-twitter"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tem-main">
                            <div class="figure">
                                <img src="https://dev8.ivantechnology.in/recruitmentapp/uploads/team/1_628e10a814f7b.png">
                            </div>
                            <div class="text">
                                <h4>Cormac O'meara</h4>
                                <p>web developer</p>
                                <ul>
                                    <li><a href="https://facebook.com/janedoe3"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="https://twitter/teamone.india"><i class="fa fa-twitter"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tem-main">
                            <div class="figure">
                                <img src="https://dev8.ivantechnology.in/recruitmentapp/uploads/team/1_628e10f1ed032.png">
                            </div>
                            <div class="text">
                                <h4>Cormac O'meara</h4>
                                <p>web developer</p>
                                <ul>
                                    <li><a href="https://facebook.com/janedoe3"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="https://twitter/teamone.india"><i class="fa fa-twitter"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tem-main">
                            <div class="figure">
                                <img src="https://dev8.ivantechnology.in/recruitmentapp/uploads/team/1_62c6b8cb681c5.png">
                            </div>
                            <div class="text">
                                <h4>Cormac O'meara</h4>
                                <p>web developer</p>
                                <ul>
                                    <li><a href="https://facebook.com/janedoe3"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="https://twitter/teamone.india"><i class="fa fa-twitter"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </section>
        <section class="vrcul-grot findout-div" style="background: linear-gradient(90deg, #0000, #0000), url(https://dev8.ivantechnology.in/recruitmentapp/uploads/staff/1_62cfc202a4207_bottom_.jpg) center no-repeat;">
            <div class="banner-layer11"></div>
            <div class="banner-layer2"></div>
            <div class="banner-layer3"></div>
            <div class="container">
                <div class="row counterdiv" id="counter">
                    <div class="col-md-3 textcolor mb-3" id="counters_1">
                        <div class="counterbox">
                            <h2><span class="counter-value" data-count="{{@$about->glorious_year}}">{{ @$about->glorious_year }}</span>+</h2>
                            <h3>Glorious Years</h3>
                        </div>
                    </div>
                    <div class="col-md-3 textcolor mb-3">
                        <div class="counterbox">
                            <h2><span class="counter-value" data-count="{{ @$about->happy_client }}">{{ @$about->happy_client }}</span>+</h2>
                            <h3>Happy Clients</h3>
                        </div>
                    </div>
                    <div class="col-md-3 textcolor mb-3">
                        <div class="counterbox">
                            <h2><span class="counter-value" data-count="{{ @$about->talented_candidate }}">{{ @$about->talented_candidate }}</span>+</h2>
                            <h3>Talented Candidates</h3>
                        </div>
                    </div>
                    <div class="col-md-3 textcolor mb-3">
                        <div class="counterbox">
                            <h2><span class="counter-value" data-count="{{ @$about->jobs_expo }}">{{ @$about->jobs_expo }}</span>+</h2>
                            <h3>Jobs Expos</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	</div>
<!-- post-resume-block -->

@endsection
@section('myscript')
<script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
<script>
	const APP_URL = '<?= env('APP_URL')?>';
    ClassicEditor.create( document.querySelector( '#sms_body' ) );
    setTimeout(() => {
        $('.error').hide();
    }, 4000);
</script>
<script type="text/javascript">
    var a = 0;
    $(window).scroll(function () {
        if ($("#counter").length) {
            var oTop = $("#counter").offset().top - window.innerHeight;
            if (a == 0 && $(window).scrollTop() > oTop) {
                $(".counter-value").each(function () {
                    var $this = $(this),
                        countTo = $this.attr("data-count");
                    $({ countNum: $this.text() }).animate(
                        { countNum: countTo },
                        {
                            duration: 2000,
                            easing: "swing",
                            step: function () {
                                $this.text(Math.floor(this.countNum));
                            },
                            complete: function () {
                                $this.text(this.countNum);
                            },
                        }
                    );
                });
                a = 1;
            }
        }
    });
</script>
@endsection