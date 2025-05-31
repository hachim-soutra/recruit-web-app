@extends('site.layout.app')

@section('title', 'Jobs, Jobs in Ireland, Find a Job')
@section('meta_desc',
    "Find jobs in Ireland, search for a job near you in Dublin, Cork, Limerick or Galway in Ireland's
    leading jobs search - Recruit.ie")
@section('robots', 'noindex')

@section('mystyle')
    <style>
        .atext {
            margin: 0;
            padding: 16px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .atext a {
            color: #ed1c24;
            font-weight: 500;
            text-align: left;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .text {
            min-height: 0px !important;
        }

        .item-col {
            margin: 0 0 21px 0;
            padding: 15px 10px 0px 10px;
            background: #FFFFFF;
            border: 1px solid #E9E9E9;
            border-radius: 5px;
        }

        .select2-container .select2-container--default .select2-container--open {
            top: 137px !important;
            left: 786.219px !important;
        }

        .select2-container--open .select2-dropdown--below {
            width: 332.016px !important;
            margin-left: -45.65px !important;
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

        .sector-item {
            padding: 10px;
            font-family: 'Poppins', sans-serif;
            margin: 4px;
            border-radius: 3px;
            background: #d4d4d4;
            color: #292929;
        }

        @media screen and (max-width: 480px) {
            h2 {
                font-size: 1.5rem !important;
            }
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif
    <!-- banner-block -->
    @include('site.layout.banner', ['stats' => $data['banner_stats']])
    <!-- banner-block -->
    <div class="your-sector">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-4">
                    <div class="d-flex flex-column align-items-center justify-content-between ">
                        <img src="{{ asset('icons/upload_cv.svg') }}" alt="Upload CV" loading="lazy">
                        <h4 class="text-uppercase">Upload CV</h4>
                        <p class="text-center">
                            Put your CV in front of great employers
                        </p>
                        <a class="btn btn-danger text-white"
                            href="{{ Auth::user() ? route('profile') : route('signin') }}">Upload CV</a>

                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex flex-column align-items-center justify-content-between ">
                        <img src="{{ asset('icons/alert.svg') }}" alt="alert" loading="lazy">
                        <h4 class="text-uppercase">Create an alert</h4>
                        <p class="text-center">Get the jobs you want sent straight to your inbox</p>
                        <a class="btn btn-danger text-white" href="{{ route('alert') }}">Create alert</a>
                    </div>
                </div>
                <div class="col-12 col-md-4 my-5 my-md-0">
                    <div class="d-flex flex-column align-items-center justify-content-between ">
                        <img src="{{ asset('icons/career_advice.svg') }}" alt="Career advice" loading="lazy">
                        <h4 class="text-uppercase">Find Career Coach</h4>
                        <p class="text-center">
                            The latest career advice to support your job hunt
                        </p>
                        <a class="btn btn-danger text-white" href="{{ route('career-coach') }}">See what's new</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- your-sector -->

    <!-- latest-jobs-block -->
    @include('site.pages.partial.common.welcome.latest-job', ['jobs' => $data['latest_jobs']])
    <!-- latest-jobs-block -->

    <!-- our-news-section -->
    @include('site.pages.partial.common.welcome.news', ['news' => $data['news'], 'events' => $data['events'], 'advices' => $data['advices']])
    <!-- our-news-section -->

@endsection

@section('myscript')
@endsection
