@extends('site.layout.app')
@section('title', $meta_title)
@section('meta_desc', $meta_desc)
@section('mystyle')
    <style>
        .heading {
            position: relative;
            width: 100%;
            min-height: 66px;
            padding-right: 15px;
            padding-left: 15px;
        }

        .img {
            width: 100%;
            height: auto;
            max-height: 400px;
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
        }

        .tablinks {
            border: none;
            margin-right: 80px;
            border-top: none;
        }

        .tablinks.last {
            margin-right: 0px !important;
        }

        .tabrow {
            border-bottom: 1px solid #8080801f;
            margin-top: 4%;
        }

        .tab .active {
            color: #c12128 !important;
        }

        .tabcontent {
            padding: 1rem 0;
            display: none;
        }

        .findbtn {
            width: 101%;
            color: white;
            background-color: #c12128;
            margin-left: -5px;
            margin-top: 15px;
        }

        .display-alert-none {
            transition: all 1s ease-out;
            opacity: 0;
        }

        .display-alert {
            opacity: 1;
            font-size: 1.2rem;
            margin-top: 1rem;
        }

        .gap-menu {
            gap: 10px;
        }

        .tabcontent h1 {
            font-size: 1.8rem;
        }

        .tabcontent h2 {
            font-size: 1.6rem;
        }

        .tabcontent h3 {
            font-size: 1.6rem;
        }

        .tabcontent h4 {
            font-size: 1.4rem;
        }

        .tabcontent h5 {
            font-size: 1.2rem;
        }

        .tabcontent h6 {
            font-size: 1rem;
        }

        .whitespace-no-wrap {
            white-space: nowrap;
        }

        /* Mobile css */
        @media (max-width:480px) {
            .display-alert {
                font-size: 1rem;
            }

            .tablinks {
                margin-right: 1rem;
            }
        }
    </style>
@endsection
@section('content')

    <!-- post-resume-block -->
    <div class="post-resume-one">
        <div class="container">
            <div class="bd-block">
                <div class="col-md-12">
                    <div class="row">

                        @include('site.pages.coach.partial.coach_contact_info', [
                            'subTitle' => $coach->coach->university_or_institute,
                            'address' => $coach->coach->address,
                            'linkedinLink' => $coach->coach->linkedin_link,
                            'facebookLink' => $coach->coach->facebook_link,
                            'instagramLink' => $coach->coach->instagram_link,
                            'contactLink' => $coach->coach->contact_link,
                        ])

                        <div class="col-md-12 mt-2">
                            {!! nl2br($coach->coach->coach_skill) !!}
                        </div>

                        <div class="tab col-md-12 mt-5">
                            <button class="tablinks active" onclick="openTab(event, 'aboutus')">About Us</button>
                            <button class="tablinks" onclick="openTab(event, 'help')">How We Help</button>
                            <button class="tablinks last" onclick="openTab(event, 'faq')">FAQ's</button>
                        </div>

                        <div class="col-md-12 m-0">
                            <div id="aboutus" class="tabcontent active" style="display:block;">
                                <h4>About Us</h4>
                                {!! nl2br($coach->coach->about_us) !!}
                            </div>
                            <div id="help" class="tabcontent">
                                <h4>How We Help</h4>
                                {!! nl2br($coach->coach->how_we_help) !!}
                            </div>
                            <div id="faq" class="tabcontent">
                                <h4>FAQ's</h4>
                                {!! nl2br($coach->coach->faq) !!}
                            </div>

                        </div>
                        @if (Auth::user()?->user_type === 'candidate')
                            <div class="col-md-12">
                                <a href="{{ route('job-seeker.start-chat', ['id' => $coach->id]) }}"
                                    class="btn btn-find findbtn">
                                    Start chat
                                </a>
                            </div>
                        @endif

                        @if (!Auth::user())
                            <hr>
                            <div class="col-md-12">
                                <button dis class="btn btn-find findbtn" id="chat-btn" onclick="openChatAlert()">
                                    Start chat
                                </button>
                            </div>
                            <div class="col-md-12 display-alert-none" id="chat-alert">
                                You don't have access to chat with a career coach. Please <a
                                    href="{{ route('signin', ['redirect' => url()->current()]) }}">sign
                                    in</a> or <a href="{{ route('register', ['redirect' => url()->current()]) }}">create
                                    a
                                    new
                                    account</a> to
                                proceed
                            </div>
                        @endif
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
        <script>
            $(document).ready(function() {
                const imageLink = $('#coach-banner-img').attr('src');
                checkImage(imageLink);
            });

            function checkImage(url) {
                const request = new XMLHttpRequest();
                request.open("GET", url, true);
                request.send();
                request.onload = function() {
                    status = request.status;
                    if (request.status === 200) {
                        $('#coach-banner-img').attr('src', url);
                    } else {
                        $('#coach-banner-img').attr('src', `${APP_URL}/uploads/coach_banner/no_banner.jpg`);
                    }
                }
                $('#coach-banner-img').attr('src', `${APP_URL}/uploads/coach_banner/no_banner.jpg`);
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
            }

            function openChatAlert() {
                document.getElementById("chat-alert").className += " display-alert";
                document.getElementById("chat-btn").disabled = true;
            }
        </script>
    @endsection
