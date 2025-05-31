<div class="item-tr-sec">
    <div class="item-col">

        <div class="top">
            <div class="figure">
                <img src="{{ @$data['firstJobDetail']->company_logo }}"
                    alt="{{ @$data['firstJobDetail']->company_name }}" />
            </div>
            <p title="{{ @$data['firstJobDetail']->company_name }}">{{ @$data['firstJobDetail']->company_name }}</p>
            <!--<h4>{{ @$data['firstJobDetail']->job_title }}</h4>-->
            <h1 title="{{ @$data['firstJobDetail']->job_title }}">{{ @$data['firstJobDetail']->job_title }}</h1>
            <?php $jobid = @$data['firstJobDetail']->id; ?>
            @if (count(@$data['firstJobDetail']->applicatons) > 0)
                <input type="button" value="Apply" name="" onclick="jobListApply('{{ $jobid }}')">
            @else
                <input type="button" value="Apply" name="" onclick="jobListApply('{{ $jobid }}')">
            @endif
        </div>

        <div class="text item-col-txt">
            <div class="lt item">
                @if (@$data['firstJobDetail']->hide_salary != 'yes')
                    <!--<span>{{ @$data['firstJobDetail']->salary_period }}</span>-->
                    <h5>Salary</h5>
                    <p>{{ @$data['firstJobDetail']->salary_currency }}{{ @$data['firstJobDetail']->salary_from }} -
                        {{ @$data['firstJobDetail']->salary_currency }}{{ @$data['firstJobDetail']->salary_to }}
                        {{ @$data['firstJobDetail']->salary_period }}</p>
                @endif
            </div>

            <div class="rt">
                <span><b>Posted :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime(@$data['firstJobDetail']->created_at))->diffForHumans() }}</span>
            </div>
        </div>
        <div class="save-icon">
            <a href="{{ route('bookmarked-job', ['jobid' => @$data['firstJobDetail']->id]) }}"><img
                    src="{{ asset('frontend/images/bookmark.svg') }}"></a>
        </div>
    </div>
    <div class="item-col-txt">

        <div class="item location-sec">
            <h5>Job location</h5>
            <?php			$location_arr = array_filter(explode(',' , trim($data['firstJobDetail']->job_location)));			if(!empty($location_arr)):
				$location_term = trim(strtolower($location_arr[0]));				$location_permalink = trim(route('job-location').'/'.$location_term);				?> <span><a title="{{ @$data['firstJobDetail']->job_location }}"
                    href="{{ $location_permalink }}">{{ @$data['firstJobDetail']->job_location }}</a></span>
            <?php endif; ?>
        </div>

        <div class="item category-sec">
            <h5>Job category</h5>
            <?php
            $job_category_term = trim(strtolower($data['firstJobDetail']->functional_area));
            $job_category_permalink = trim(route('job-category') . '/' . $job_category_term);
            ?>
            <span><a title="{{ $data['firstJobDetail']->functional_area }}"
                    href="{{ $job_category_permalink }}">{{ $data['firstJobDetail']->functional_area }}</a></span>
        </div>

        <div class="item type-sec">
            <h5>Job type</h5>
            <?php
            $job_type_term = trim(strtolower($data['firstJobDetail']->preferred_job_type));
            $job_type_permalink = trim(route('job-type') . '/' . $job_type_term);
            ?>
            <span><a title="{{ $data['firstJobDetail']->preferred_job_type }}"
                    href="{{ $job_type_permalink }}">{{ $data['firstJobDetail']->preferred_job_type }}</a></span>
        </div>

        <!--
  <div class="item job-titlec">
   <h5>Job Title</h5>
   <p>{{ @$data['firstJobDetail']->job_title }}</p>
  </div>
  -->

        <div class="item job-desc">
            <h5>Job Descriptions</h5>
            <p>{!! nl2br(preg_replace('/(\r\n|\n|\r)/', '<br/>', @$data['firstJobDetail']->job_details)) !!}</p>
        </div>

        <div class="item job-titlec">
            <h5>Qualifications</h5>
            <?php
            $replace = str_replace('[', ' ', str_replace(']', ' ', @$data['firstJobDetail']->qualifications));
            $qualification_explode = explode('|', $replace);
            ?>
            <?php for($i = 0;$i < count($qualification_explode);$i++): ?>
            <p>{{ stripslashes(str_replace('"', ' ', $qualification_explode[$i])) }}</p>
            <?php endfor; ?>
        </div>

        <div class="item job-desc">
            <h5>Skills</h5>
            <?php
            $skill_explode = [];
            $replace = str_replace('[', ' ', str_replace(']', ' ', @$data['firstJobDetail']->job_skills));
            if (str_contains($replace, '|')) {
                $skill_explode = explode('|', $replace);
            }
            if (str_contains($replace, ',')) {
                $skill_explode = explode(',', $replace);
            }
            ?>
            <ul>
                <?php for($i = 0;$i < count($skill_explode);$i++): ?>
                <li>{{ stripslashes(str_replace('"', ' ', $skill_explode[$i])) }}</li>
                <?php endfor; ?>
            </ul>
        </div>


        <div class="item job-desc">
            <h5>Experience</h5>
            <?php
            $replace = str_replace('[', ' ', str_replace(']', ' ', @$data['firstJobDetail']->experience));
            $experience_explode = explode('|', $replace);
            ?>
            <ul>
                <?php for($i = 0;$i < count($experience_explode);$i++): ?>
                <li>{{ stripslashes(str_replace('"', ' ', $experience_explode[$i])) }}</li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>
</div>
