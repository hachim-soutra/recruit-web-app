@if (session('success'))
    <script>
        toastr.success("{{ session('success') }}");
    </script>
@endif
@if (session('errors'))
    <script>
        toastr.error("{{ session('errors')->first() }}");
    </script>
@endif
<div class="inner-form-custom">
    @include('site.layout.banner-listing', ['stats' => $data['banner_stats']])
</div>
@if ($data['count'] <= 0)
    <div class="login-block">
        <div class="container">
            <div class="bd-block">
                <h5 class="mb-0 text-center"><span class="h5span">{{ $data['count'] }}</span> Matching Results
                </h5>
            </div>
        </div>
    </div>
@else
    <div class="post-resume-one pt-5">
        <div class="container">
            <div class="bd-block">
                @if ($data['count'] > 0)
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="mb-0">
                                <span class="h5span">{{ @$data['count'] }}</span>
                                Matching Results
                            </h5>
                        </div>
                        <div class="mt-3 col-12 col-md-4 col-lg-3 h-auto">
                            <div id="toolbar">
                                <select class="form-control" id="sort-by-job-select">
                                    <option value="none" disabled selected>Sort by</option>
                                    <option value="relevant">Most relevant</option>
                                    <option value="latest">Latest</option>
                                    <option value="soon">Ending soonest</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row flex-column-reverse flex-md-row">
                    <div class="col-lg-5 mt-4 mt-md-0">
                        <livewire:item-job-listing :jobPosts="$data['jobPost']->items()" :firstJobDetail="$data['firstJobDetail']" />
                        {{ $data['jobPost']->appends(Request::except('page'))->onEachSide(1)->links() }}
                    </div>
                    @if (!empty($data['firstJobDetail']))
                        <livewire:first-job-listing :firstJobDetail="$data['firstJobDetail']" />
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if (Auth::user() && Auth::user()->user_type === 'candidate')
        @include('site.pages.partial.common.apply-job-modal', [
            'candidate' => $data['candidateDetail'],
        ])
    @endif
@endif
