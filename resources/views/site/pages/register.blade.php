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
            <div class="bd-block">
                <h4>Register</h4>
                <span>As a <span id="usertype_span">Employer</span></span>
                <div class="item-sec">
                    <nav>
                        <div class="nav nav-tabs nav-fill btn-bd" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link " id="job-seeker-tab" onclick="changeSpan('Candidate')"
                                data-toggle="tab" href="#job-seeker" role="tab" aria-controls="job-seeker"
                                aria-selected="true">Job Seeker</a>
                            <a class="nav-item nav-link active" id="employer-tab" onclick="changeSpan('Employer')"
                                data-toggle="tab" href="#employer-profile" role="tab" aria-controls="employer-profile"
                                aria-selected="false">Employer</a>
                            <a class="nav-item nav-link" id="career-coach-tab" onclick="changeSpan('Coach')"
                                data-toggle="tab" href="#career-coach" role="tab" aria-controls="career-coach"
                                aria-selected="false">Career Coach</a>
                        </div>
                    </nav>
                    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                        <!-- job seeker  -->
                        <!-- item-form-bd -->
                        <div class="tab-pane fade " id="job-seeker" role="tabpanel" aria-labelledby="job-seeker-tab">
                            <div class="form-bd">
                                <form id="signupform">
                                    <!-- item -->
                                    <div class="item">
                                        <input type="text" placeholder="Full Name" id="candidate_fullname">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item">
                                        <input type="email" placeholder="Email Address" id="candidate_email">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item">
                                        <input type="password" placeholder="Password" id="candidate_password">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item">
                                        <input type="password" placeholder="Confirm Password"
                                            id="candidate_confirmpassword">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item login-sec">
                                        <input type="button" value="Login" data-usertype="candidate"
                                            onclick="formSubmit(this, 'candidate')">
                                    </div>
                                    <!-- item -->
                                    <div class="item-border">
                                        <p>or cffvv</p>
                                    </div>
                                    <!-- <div class=" with-linkedin">
                        <input class="btn btn-lg btn-facebook btn-block" onclick="socialLogin('candidate', 'linkedin')" type="button" value="Sign In with Linkedin">
                       </div>
                       <div class="with-google" style="margin-top:5%;">
                        <input class="btn btn-lg btn-google btn-block" onclick="socialLogin('candidate', 'google')" type="button" value="Sign In with Google">
                       </div> -->

                                    <!-- item -->
                                    <div class="register-sec">
                                        <!---->
                                        <p>Already have an account?</p>
                                        <a href="{{ route('signin') }}">Sign In</a>
                                        <!---->
                                    </div>
                                    <!-- item -->
                                </form>
                            </div>
                        </div>
                        <!-- item-form-bd -->

                        <!-- employer  -->
                        <!-- item-form-bd -->
                        <div class="tab-pane fade show active" id="employer-profile" role="tabpanel"
                            aria-labelledby="employer-tab">
                            <div class="form-bd">
                                <form id="signupform">
                                    <!-- item -->
                                    <div class="item">
                                        <input type="text" placeholder="Full Name" id="employer_fullname">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item">
                                        <input type="email" placeholder="Email Address" id="employer_email">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item">
                                        <input type="password" placeholder="Password" id="employer_password">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item">
                                        <input type="password" placeholder="Confirm Password" id="employer_confirmpassword">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item login-sec">
                                        <input type="button" value="Login" data-usertype="employer"
                                            onclick="formSubmit(this, 'employer')">
                                    </div>
                                    <!-- item -->
                                    <div class="item-border">
                                        <p>or</p>
                                    </div>


                                    <!-- <div class=" with-linkedin">
                        <input class="btn btn-lg btn-facebook btn-block" onclick="socialLogin('employer', 'linkedin')" type="button" value="Sign In with Linkedin">
                       </div>
                       <div class="with-google" style="margin-top:5%;">
                        <input class="btn btn-lg btn-google btn-block" onclick="socialLogin('employer', 'google')" type="button" value="Sign In with Google">
                       </div> -->
                                    <!-- item -->
                                    <div class="register-sec pt-3">
                                        <!---->
                                        <p>Already have an account?</p>
                                        <a href="{{ route('signin') }}">Sign In</a>
                                        <!---->
                                    </div>
                                    <!-- item -->
                                </form>
                            </div>
                        </div>
                        <!-- item-form-bd -->

                        <!-- coach  -->
                        <!-- item-form-bd -->
                        <div class="tab-pane fade" id="career-coach" role="tabpanel" aria-labelledby="career-coach-tab">
                            <div class="form-bd">
                                <form id="signupform">
                                    <!-- item -->
                                    <div class="item">
                                        <input type="text" placeholder="Full Name" id="coach_fullname">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item">
                                        <input type="email" placeholder="Email Address" id="coach_email">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item">
                                        <input type="password" placeholder="Password" id="coach_password">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item">
                                        <input type="password" placeholder="Confirm Password" id="coach_confirmpassword">
                                    </div>
                                    <!-- item -->
                                    <!-- item -->
                                    <div class="item login-sec">
                                        <input type="button" value="Login" data-usertype="coach"
                                            onclick="formSubmit(this, 'coach')">
                                    </div>
                                    <!-- item -->
                                    <div class="item-border">
                                        <p>or</p>
                                    </div>


                                    <!-- item -->
                                    <div class="register-sec">
                                        <!---->
                                        <p>Already have an account?</p>
                                        <a href="{{ route('signin') }}">Sign In</a>
                                        <!---->
                                    </div>
                                    <!-- item -->
                                </form>
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

        function changeSpan(usertype) {
            $('#usertype_span').text(usertype);
        }

        function formSubmit(element, usertype) {
            console.log(usertype);
            $(".page-loader").show("first");
            var ajax_url = APP_URL + '/registration';
            let fullname = $('#' + usertype + '_fullname').val();
            let email = $('#' + usertype + '_email').val();
            let password = $('#' + usertype + '_password').val();
            let confpass = $('#' + usertype + '_confirmpassword').val();
            $.ajax({
                url: ajax_url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
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
                        };
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
                            location.href = "{{ route('dashboard') }}";
                        }, 1000);
                    };
                },
                error: function(err) {
                    console.log(err);
                }
            });
            $(".page-loader").hide("slow");
        }

        function socialLogin(usertype, logintype) {
            if (logintype == 'linkedin') {
                location.href = "{{ route('social-login-linkedin') }}" + '/' + usertype;
            }
            if (logintype == 'google') {
                location.href = "{{ route('social-login-google') }}" + '/' + usertype;
            }
        }
    </script>
@endsection
