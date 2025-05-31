<!-- Country Modal -->
<div class="modal fade" id="countryModal" tabindex="-1" aria-labelledby="countryModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Choose your country</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <img loading="lazy" onclick="chooseCountry('world');" width="64"
                             height="auto" class="cursor-pointer mb-2 mb-md-0"
                             src="{{ asset('frontend/images/countries/world.png') }}"
                             alt="world" />
                        @if (isset($_COOKIE['country']) && $_COOKIE['country'] == 'world')
                            <img width="24" height="auto" class="cursor-pointer mb-2 mb-md-0"
                                 src="{{ asset('frontend/images/countries/check.png') }}" loading="lazy"
                                 alt="world" />
                        @endif
                    </div>
                    @foreach (['ireland', 'morocco', 'australia', 'france', 'uk'] as $country)
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <img loading="lazy" onclick="chooseCountry('{{ $country }}');" width="64"
                                height="auto" class="cursor-pointer mb-2 mb-md-0"
                                src="{{ asset('frontend/images/countries/' . $country . '.png') }}"
                                alt="{{ $country }}" />
                            @if (isset($_COOKIE['country']) && $_COOKIE['country'] == $country)
                                <img width="24" height="auto" class="cursor-pointer mb-2 mb-md-0"
                                    src="{{ asset('frontend/images/countries/check.png') }}" loading="lazy"
                                    alt="{{ $country }}" />
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Country Modal -->
