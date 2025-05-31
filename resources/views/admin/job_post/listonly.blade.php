@extends('admin.layout.app')

@section('title', "Admin Job Post | $type")

@section('mystyle')
<style>
    .expiredjob{
        border: 1px solid;
        padding: 6px;
        background-color: #f10c37;
        color: white;
        border-radius: 7px;
        font-size: 13px;
    }
    .activejob{
        border: 1px solid;
        padding: 6px;
        background-color: #0080009c;
        color: white;
        border-radius: 7px;
        font-size: 13px;
    }
</style>
@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Job Post For {{$type}}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{$type}} Job Post</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="row">
            <div class="box-body">
                <div class="col-md-4 col-xs-12">
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
                </div>
                <div class="col-md-4 col-xs-12">
                    <a href="{{ route('admin.job.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
                        Create
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All {{$type}} Job Post List</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Employer</th>
                            <th scope="col">Job Location</th>
                            <th scope="col">Industry</th>
                            <th scope="col" class="text-center">Skills</th>
                            <th scope="col" style="width:10%;" class="text-center">Expiry Status</th>
                            {{-- <th scope="col" class="text-center">Experience</th>
                            <th scope="col" class="text-center">Total Hire</th> --}}
                            <th scope="col" class="text-center">Job Status</th>
                            <th scope="col" style="width:19%;" class="text-center">Action</th>
                        </tr>
                        @forelse ($data as $e)

                            <tr>
                                <td>{{ $e->job_title }}</td>
                                <td>{{ $e->employer->name }}</td>
                                <td>{{ $e->job_location }}</td>
                                <td>{{ $e->functional_area }}</td>
                                <td class="text-center">
                                    @foreach (json_decode($e->job_skills) as $member)
                                        {{ $member }}
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    @if ($e->job_expiry_date> date('Y-m-d'))
                                        <span class="activejob"> Job Active</span>
                                    @else
                                    <span class="expiredjob" data-toggle="tooltip" data-placement="bottom" title="To active this job upgrade expiry date">
                                        Job Expired</span>
                                    @endif
                                </td>
                                {{-- <td class="text-center">{{ $e->experience }}</td>
                                <td class="text-center">{{ $e->total_hire }}</td> --}}
                               <td class="text-center">
                                    <select name="status" class="job-status" id="{{ $e->id }}">
                                        <option value="Save as Draft" {{ ($e->job_status == "Save as Draft")? "Selected" : "" }}>Save as Draft</option>
                                        <option value="Published" {{ ($e->job_status == "Published")? "Selected" : "" }}>Published</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.job.edit', $e->id) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Edit Details">
                                            <i class="fa fa-fw fa-pencil"></i>
                                        </a>

                                        <a href="{{ route('admin.job.show', $e->id) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" title="Job Details">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>

                                        <a href="{{route('admin.job.destroy', $e->id)}}" class="btn btn-sm btn-danger js-tooltip-enabled deleteRow" data-toggle="tooltip" data-id="{{ $e->id }}" title="Delete" data-method="DELETE" data-original-title="Delete">
                                            <i class="fa fa-fw fa-times"></i>
                                        </a>
                                        <a href="{{ route('admin.job.applicants', $e->id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="bottom" title="Applicants">
                                            Applicants
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center">No Data found</td>
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
@include('admin.scripts.job')
@endsection
