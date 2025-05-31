@extends('admin.layout.app')

@section('title', "Employe Job Applicant List")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Job Applicants
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.job.list') }}">Job Post</a></li>
        <li class="active">Applicants</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="box">
        <div class="row">
            <div class="box-body">
                {{-- <div class="col-md-4 col-xs-12">
                    <form method="GET" action="">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control pull-right" placeholder="Search" value="{{ (isset($request->keyword)) ? $request->keyword : null }}">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="btn-group">

                    </div>
                </div>
                <div class="col-md-4 col-xs-12">

                </div> --}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> ({{ count($data) }}) Applicant List Of Job - {{ ucfirst($job->job_title) }}</h3>
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
                            <!--
                            <tr>
                                 <td colspan="6">
                                    <pre><?php //print_r($e); ?></pre><hr />
                                 </td>
                            </tr>
                            -->
                            @if ($e->candidate != null)
                            <tr>
                            	<td> <a href="{{ route('admin.candidate.show', $e->candidate->id) }}">{{ $e->candidate->name }}</a></td>
                            	<td><a target="_blank" href="{{$e->candidate->candidate->resume}}">{{ basename($e->candidate->candidate->resume) }}</a></td>
                            	<td><span data-toggle="tooltip" title="{{$e->candidate->candidate->cover_letter}}">{{ substr($e->candidate->candidate->cover_letter, 0, 19) }}...</span></td>
                            	<td><a href="{{ route('admin.job.show', $e->jobs->id) }}">{{ $e->jobs->job_title }}</a></td>
                            	<td>{{ $e->status }}</td>
                            	<td>{{ Carbon::parse($e->created_at)->format('d M Y') }}</td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6">No Record Found</td>
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
@endsection
