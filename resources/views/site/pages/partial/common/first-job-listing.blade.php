<div class="col-lg-7">
    <div class="item-tr-sec">
        <div class="item-col">
            <div class="top">
                <div class="figure">
                    <img src="{{ $firstJobDetail->company_logo }}">
                </div>
                <p>{{ $firstJobDetail->company_name }}</p>
                <h4>{{ $firstJobDetail->job_title }}</h4>
                @if(Auth::user() == null || Auth::user()->user_type === 'candidate')
                    @if ($firstJobDetail->applicatons() != null && $firstJobDetail->applicatons()->where('candidate_id', Auth::id())->exists())
                        <button type="button" class="btn btn-success appliedbtn"><i
                                class="fa fa-check" style="font-size: xx-small;"></i>
                            Applied
                        </button>
                    @else
                        <input type="button" id="jobapplybtn" class="jobapplybtn"
                               value="Apply" name=""
                               onclick="jobApply('{{ $firstJobDetail->id }}')"
                        />
                    @endif
                @endif
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
                <p>{!! str_limit(nl2br(preg_replace('/(\r\n|\n|\r)/', '<br/>', $firstJobDetail->job_details)), $limit = 525, $end = '...')  !!}</p>
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
