<div class="banner-block">
    <div class="container">
        <div class="banner-bd">
            <h1>Unlock Your Career Potential</h1>
            <h4>
                Discover job opportunities tailored to your skills and aspiration.<br />
                You next career move start here.
            </h4>

            <div class="d-flex flex-row align-items-center justify-content-center">
                <a class="banner-bd-button btn btn-md py-3 mx-auto" href="{{ route('common.job-listing') }}">
                    Explore Job Listings
                    <i class="fa fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
    <div
        class="item-row-second d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between px-2 py-4 bg-white">
        <p class="mb-0 search-banner-company-title">
            <span style="color: var(--red-color)">{{ $stats['jobs'] }}</span>
            job ads |
            <span style="color: var(--red-color)">{{ $stats['companies'] }}</span>
            companies
        </p>
        <a href="{{ route('common.company-search') }}" class="banner-company-button mt-2 mt-md-0">
            See all hiring companies
        </a>
    </div>
</div>
