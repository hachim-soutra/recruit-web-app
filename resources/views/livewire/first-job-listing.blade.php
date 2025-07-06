<div class="d-none d-md-block col-lg-7">
    <div class="item-tr-sec">
        <div class="item-col">
            <div class="top">
                <div class="figure">
                    <img src="{{ $firstJobDetail->company_logo }}" id="company-logo-first" >
                </div>
                <p>{{ $firstJobDetail->company_name }}</p>
                <a href="{{ route('common.job-detail', [ 'id' => $firstJobDetail->slug  ]) }}" class="title-more-link">
                    <h4>{{ $firstJobDetail->job_title }}</h4>
                </a>
                <div class="d-flex gap-2 flex-column flex-md-row align-items-center justify-content-center justify-content-md-between">
                    <div>
                        @if(Auth::user() == null || Auth::user()->user_type === 'candidate')
                            @if ($firstJobDetail->applicatons() != null && $firstJobDetail->applicatons()->where('candidate_id', Auth::id())->exists())
                                <button type="button" class="btn btn-success appliedbtn"><i
                                        class="fa fa-check" style="font-size: xx-small;"></i>
                                    {{$firstJobDetail->post_job_type === 'career_website' ? 'Applied via Career Website' : 'Applied'}}
                                </button>
                            @elseif ($firstJobDetail->post_job_type === 'career_website')
                                <input type="button"
                                    id="jobapplybtn"
                                    class="jobapplybtn"
                                    value="Apply via Career Website"
                                    onclick="applyViaCareerWebsite('{{ $firstJobDetail->application_url }}', '{{ $firstJobDetail->id }}')"
                            />
                            @else
                                <input type="button" id="jobapplybtn" class="jobapplybtn"
                                    value="Apply" name=""
                                    onclick="jobApply('{{ $firstJobDetail->id }}')"
                                />
                            @endif
                        @endif
                        <a href="{{  route('common.company-detail', [ 'id' => $firstJobDetail->company_id ]) }}"
                           class="btn employer-info ml-3" type="button" style="background: #f3f3f3;">Employer info</a>
                    </div>
                    <div class="d-flex flex-row align-items-center justify-content-center mt-4 mt-md-0">
                        @if (Auth::user() && $firstJobDetail->bookmark != null && $firstJobDetail->bookmark->candidate_id === Auth::user()->id)
                            <div class="mr-2">
                                <a href="{{ route('bookmarked-job', ['jobid' => $firstJobDetail->id]) }}" style="color: #EB1829; cursor: pointer;">
                                    <i class="fa fa-bookmark fa-lg"></i>
                                    Unsave
                                </a>
                            </div>
                        @else
                            <div class="mr-2">
                                <a href="{{ route('bookmarked-job', ['jobid' => $firstJobDetail->id]) }}" style="color: #EB1829; cursor: pointer;">
                                    <i class="fa fa-bookmark-o fa-lg"></i>
                                    Save
                                </a>
                            </div>
                        @endif
                        <div class="">
                            <a data-toggle="modal" data-target="#job-detail-share" style="color: #EB1829; cursor: pointer;">
                                <i class="fa fa-share-alt  fa-lg"></i>
                                Share
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text">
                @if ($firstJobDetail->hide_salary != 'yes')
                    <div class="lt">
                        <span>{{ $firstJobDetail->salary_period }}</span>
                        <p>{{ $firstJobDetail->salary_currency }}{{ $firstJobDetail->salary_from }}
                            -
                            {{ $firstJobDetail->salary_currency }}{{ $firstJobDetail->salary_to }}
                        </p>
                    </div>
                @endif

                <div class="rt">
                    <span><b>Posted: </b>{{ Carbon\Carbon::createFromTimeStamp(strtotime($firstJobDetail->created_at))->diffForHumans() }}</span>
                </div>
            </div>
            @if (Auth::user() && $firstJobDetail->bookmark != null && $firstJobDetail->bookmark->candidate_id === Auth::user()->id)
                <div class="save-icon" style="width:5%;">
                    <a
                        href="{{ route('bookmarked-job', ['jobid' => $firstJobDetail->id]) }}"><img
                            src="{{ asset('frontend/images/bookmarked.png') }}"></a>
                </div>
            @else
                <div class="save-icon">
                    <a
                        href="{{ route('bookmarked-job', ['jobid' => $firstJobDetail->id]) }}"><img
                            src="{{ asset('frontend/images/bookmark.svg') }}"></a>
                </div>
            @endif
        </div>
        <div class="item-col-txt">

            <div class="item location-sec">
                <span>Job location</span>
                <p>{{ $firstJobDetail->job_location }}</p>
            </div>


            <div class="item job-titlec">
                <h5>Job Title</h5>
                <p>{{ $firstJobDetail->job_title }}</p>
            </div>

            <div class="item job-desc">
                <h5>Job Descriptions</h5>
                <p>
                    {!! substr(nl2br(preg_replace('/(\r\n|\n|\r)/', '<br/>', $firstJobDetail->job_details)), 0, 300) !!}
                    <a href="{{ route('common.job-detail', [ 'id' => $firstJobDetail->slug  ]) }}" class="show-more-link">Show more ...</a>
                </p>
            </div>

            <div class="item job-titlec">
                <h5>Qualifications</h5>
                <?php
                $replace = str_replace('[', ' ', str_replace(']', ' ', $firstJobDetail->qualifications));
                $qualification_explode = explode('|', $replace);
                ?>
                <?php for ($i = 0;
                           $i < count($qualification_explode);
                           $i++): ?>
                <p>{{ stripslashes(str_replace('"', ' ', $qualification_explode[$i])) }}
                </p>
                <?php endfor; ?>
            </div>

            <div class="item job-desc">
                <h5>Skills</h5>
                <?php
                $skill_explode = [];
                $replace = str_replace('[', ' ', str_replace(']', ' ', $firstJobDetail->job_skills));
                if (str_contains($replace, '|')) {
                    $skill_explode = explode('|', $replace);
                }
                if (str_contains($replace, ',')) {
                    $skill_explode = explode(',', $replace);
                }
                ?>
                <ul>
                    <?php for ($i = 0;
                               $i < count($skill_explode);
                               $i++): ?>
                    <li>{{ stripslashes(str_replace('"', ' ', $skill_explode[$i])) }}</li>
                    <?php endfor; ?>
                </ul>
            </div>


            <div class="item job-desc">
                <h5>Experience</h5>
                <?php
                $replace = str_replace('[', ' ', str_replace(']', ' ', $firstJobDetail->experience));
                $experience_explode = explode('|', $replace);
                ?>
                <ul>
                    <?php for ($i = 0;
                               $i < count($experience_explode);
                               $i++): ?>
                    <li>{{ stripslashes(str_replace('"', ' ', $experience_explode[$i])) }}
                    </li>
                    <?php endfor; ?>
                </ul>
            </div>
            <div class="d-flex flex-column align-items-center justify-content-center">
                <a href="{{ route('common.job-detail', [ 'id' => $firstJobDetail->slug  ]) }}"
                   class="btn show-more-button" type="button">Show More</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="applyConfirmModal" tabindex="-1" aria-labelledby="applyConfirmLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="applyConfirmLabel">Confirm Application</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Did you apply for this job on the external career website?
      </div>
      <div class="modal-footer">
        <button type="button" id="confirmNo" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="button" id="confirmYes" class="btn btn-primary">Yes</button>
      </div>
    </div>
  </div>
</div>


@include('site.pages.partial.common.job-detail.share-job-modal', [
    'job' => $firstJobDetail,
])
<script>
    function shareJob(social) {
        const title = '<?= $firstJobDetail->job_title ?>';
        const link = encodeURI(window.location.href);
        const msg = encodeURIComponent('Hey, I found this job');

        if (social === 'facebook') {
            window.open(`https://www.facebook.com/share.php?u=${link}`, "_blank");
        }
        else if (social === 'whatsapp') {
            window.open(`https://api.whatsapp.com/send?text=${msg}: ${link}`, "_blank");
        }
        else if (social === 'twitter') {
            window.open(`http://twitter.com/share?&url=${link}&text=${msg}&hashtags=javascript,programming`, "_blank");
        }
        else if (social === 'linkedin') {
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${link}`, "_blank");
        }
        else if (social === 'clipboard') {
            navigator.clipboard.writeText(link).then(() => {
                $('#job-detail-share').modal('hide');
                toastr.success("Job link copied to clipboard");
            },() => {
                toastr.error('Failed to copy job link');
            });
        }
    }

    function applyViaCareerWebsite(url, jobId) {
        var isAuthenticated = @json(auth()->check());
        if (!isAuthenticated) {
            window.location.href = "{{ route('signin') }}" + '/' +jobId;
            return;
        }
        window.open(url, '_blank');
         $.ajax({
            url: APP_URL +'/save-job-carrier',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                job_id: jobId
            }}
        );
        setTimeout(() => {
            window.addEventListener('focus', function onFocus() {
                window.removeEventListener('focus', onFocus);
                showConfirmationPopup(jobId);
            });
        }, 500);
    }

    function showConfirmationPopup(jobId) {
        $('#applyConfirmModal').modal({ backdrop: 'static',
            keyboard: false,
            show: true
        });

        $('#confirmYes').off('click').on('click', function () {
            const APP_URL = '<?= env('APP_URL') ?>';
            jobApply(jobId);
            // $.ajax({
            //     url: APP_URL +'/apply-job',
            //     type: 'POST',
            //     data: {
            //         _token: '{{ csrf_token() }}',
            //         job_id: jobId
            //     },
            //     // beforeSend: function () {
            //     //     $('#confirmYes').prop('disabled', true);
            //     // },
            //     // complete: function () {
            //     //     $('#confirmYes').prop('disabled', false);
            //     // },
            //     success: function (res) {
            //         if (res.code === 200) {
            //             toastr.success(res.msg);
            //             $('#applyConfirmModal').modal('hide');
            //             setTimeout(() => location.reload(), 2500);
            //         } else {
            //             toastr.error(res.msg);
            //         }
            //     },
            //     error: function (err) {
            //         console.error(err);
            //         toastr.error('An error occurred. Please try again.');
            //     }
            // });
        });

        $('#confirmNo').off('click').on('click', function() {
            $('#applyConfirmModal').modal('hide');
        });
    }
</script>
