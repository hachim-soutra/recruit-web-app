<form action="{{ route('common.job-listing') }}" id="jobsearchform" method="GET">
    <div class="item-row" id="filter-section">
        <div class="lt" style="flex-direction: column; gap: 10px;">
            <div class="d-flex flex-warp">
                <div class="item-col w-100 col">
                    <input type="search" placeholder="keyword" value="{{ old('keyword') }}" name="keyword"
                        id="keyword" class="form-control" />
                </div>
                <div class="item-col locations h-53 col">
                    <select class="js-example-basic-multiple js-states form-control select-location mr-5"
                        name="locations[]" multiple id="locations_select">
                        @foreach ($locations as $k => $emp)
                            <option {{ old('locations') && in_array($k, old('locations')) ? 'selected' : '' }}
                                value="{{ $k }}">
                                {{ $k }} ({{ count($emp) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="item-col sector h-53 col">
                    <select class="js-example-basic-multiple js-states form-control select-sector mr-5" name="sectors[]"
                        multiple id="sectors_select">
                        <option></option>
                        @foreach ($sectors as $k => $emp)
                            <option {{ old('sectors') && in_array($k, old('sectors')) ? 'selected' : '' }}
                                value="{{ $k }}">
                                {{ $k }} ({{ count($emp) }})
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
                    <div class="item-col role h-53 col">
                        <select class="js-example-basic-multiple js-states form-control select-sector mr-5"
                            name="roles[]" multiple id="roles_select">
                            @foreach ($roles as $k => $emp)
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
                            @foreach ($employers as $k => $emp)
                                <option {{ old('employers') && in_array($k, old('employers')) ? 'selected' : '' }}
                                    value="{{ $k }}">
                                    {{ $k }} ({{ count($emp) }})
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
