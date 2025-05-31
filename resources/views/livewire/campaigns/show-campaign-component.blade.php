<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="object">Subject :</label>
            <input type="text" class="form-control" readonly value="{{ $campaign->object }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="date_envoi">Date envoi :</label>
            <input type="datetime-local" class="form-control" readonly value="{{ $campaign->date_envoi }}">
        </div>
    </div>
    <div class="col-md-6 ">
        <label>recipients ( {{ count(json_decode($campaign->recipients)) }})
        </label>
        <hr style="margin: 0px">
        <ul class="h-100" style="max-height: 300px; overflow:auto">
            @foreach (json_decode($campaign->recipients) as $candidate)
                <li style="padding: 5px 0px">
                    {{ $candidate }}
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-6 ">
        <label>Selected jobs( {{ count(json_decode($selectedJobsModel)) }})
        </label>
        <hr style="margin: 0px">
        <ul class="h-100" style="max-height: 300px; overflow:auto;list-style: none;padding-left: 0;">
            @foreach (json_decode($selectedJobsModel) as $job)
                <li style="padding: 10px 0px">
                    <i class="fa fa-minus-circle" aria-hidden="true"></i>
                    {{ $job->job_title }} ({{ $job->company_name }}) <i class="fa fa-location-arrow"
                        aria-hidden="true"></i> {{ $job->job_location }}
                    <i class="fa fa-industry" aria-hidden="true"></i> {{ $job->functional_area }}
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="object">Mail :</label>
            <div style="background-color: #f3f2f1;">
                {!! $campaign->mail !!}
            </div>
        </div>
    </div>
</div>
