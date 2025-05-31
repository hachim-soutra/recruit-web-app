@extends('admin.layout.app')

@section('title', "Employe Job Applicant List")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Employer Job Applicants
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.employer.list') }}">Employers</a></li>
        <li class="active">Applicants</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Engagement ( No of Clicks & Views on Job)</h3>
                </div>
                <div class="box-body">
                    <div class="row" style="padding: 0 40px 0 40px">
                        <div class="col-md-6 col-lg-5 col-xl-3">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body bg-white">
                                    <div class="d-flex flex-column flex-md-row align-items-center">
                                        <div class="d-flex flex-row align-items-start">
                                            <img src="{{ asset('frontend/images/view.png') }}" alt="Views" >
                                            <div>
                                                <h5 class="card-title text-uppercase text-muted mb-0">Number of views</h5>
                                                <span class="h2 font-weight-bold mb-0 text-center">{{ $stats['VIEW'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-5 col-xl-3">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body bg-white">
                                    <div class="d-flex flex-column flex-md-row align-items-center">
                                        <div class="d-flex flex-row align-items-start">
                                            <img src="{{ asset('frontend/images/cursor.png') }}" alt="Views" >
                                            <div>
                                                <h5 class="card-title text-uppercase text-muted mb-0">Number of clicks</h5>
                                                <span class="h2 font-weight-bold mb-0">{{ $stats['CLICK'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                <td><span data-toggle="tooltip" title="{{$e->candidate->candidate->cover_letter}}">{{ substr($e->candidate->candidate->cover_letter, 0, 19) }}...</span></td>
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
</section>

@endsection

@section('myscript')
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@include('admin.scripts.employer')
@endsection
