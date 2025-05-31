<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/img/fav-icon.png') }}">
    <title>Recruit.ie | Wrong Email</title>
    <!-- Custom CSS -->
     <link rel="stylesheet" href="{{ asset('backend/plugin/bootstrap/dist/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/plugin/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/plugin/Ionicons/css/ionicons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/style.min.css') }}" />
    <link href="{{ asset('backend/plugin/loader/jquery.loadingModal.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugin/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/custom.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center"
            style="background:#ed1c24; height: 100vh; text-align: center; color: #fff; padding: 86px;">
            <div class="auth-box">
                <div id="loginform">
                    <div class="logo">
                        <span class="db"><img src="{{ asset('backend/img/fail-tick.png') }}" alt="logo" width="100px"/></span>
                        <h2>Please check your mail!</h2>
                        <p>Sorry your email cannot be identified.</p></br>
                        <img src="{{ asset('backend/img/job-portal-logo.png') }}" alt="logo" style="padding:10px; margin-top:20px;border: none; border-radius:6px;border-spacing: 0;border-collapse: collapse;vertical-align: top; background:#fff; ">
                    </div>
                    <!-- Form -->

                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{ asset('backend/plugin/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/loader/jquery.loadingModal.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/toastr/toastr.min.js') }}"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $(".preloader").fadeOut();
    </script>
    @include('admin.scripts.common')
    @include('admin.scripts.auth')
</body>

</html>
