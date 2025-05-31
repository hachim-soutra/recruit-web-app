<div>
    @foreach ($jobPosts as $jp)
        <!-- item-col for laptop -->
        <div wire:click="selectJob({{ $jp['id'] }})"
            class="d-none d-md-block job-detail-scroll item-col mb-3 {{ $jp['id'] == $firstJobDetail['id'] ? 'active-div' : '' }}">
            <!--top-->
            <div class="top">
                <div class="lt">
                    <h3>{{ $jp['job_title'] }}</h3>
                    <span>{{ $jp['company_name'] }}</span>
                </div>
            </div>
            <!--top-->
            <!-- middle -->
            <div class="middle">
                <div class="lt">
                    <span>{{ $jp['job_location'] }}</span>
                    <p>{{ $jp['preferred_job_type'] }}</p>
                </div>
                <div class="rt">
                    <div class="img-sec">
                        <img src="{{ $jp['company_logo'] }}" id="company-logo-list">
                    </div>
                </div>
            </div>
            <!-- middle -->
            <!-- bottom -->
            <div class="bottom">
                @if ($jp['hide_salary'] != 'yes')
                    <p>{{ $jp['salary_currency'] }}{{ $jp['salary_from'] }} -
                        {{ $jp['salary_currency'] }}{{ $jp['salary_to'] }}
                        {{ $jp['salary_period'] }}
                    </p>
                @endif
                <span>
                    <b>Posted :</b>
                    {{ Carbon\Carbon::createFromTimeStamp(strtotime($jp['created_at']))->diffForHumans() }}
                </span>
                @if (auth()?->user()?->user_type === 'candidate' && isset($jp['applications']) && $jp['applications']->where('candidate_id', Auth::id())->count() > 0)
                    <p class="appliedtext">Applied</p>
                @endif
            </div>
            <!-- bottom -->
        </div>
        <!-- item-col for laptop -->
        <!-- item-col for mobile -->
        <div class="d-block d-md-none item-col job-detail-item-mobile-view mb-3" data-id="{{$jp['slug']}}">
            <!--top-->
            <div class="top">
                <div class="lt">
                    <h3>{{ $jp['job_title'] }}</h3>
                    <span>{{ $jp['company_name'] }}</span>
                </div>
            </div>
            <!--top-->
            <!-- middle -->
            <div class="middle">
                <div class="lt">
                    <span>{{ $jp['job_location'] }}</span>
                    <p>{{ $jp['preferred_job_type'] }}</p>
                </div>
                <div class="rt">
                    <div class="img-sec">
                        <img src="{{ $jp['company_logo'] }}" id="company-logo-list">
                    </div>
                </div>
            </div>
            <!-- middle -->
            <!-- bottom -->
            <div class="bottom">
                @if ($jp['hide_salary'] != 'yes')
                    <p>{{ $jp['salary_currency'] }}{{ $jp['salary_from'] }} -
                        {{ $jp['salary_currency'] }}{{ $jp['salary_to'] }}
                        {{ $jp['salary_period'] }}
                    </p>
                @endif
                <span>
                    <b>Posted :</b>
                    {{ Carbon\Carbon::createFromTimeStamp(strtotime($jp['created_at']))->diffForHumans() }}
                </span>
                @if (auth()?->user()?->user_type === 'candidate' && isset($jp['applications']) && $jp['applications']->where('candidate_id', Auth::id())->count() > 0)
                    <p class="appliedtext">Applied</p>
                @endif
            </div>
            <!-- bottom -->
        </div>
        <!-- item-col for mobile -->
    @endforeach
</div>
<script>
    $(document).ready(function() {
        $(".job-detail-scroll").click(function() {
            var elem = $(".row.flex-column-reverse.flex-md-row")[0];
            elem.scrollIntoView();
            // window.scrollTo(0, 0);
        });

        $(document).on('click', '.job-detail-item-mobile-view', function(e) {
            var jobId = $(this).data('id');
            if (jobId !== undefined) {
                var route = "{{ route('common.job-detail', ['id' => ':jobId']) }}";
                location.href = route.replace(':jobId', jobId);
            } else {
                console.error("Missing data-id attribute on #job-detail-item-mobile-view");
            }
        });

    });
</script>
