@extends('admin.layout.app')

@section('title', "Employe Job List")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Employer Jobs
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.employer.list') }}">Employers</a></li>
        <li class="active">Job List</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="box">
        <div class="row">
            <div class="box-body">
                <div class="col-md-4 col-xs-12">
                    <form method="GET" action="">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control pull-right" placeholder="Search by job title" value="{{ (isset($request->keyword)) ? $request->keyword : null }}">
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
                    {{-- <a href="{{ route('admin.employer.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
                        Create Employer
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{ ucfirst($user->name) }} Posted Job List</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>Employer Name</th>
                            <th>Job Title</th>                           
                            <th>Job Status</th>
                            <th>Total Application</th>
                            <th>Company Name</th>                          
                            <th>Job Post Date</th>                          
                            <th>Job Expire Date</th>                          
                            <th class="text-center">Action</th>
                        </tr>
                        @forelse ($data as $e)
                            <tr>
                                <td><a href="{{ route('admin.employer.show', $e->employer->id) }}">{{ $e->employer->name }}</a></td>
                                <td><a href="{{ route('admin.job.show', $e->id) }}">{{ $e->job_title }}</a></td>
                                <td>{{ $e->job_status }}</td>
                                <td>{{ $e->applicatons_count }}</td>
                                <td>{{ $e->company_name }}</td>
                                <td>{{ Carbon::parse($e->created_at)->format('d M Y')}}</td>
                                <td>{{ Carbon::parse($e->job_expiry_date)->format('d M Y') }}</td>
                 
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.employer.job.applicant', $e->id) }}" class="btn btn-xs btn-info">Applicants</a>
                                       
                                    </div>
                                </td>
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
@include('admin.scripts.employer')
@endsection
