@extends('site.layout.app')
@section('title', 'Welcome')
@section('mystyle')
@endsection
@section('content')
    <!-- banner-block -->

    <!-- Page Loder start -->

    <div class="page-loader" style="display:none;">
        <div class="spinner"></div>
    </div>

    <!-- Page Loder End -->

    <!-- login-block register-block -->
    <div class="login-block register-block">
        <div class="container">
            <!-- =============== -->
            @if (session('success'))
                <script>
                    toastr.success("{{ session('success') }}");
                </script>
            @endif
            @if (session('errors'))
                <script>
                    toastr.error("{{ session('errors')->first() }}");
                </script>
            @endif
            <div class="bd-block">
                <h4>Complete Registration</h4>
                <span>As a <span id="usertype_span"></span></span>
                <div class="item-sec">
                    <div class="py-3 px-3 px-sm-0">
                        <!-- employer  -->
                        <!-- item-form-bd -->
                        <div id="employer-profile">
                            <div class="form-bd">
                                @include('site.pages.partial.auth.complete-register-form', [
                                    'usertype' => 'employer',
                                    'user' => $user,
                                ])
                            </div>
                        </div>
                        <!-- item-form-bd -->
                    </div>
                </div>
            </div>
            <!-- ============== -->
        </div>
    </div>
    <!-- login-block -->

@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';
        $(document).ready(function() {
            $('#usertype_span').text('Employer');
        });

        function formSubmit(element, usertype) {
            $(".page-loader").show("first");
            var ajax_url = APP_URL + '/registration-complete';
            let id = $('#' + usertype + '_id').val();
            let fullname = $('#' + usertype + '_fullname').val();
            let email = $('#' + usertype + '_email').val();
            let password = $('#' + usertype + '_password').val();
            let confpass = $('#' + usertype + '_confirmpassword').val();
            $.ajax({
                url: ajax_url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    usertype: usertype,
                    fullname: fullname,
                    email_id: email,
                    password: password,
                    confirm_password: confpass
                },
                success: function(res) {
                    if (res.code == '403') {
                        let err = res.msg;
                        if (err.hasOwnProperty('confirm_password')) {
                            for (let j = 0; j < err.confirm_password.length; j++) {
                                toastr.error(err.confirm_password[j]);
                            }
                            $('#' + usertype + '_confirmpassword').val();
                        }
                        if (err.hasOwnProperty('email_id')) {
                            for (let j = 0; j < err.email_id.length; j++) {
                                toastr.error(err.email_id[j]);
                            }
                            $('#' + usertype + '_email').val();
                        };
                        if (err.hasOwnProperty('fullname')) {
                            for (let j = 0; j < err.fullname.length; j++) {
                                toastr.error(err.fullname[j]);
                            }
                            $('#' + usertype + '_fullname').val();
                        };
                        if (err.hasOwnProperty('password')) {
                            for (let j = 0; j < err.password.length; j++) {
                                toastr.error(err.password[j]);
                            }
                            $('#' + usertype + '_password').val();
                        };
                    };
                    if (res.code == '500') {
                        toastr.warning(res.msg);
                        $('#' + usertype + '_fullname').val();
                        $('#' + usertype + '_email').val();
                        $('#' + usertype + '_password').val();
                        $('#' + usertype + '_confirmpassword').val();
                    };
                    if (res.code == '201') {
                        toastr.success(res.msg);
                        setTimeout(() => {
                            location.href = "{{ route('signin') }}";
                        }, 1000);
                    };
                    if (res.code == '200') {
                        toastr.success(res.msg);
                        setTimeout(() => {
                            const urlSearchParams = new URLSearchParams(window.location.search);
                            const myParam = urlSearchParams.get('redirect');
                            if (myParam && usertype === "candidate") {
                                location.href = myParam;
                            } else {
                                if (usertype == 'candidate') {
                                    location.href = "{{ route('common.job-listing') }}";
                                } else if (usertype == 'coach') {
                                    location.href = "{{ route('profile') }}";
                                } else {
                                    location.href = "{{ route('dashboard') }}";
                                }
                            }
                        }, 1000);
                    };
                },
                error: function(err) {
                    console.log(err);
                }
            });
            $(".page-loader").hide("slow");
        }
    </script>
@endsection
