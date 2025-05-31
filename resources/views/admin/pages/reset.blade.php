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
            <a href="">Recruit.ie </a>
        </div>
         <div class="login-box-body">
            <p class="login-box-msg">Reset Password</p>
            <form class="form-horizontal m-t-20" id="form-password" action="{{ url('reset-password') }}" method="POST" novalidate>
                                @csrf
                <div class="form-group has-feedback">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ $user->email }}"  readonly />
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" />
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Repeat Password" />
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            Update Your Password
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <script src="{{ asset('backend/plugin/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/loader/jquery.loadingModal.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/toastr/toastr.min.js') }}"></script>
    @include('admin.scripts.common')
    @include('admin.scripts.login')
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $(".preloader").fadeOut();
    </script>
    <script>
        $(document).ready(function () {

            $('#form-password').on('submit', function (e) {
                e.preventDefault(e);
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        $('.form-group').removeClass('has-error');
                        $('.help-block').remove();
                        $('.text-danger').remove();
                    },
                    complete: function () {},
                    success: function (response) {
                        console.log(response);
                        if (response.data.status === "validation_error") {
                            printErrorMsg(response.data.message);
                        } else if (response.data.status === "error") {
                            toastr.success(response.data.message);

                        } else {
                            toastr.success(response.data.message);
                            window.location.replace("{{url('success')}}");

                        }
                    },
                    error: function (error) {
                        console.log(error);
                    },
                })
            });


        });
    </script>
</body>

</html>
