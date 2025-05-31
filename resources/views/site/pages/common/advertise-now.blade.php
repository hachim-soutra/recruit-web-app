@extends('site.layout.app')
@section('title', 'Advertise your job')
@section('mystyle')
    <style>
        .advertise-container-terms {
            padding: 20px;
            background: #ff605891;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
        .advertise-container-terms a {
            margin: 0;
            font-size: 12px;
            line-height: 15px;
            color: #1A0305;
            font-weight: bold;
        }
        .advertise-container-terms a:hover {
            text-decoration: underline;
        }
        .advertise-container-year {
            padding: 20px;
            background: #FF6058;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
        .copyright {
            margin: 0;
            font-size: 12px;
            line-height: 15px;
            color: #1A0305;
            font-weight: bold;
        }
        .copyright a {
            margin: 0;
            font-size: 12px;
            line-height: 15px;
            color: #1A0305;
            font-weight: bold;
        }
        .copyright a:hover {
            text-decoration: underline;
        }
        .advertise-short-title {
            font-size: 40px;
            line-height: 48px;
            padding: 24px 12px;
            margin: 0;
            color: #fff;
            font-weight: 700;
        }
        .advertise-paragraph {
            padding: 0 12px 24px;
            margin: 0;
            font-weight: 400;
            font-size: 16px;
            color: #fff;
            line-height: 24px;
        }
        .advertise-form-container {
            margin-top: 20px;
            background-color: #757575;
            border-radius: 4px;
            -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.05);
            box-shadow: 0 0 10px 2px rgba(0,0,0,0.16);
            border: 1px solid rgba(0,0,0,0.12);
            border-top: 4px solid var(--red-color);
            padding: 12px 12px 24px;
            color: #fff;
        }
        .advertise-form-button {
            width: auto;
            padding-left: 25px;
            padding-right: 25px;
            background: var(--red-color);
            min-width: 150px;
            color: var(--light);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 14px;
        }
        .advertise-form-button:hover{
            background: #414042;
        }
    </style>
@endsection
@section('content')
    <div id="advertise-container-fill">
        @if (!empty($errors))
            @foreach ($errors->all() as $error)
                <script>
                    toastr.warning("{{ $error }}");
                </script>
            @endforeach
        @endif
        @if (session('success'))
            <script>
                //toastr.success("{{ session('success') }}");
            </script>
        @endif
        @if (session('error'))
            <script>
                toastr.error("{{ session('error')->first() }}");
            </script>
        @endif
        <div id="advertise-container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-9">
                    <div class="row">
                        <div class="col-12 col-md-7">
                            <h1 class="advertise-short-title">Advertise a Job Today</h1>
                            <p class="advertise-paragraph">
                                Hiring becomes quick and simple when you have lots of qualified candidates to choose from.
                                Recruit.ie is Ireland's favorite hiring platform. We give you more options and value every day.
                                Our team and high-tech job tools help you succeed fast. That's how we make it easy for you to find
                                reat people for your team.
                            </p>
                        </div>
                        <div class="col-12 col-md-5 advertise-form-container">
                            <form class="row mt-3 mx-0" method="POST" id="advertise-form" novalidate>
                                <div class="form-group col-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="job_type" id="radio_single" value="single">
                                        <label class="form-check-label" for="radio_single">Single job</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="job_type" id="radio_multiple" value="multiple" checked>
                                        <label class="form-check-label" for="radio_multiple">More than one job</label>
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <input type="text" class="form-control mb-0" name="company_name" placeholder="*Company name">
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control mb-0" name="first_name" placeholder="*First name">
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control mb-0" name="last_name" placeholder="*Last name">
                                </div>
                                <div class="form-group col-12">
                                    <input type="email" class="form-control mb-0 " name="email"  placeholder="*E-mail">
                                </div>
                                <div class="form-group col-12">
                                    <input type="text" class="form-control mb-0 " name="phone" placeholder="*Phone number">
                                </div>
                                <div class="form-group mb-0 col-12">
                                    <button type="button" id="btn-update" class="advertise-form-button btn w-100"
                                            onclick="postForm()">Get started</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="advertise-container-terms">
            <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
                <a href="{{ route('term_of_use') }}" class="mb-4 mb-md-0">Terms Of Use</a>
                <a href="{{ route('privacy') }}" class="mb-4 mb-md-0">Privacy Policy</a>
                <a href="https://www.recruit.ie/careers/cookies-policy/">Cookies</a>
            </div>

        </div>
        <div class="advertise-container-year d-flex flex-row align-items-center justify-content-center">
            <p class="copyright">
                © <span id="current-Year">{{ now()->year }}</span>
                <a href="https://www.recruit.ie/">Recruit.ie</a>
            </p>
        </div>
    </div>
@endsection
@section('myscript')
    <script>

        const APP_URL = '<?= env('APP_URL') ?>';

        function postForm() {

            let jobType         = $("input[name=job_type]").val();
            let companyName     = $("input[name=company_name]").val();
            let firstName       = $("input[name=first_name]").val();
            let lastName        = $("input[name=last_name]").val();
            let email           = $("input[name=email]").val();
            let phone           = $("input[name=phone]").val();
            $.ajax({
                url: APP_URL + '/post-advertise-job',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    job_type: jobType,
                    company_name: companyName,
                    first_name: firstName,
                    last_name: lastName,
                    email: email,
                    phone: phone
                },
                beforeSend: function() {
                    const button = $('.advertise-form-button');
                    button.html('<i class="fa fa-circle-o-notch fa-spin"></i> Please wait ...');
                    button.prop("disabled",true);
                },
                success: function(res) {
                    const button = $('.advertise-form-button');
                    button.html('Get started');
                    button.prop("disabled",false);
                    if (res.code === 200) {
                        toastr.success(res.msg);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                    if (res.code === 403) {
                        let err = res.msg;
                        handleErrors('job_type', err);
                        handleErrors('company_name', err);
                        handleErrors('first_name', err);
                        handleErrors('last_name', err);
                        handleErrors('email', err);
                        handleErrors('phone', err);
                    }
                    if (res.code === 500) {
                        toastr.error(res.msg);
                    }
                },
                error: function(err) {
                    const button = $('.advertise-form-button');
                    button.html('Get started');
                    button.prop("disabled",false);
                    console.log(err);
                }
            });
        }

        function handleErrors(propertyName, err) {
            if (err.hasOwnProperty(propertyName)) {
                for (let j = 0; j < err[propertyName].length; j++) {
                    toastr.error(err[propertyName][j]);
                }
            }
        }
    </script>
@endsection
