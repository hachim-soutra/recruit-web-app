<!-- item-col 1-->
<div onclick="showJobPost({{$jp->id}})" class="item-col {{ $jp->id == $firstJobDetail->id ? 'active-div' : ''  }}">
    <!--top-->
    <div class="top">
        <div class="lt">
            <h3>{{ $jp->job_title }}</h3>
            <span>{{ $jp->company_name }}</span>
        </div>
    </div>
    <!--top-->
    <!-- middle -->
    <div class="middle">
        <div class="lt">
            <span>{{ $jp->job_location }}</span>
            <p>{{ $jp->preferred_job_type }}</p>
        </div>
        <div class="rt">
            <div class="img-sec">
                <img src="{{ $jp->company_logo }}">
            </div>
        </div>
    </div>
    <!-- middle -->
    <!-- bottom -->
    <div class="bottom">
        @if ($jp->hide_salary != 'yes')
            <p>{{ $jp->salary_currency }}{{ $jp->salary_from }} -
                {{ $jp->salary_currency }}{{ $jp->salary_to }}
                {{ $jp->salary_period }}
            </p>
        @endif
        <span>
            <b>Posted :</b>
            {{ Carbon\Carbon::createFromTimeStamp(strtotime($jp->created_at))->diffForHumans() }}
        </span>
        @if ($jp->applicatons()->where('candidate_id', Auth::id())->exists())
            <p class="appliedtext">Applied</p>
        @endif
    </div>
    <!-- bottom -->
</div>
<!-- item-col -->
