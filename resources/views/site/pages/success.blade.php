<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Recruit.ie | Login</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
     <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/img/fav-icon.png') }}">

    <link rel="stylesheet" href="{{ asset('backend/plugin/bootstrap/dist/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/plugin/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/plugin/Ionicons/css/ionicons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/style.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/plugin/iCheck/square/blue.css') }}" />
    <link href="{{ asset('backend/plugin/loader/jquery.loadingModal.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugin/toastr/toastr.min.css') }}" rel="stylesheet">
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" />
</head>

<body class="hold-transition login-page">
     <div class="login-box">
        <div class="login-logo">
            <img src="{{ asset('backend/img/job-portal-logo.png') }}" alt="logo"
                                                    >
        </div>
        <div class="login-box-body login-logo">
             <img src="{{ asset('backend/img/ok.png') }}" alt="logo" width="100px"/></br>
             <h2 class="login-box-msg">Password Successfully Update.</h2>
        </div>

   <script src="{{ asset('backend/plugin/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/loader/jquery.loadingModal.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/toastr/toastr.min.js') }}"></script>
</body>

</html>
