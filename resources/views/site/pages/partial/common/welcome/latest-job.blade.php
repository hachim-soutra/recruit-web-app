<!-- clients-block -->
<div class="container mt-5">
    <div class="title-block text-center">
        <h2>Latest jobs and opportunities on Recruit.ie</h2>
    </div>

    <div class="row mt-5 justify-content-center">
        @foreach ($jobs as $job)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="latest-jobs-item">
                    <div class="d-flex flex-row align-items-start m-2">
                        <div class="img-sec mr-2">
                            <img src="{{ $job->company_logo }}" alt="{{ $job->slug }}" loading="lazy">
                        </div>
                        <div>
                            <a href="{{ route('common.job-detail', ['id' => $job->slug]) }}">
                                <h3 class="latest-jobs-title">
                                    {{ strlen($job->job_title) >= 60 ?  substr($job->job_title, 0, 60).'...' : $job->job_title }}
                                </h3>
                            </a>
                            <span class="latest-jobs-title" style="font-weight: 400">{{ $job->company_name }}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-row align-items-end justify-content-between m-2">
                        <p class="mb-0">
                           <span class="latest-item-posted-text">
                                <i class="fa fa-map-marker fa-lg" aria-hidden="true" style="color: var(--red-color)"></i>
                                LOCATION
                           </span>
                        <br>
                        <span class="latest-item-posted-text mb-0">{{ $job->job_location }}</span>
                        </p>

                        <p class="mb-0">{{ $job->preferred_job_type }}</p>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>
<!-- clients-block -->
