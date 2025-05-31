@extends('site.layout.app')
@section('title', 'Job Applicants')
@section('mystyle')
<style>
	.pagination{
		width: 100%;
	}
	.publishedbtn{
		background: url("{{asset('frontend/images/send_black-icon.svg')}}") no-repeat left 20px center #009688 !important;
	}
	.expirejob{
		color: #ed1c24 !important;
		font-size: 16px !important;
		font-weight: bolder !important;
		border: 1px solid;
		border-radius: 9px;
		width: 100px;
		text-align: center;
	}
</style>
@endsection
@section('content')
<div class="post-resume-one">
    <div class="container">
        <div class="bd-block">
            <div class="row">
                <div class="col-lg-12">
                    <div class="box-header">
                        <h3 class="box-title">Engagement ( No of Clicks & Views on Job)</h3>
                    </div>
                </div>
                <div class="col-12 my-4">
                    <div class="row justify-content-start">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Number of views</h5>
                                            <span class="h2 font-weight-bold mb-0">{{ $stats['VIEW'] }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <img src="{{ asset('frontend/images/view.png') }}" alt="Views" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Number of clicks</h5>
                                            <span class="h2 font-weight-bold mb-0">{{ $stats['CLICK'] }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <img src="{{ asset('frontend/images/cursor.png') }}" alt="Views" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"> Applicant List Of Job - {{ ucfirst($job->job_title) }}</h3>
                            </div>
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Candidate Name</th>
                                        <th>Resume</th>
                                        <th>Cover Letter</th>
                                        <th>Job Title</th>
                                        <th>Job Status</th>
                                        <th>Applied At</th>
                                    </tr>
                                    @forelse ($data as $e)
                                        <tr>
                                            <td> <a href="{{ route('admin.candidate.show', $e->candidate->id) }}">{{ $e->candidate->name }}</a></td>
                                            <td><a target="_blank" href="{{$e->candidate->candidate->resume}}">{{ basename($e->candidate->candidate->resume) }}</a></td>
                                            <td><a target="_blank" href="{{$e->candidate->candidate->cover_letter}}">{{ basename($e->candidate->candidate->cover_letter) }}</a></td>
                                            <td><a href="{{ route('admin.job.show', $e->jobs->id) }}">{{ $e->jobs->job_title }}</a></td>
                                            <td>{{ $e->status }}</td>
                                            <td>{{ Carbon::parse($e->created_at)->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>No Record Found</td>
                                        </tr>
                                    @endforelse
                                </table>
                                <div class="mt-5">
                                    {{ $data->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('myscript')
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
