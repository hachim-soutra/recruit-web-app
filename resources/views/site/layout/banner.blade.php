<script>
    $(document).ready(function() {
        const brandTrack = $('.brand-track');

        $('.brand-slider').on('mouseenter', function() {
            brandTrack.css('animation-play-state', 'paused');
        }).on('mouseleave', function() {
            brandTrack.css('animation-play-state', 'running');
        });

        // Touch support for mobile
        let startX = 0;

        $('.brand-slider').on('touchstart', function(e) {
            startX = e.touches[0].clientX;
            brandTrack.css('animation-play-state', 'paused');
        });

        $('.brand-slider').on('touchend', function() {
            brandTrack.css('animation-play-state', 'running');
        });
    });
</script>

<div class="banner-block">
    <div class="container">
        <div class="banner-bd">
            <h1>Unlock Your Career Potential</h1>
            <h4>
                Discover job opportunities tailored to your skills and aspiration.<br />
                You next career move start here.
            </h4>

            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center">
                <a class="banner-bd-company-button btn btn-outline btn-md py-3" href="{{ route('common.company-search') }}">
                    Discover Companies
                    <i class="fa fa-arrow-right ml-2"></i>
                </a>
                <a class="banner-bd-button btn btn-md py-3 mt-3 mt-md-0 ml-0 ml-md-3" href="{{ route('common.job-listing') }}">
                    Explore Job Listings
                </a>
            </div>

            <div class="brand-carousel">
                <div class="carousel-title">
                    Our Trusted Partners
                </div>
                <div class="brand-slider">
                    <div class="brand-track">
                        {{-- First set of brands --}}
                        @foreach($stats['companies_list'] as $brand)
                            <div class="brand-item">
                                <a href="{{  route('common.company-detail', [ 'id' => $brand->id ]) }}">
                                    <img src="{{ asset($brand->company_logo) }}"
                                         alt="{{ $brand->user->name ?? '' }}"
                                         loading="lazy">
                                </a>
                            </div>
                        @endforeach

                        {{-- Duplicate set for seamless loop --}}
                        @foreach($stats['companies_list'] as $brand)
                            <div class="brand-item">
                                <a href="{{  route('common.company-detail', [ 'id' => $brand->id ]) }}">
                                    <img src="{{ asset($brand->company_logo) }}"
                                         alt="{{ $brand->user->name ?? '' }}"
                                         loading="lazy">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
