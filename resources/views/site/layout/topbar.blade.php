<header class="header-block">
    <div class="container">
        <nav class="navbar navbar-expand-lg d-flex navbar-light flex-row">
            @if (Auth::user() == null)
                <a class="d-block d-md-none navbar-brand flex-grow-logo" href="{{ route('welcome') }}">
                    <img src="{{ asset('frontend/images/logo.svg') }}" loading="lazy">
                </a>
            @else
                <a class="navbar-brand flex-grow-logo" href="{{ route('welcome') }}">
                    <img src="{{ asset('frontend/images/logo.svg') }}" loading="lazy" />
                </a>
            @endif

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('welcome') }}">Home</a>
                    </li>
                    @if (Auth::user() && Auth::user()->user_type === 'employer')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('post-job') }}">Post Jobs</a>
                        </li>
                    @endif
                    @if (Auth::user() == null || (Auth::user() && Auth::user()->user_type === 'candidate'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('common.job-listing') }}">Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('common.company-search') }}">Companies Hiring</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('career-coach') }}">Find Career Coach</a>
                        </li>
                    @endif
                    @if (Auth::user() && (Auth::user()->user_type === 'employer' || Auth::user()->user_type === 'coach'))
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.courses.ie/">Find Courses</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="https://www.recruit.ie/careers/category/career-advice/" class="dropdown-toggle"
                                type="button" data-toggle="dropdown">
                                Career Advice
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="https://www.recruit.ie/careers/category/career-advice/cv-advice/">CV Tips &
                                        Advice</a></li>
                                <li><a href="https://www.recruit.ie/careers/category/career-advice/job-hunting/">Job
                                        Hunting</a></li>
                                <li><a href="https://www.recruit.ie/careers/category/career-advice/job-interviews/">Job
                                        Interviews</a></li>
                                <li><a
                                        href="https://www.recruit.ie/careers/category/career-advice/internships/">Internships</a>
                                </li>
                                <li><a href="https://www.recruit.ie/careers/category/career-advice/changing-career/">Changing
                                        Career</a></li>
                                <li><a href="https://www.recruit.ie/careers/category/career-advice/internships/">Work-life
                                        Balance</a></li>
                            </ul>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a href="https://www.recruit.ie/careers/category/career-advice/" class="dropdown-toggle"
                           type="button" data-toggle="dropdown">
                            Our blogs
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('blogs', ['type' => 'news']) }}">Our News</a>
                            </li>
                            <li>
                                <a href="{{ route('blogs', ['type' => 'events']) }}">Our Events</a>
                            </li>
                            <li>
                                <a href="{{ route('blogs', ['type' => 'advices']) }}">Our Advice</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- right-block -->
            <div class="d-flex flex-row align-items-end">
                <ul class="lt mr-4" style="display: inline-flex;gap: 10px;">
                    <img width="20" height="auto" class="cursor-pointer" data-toggle="modal"
                        data-target="#countryModal" src="{{ asset('frontend/images/countries/world.png') }}"
                        alt="world">
                </ul>
                @if (Auth::id())
                    <ul class="lt boder-non">
                        <li class="nav-item dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" type="button" data-toggle="dropdown">
                                <img src="{{ asset('frontend/images/group-icon.svg') }}">
                            </a>
                            <ul class="dropdown-menu">
                                @if (Auth::user() && Auth::user()->user_type === 'candidate')
                                    <li><a href="{{ route('common.job-listing') }}">Dashboard</a></li>
                                @else
                                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                @endif

                                <li><a href="{{ route('profile') }}">Profile</a></li>
                                @if (Auth::user()->user_type === 'candidate')
                                    <li><a href="{{ route('education-qualification') }}">Education & Qualification</a>
                                    </li>
                                    <li><a href="{{ route('find-job') }}">Find a Job</a></li>
                                    <li><a href="{{ route('find-career-coach') }}">Find Career Coaches</a></li>
                                    <li><a href="{{ route('favourite-job') }}">Favourite Jobs</a></li>
                                    <li><a href="{{ route('events') }}">Events</a></li>
                                    <li><a href="{{ route('job-seeker.chat') }}">Chats</a></li>
                                @endif
                                @if (Auth::user()->user_type === 'employer')
                                    <li><a href="{{ route('post-job') }}">Post a job</a></li>
                                    <li><a href="{{ route('draft-job') }}">Draft jobs</a></li>
                                    <li><a href="{{ route('subscription') }}">Subscription</a></li>
                                    <li><a href="{{ route('transaction') }}">Transactions</a></li>
                                @endif
                                @if (Auth::user()->user_type !== 'employer')
                                    <li><a href="{{ route('notification') }}">Notifications</a></li>
                                @endif
                                <li><a href="{{ route('setting') }}">Settings</a></li>
                                @if (Auth::user()->user_type === 'coach')
                                    <li><a href="{{ route('career-coach.chat') }}">Chats</a></li>
                                @endif
                                <li><a href="{{ route('logout') }}">Sign out</a></li>
                            </ul>
                        </li>

                    </ul>
                @else
                    <ul class="lt" style="display: inline-flex;gap: 10px;">
                        <li><a href="{{ route('signin') }}">Sign in</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    </ul>
                @endif

                <ul class="rt">

                </ul>
            </div>
            <!-- right-block -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

        </nav>
    </div>
</header>

<div class="ad-block" align="center">
	<script>
	  window.googletag = window.googletag || {cmd: []};
	  googletag.cmd.push(function() {
		googletag.defineSlot('/23122007764/recruitIE-leaderboard-top', [[728, 90], [970, 250], [320, 50], [468, 60]], 'div-gpt-ad-1715605461674-0').addService(googletag.pubads());
		googletag.pubads().enableSingleRequest();
		googletag.pubads().collapseEmptyDivs();
		googletag.enableServices();
	  });
	</script>

	<!-- /23122007764/recruitIE-leaderboard-top -->
	<div id='div-gpt-ad-1715605461674-0' style='min-width: 320px; min-height: 50px; margin:5px auto 1px;'>
		<script>googletag.cmd.push(function() { googletag.display('div-gpt-ad-1715605461674-0'); });</script>
	</div>
</div>
