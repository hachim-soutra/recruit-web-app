<?php
$settings_banner_file = $homePageContent->banner_file_get;
$settings_banner_heading = $homePageContent->banner_heading;
?>
<script>
    const bannerfile = '{{ $settings_banner_file }}';
    const bannerheading = 'Find Your Perfect Job';
    $(document).ready(function() {
        if (bannerfile != '') {
            $('.banner-block').css('background', 'url(' + bannerfile + ') no-repeat center center');
        }
        $('.banner-block').find('#banner-heading').html(bannerheading);
    });
</script>
<div class="banner-block">
    <div class="container">
        <div class="banner-bd">
            <h1 id="banner-heading">Find your right job</h1>

            <form action="{{ route('common.job-listing') }}" id="jobsearchform" method="GET">
                <div class="item-row" id="filter-section">
                    <div class="lt" style="flex-direction: column; gap: 10px;">
                        <div class="d-flex flex-warp">
                            <div class="item-col w-100 col">
                                <input type="search" placeholder="keyword" value="{{ old('keyword') }}" name="keyword"
                                    id="keyword" class="form-control" />
                            </div>
                            {{-- hs: 2023/12/21 old location with group by --}}
                            {{-- <div class="item-col locations h-53 col">
                                    <select class="js-example-basic-multiple js-states form-control select-location mr-5"
                                        name="locations[]" multiple id="locations_select">
                                        @foreach ($locations as $k => $emp)
                                            <option {{ old('locations') && in_array($k, old('locations')) ? 'selected' : '' }}
                                                value="{{ $k }}">
                                                {{ $k }} ({{ count($emp) }})</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            {{-- hs:2023/12/21 -> old sector with group by --}}
                            {{-- <div class="item-col sector h-53 col">
                                <select class="js-example-basic-multiple js-states form-control select-sector mr-5"
                                    name="sectors[]" multiple id="sectors_select">
                                    <option></option>
                                    @foreach ($data['sectors'] as $k => $emp)
                                        <option {{ old('sectors') && in_array($k, old('sectors')) ? 'selected' : '' }}
                                            value="{{ $k }}">
                                            {{ $k }} ({{ count($emp) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="item-col">
                                <input type="text" placeholder="Location" value="{{ old('job_location') }}"
                                    name="job_location" id="job_location" class="form-control" />
                                <input type="hidden" id="start_latitude" name="start_latitude" value="" />
                                <input type="hidden" id="start_longitude" name="start_longitude" value="" />
                            </div>

                            <div class="item-col sector h-53 col">
                                <select class="js-example-basic-multiple js-states form-control select-sector mr-5"
                                    name="sectors[]" multiple id="sectors_select">
                                    @foreach ($data['sectors'] as $sector)
                                        <option
                                            {{ old('sectors') && in_array($sector->name, old('sectors')) ? 'selected' : '' }}
                                            value="{{ $sector->name }}">
                                            {{ $sector->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <button type="button" data-toggle="collapse" href="#banner-search-more" role="button"
                            id="banner-search-more-link" aria-expanded="false" aria-controls="banner-search-more"
                            class="banner-search-more-link"><span></span></button>
                        <div class="collapse" id="banner-search-more">
                            <div class="d-flex flex-warp w-100">
                                {{-- <div class="item-col role h-53 col">
                                    <select class="js-example-basic-multiple js-states form-control select-sector mr-5"
                                        name="roles[]" multiple id="roles_select">
                                        @foreach ($data['roles'] as $k => $emp)
                                            <option {{ old('roles') && in_array($k, old('roles')) ? 'selected' : '' }}
                                                value="{{ $k }}">
                                                {{ $k }} ({{ count($emp) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="item-col company h-53 col">
                                    <select class="js-example-basic-multiple js-states form-control select-sector mr-5"
                                        name="employers[]" multiple id="employers_select">
                                        <option></option>
                                        @foreach ($data['employers'] as $k => $emp)
                                            <option
                                                {{ old('employers') && in_array($k, old('employers')) ? 'selected' : '' }}
                                                value="{{ $k }}">
                                                {{ $k }} ({{ count($emp) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="item-col role h-53 col">
                                    <select class="js-example-basic-multiple js-states form-control select-sector mr-5"
                                        name="roles[]" multiple id="roles_select">
                                        @foreach ($data['roles'] as $k => $role)
                                            <option {{ old('roles') && in_array($k, old('roles')) ? 'selected' : '' }}
                                                value="{{ $k }}">
                                                {{ $role }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="item-col company h-53 col">
                                    <select class="js-example-basic-multiple js-states form-control select-sector mr-5"
                                        name="employers[]" multiple id="employers_select">
                                        @foreach ($data['employers'] as $employer)
                                            <option
                                                {{ old('employers') && in_array($employer->company_name, old('employers')) ? 'selected' : '' }}
                                                value="{{ $employer->company_name }}">
                                                {{ $employer->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="rt">
                        <button type="submit" class="btn btn-primary searchbtn h-53">Search</button>
                    </div>
                </div>
            </form>

            <div
                class="item-row-second mt-2 d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between px-2 py-4 bg-white">
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
            <!-- item -->
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#sectors_select').select2({
            placeholder: "Sector",
            sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
        });
        $('#locations_select').select2({
            placeholder: "Location",
            sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
        });

        $("#roles_select").select2({
            placeholder: "Roles",
            sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
        });

        $("#employers_select").select2({
            placeholder: "Company",
            sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
        });
    });
</script>
