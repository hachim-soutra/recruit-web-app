<div class="col-md-12">
    <img src="{{ @$coach->coach->coach_banner }}"
        id="coach-banner-img" class="img" alt="coach_banner" />
</div>

<div class="col-md-12 my-3">
    <h2>{{ ucfirst($coach->name) }}</h2>
</div>
<div class="col-md-12">
    <h4 style="font-weight: 400">{{ ucfirst($subTitle) }}</h4>
</div>
<div class="col-md-12 d-flex flex-column flex-md-row gap-menu">
    @if ($coach->mobile != '')
        @if ($coach->mobile != '')
            <a class="text-dark h6 whitespace-no-wrap" href="tel:{{ $coach->mobile }}" title="mobile number"><i
                    class="fa fa-phone"></i> {{ $coach->mobile }}</a>
        @endif
        {{-- @if ($coach->coach->alternate_mobile_number != '')
            <div class="mx-3 d-none d-md-block">|</div>
            <a class="text-dark h6 whitespace-no-wrap"
                href="tel:{{ $coach->coach->alternate_mobile_number }}"
                title="alternate mobile number">
                <i class="fa fa-phone"></i> {{ $coach->coach->alternate_mobile_number }}
            </a>
        @endif --}}
    @endif
    @if ($coach->email != '')
        <div class="mx-3 d-none d-md-block">|</div>
        <div>
            <a class="text-dark h6 whitespace-no-wrap" href="mailto:{{ $coach->email }}"> <i class="fa fa-envelope"></i>
                {{ $coach->email }}</a>
        </div>
    @endif
    @if ($address)
        <div class="mx-3 d-none d-md-block">|</div>
        <div>
            <span class="text-dark whitespace-no-wrap">
                <i class="fa fa-location-arrow" aria-hidden="true"></i>
                {{ $address }}</span>
        </div>
    @endif
    @if ($linkedinLink)
        <div class="mx-3 d-none d-md-block">|</div>
        <div>
            <a href="{{ $linkedinLink }}" target="_blank" class="text-dark h6 whitespace-no-wrap">
                <i class="fa fa-linkedin-square" aria-hidden="true"></i>
                LinkedIn
        </div>
    @endif
    @if ($facebookLink)
        <div class="mx-3 d-none d-md-block">|</div>
        <div>
            <a href="{{ $facebookLink }}" target="_blank" class="text-dark h6 whitespace-no-wrap">
                <i class="fa fa-facebook-square" aria-hidden="true"></i>
                facebook
        </div>
    @endif
    @if ($instagramLink)
        <div class="mx-3 d-none d-md-block">|</div>
        <div>
            <a href="{{ $instagramLink }}" target="_blank" class="text-dark h6 whitespace-no-wrap">
                <i class="fa fa-instagram" aria-hidden="true"></i>
                instagram
        </div>
    @endif
    @if ($contactLink)
        <div class="mx-3 d-none d-md-block">|</div>
        <div>
            <a href="{{ $contactLink }}" target="_blank" class="text-dark h6 whitespace-no-wrap">
                <i class="fa fa-globe" aria-hidden="true"></i>
                Contact link</a>
        </div>
    @endif
</div>
