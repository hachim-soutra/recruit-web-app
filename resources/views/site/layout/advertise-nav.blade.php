@if (Auth::user() == null)
    <div style="background: #e6e6e6; padding: 20px 0 20px 0;">
        <div class="container">
            <div class="row justify-content-between">
                <div class="d-none d-md-block col-2">
                    <a class="navbar-brand flex-grow-logo" href="{{ route('welcome') }}"><img
                            src="{{ asset('frontend/images/logo.svg') }}" loading="lazy"></a>
                </div>
                <div
                    class="col-12 col-md-6 d-flex flex-row align-items-center justify-content-between justify-content-md-end">
                    <span class="label employers-label mr-3">Are you recruiting?</span>
                    <a href="{{ route('common.advertise-job') }}" class="recruiter-hover-button">
                        <div class="recruiter-hover">
                            <span>Advertise now</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
