@extends('admin.layout.app')

@section('title', 'Admin Alert')

@section('content')
    <section class="content-header">
        <h1>
            Manage Alert
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Alert</li>
        </ol>
    </section>
    <section class="content">
        <div class="box">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="col-md-7 col-xs-12">
                                <h3 class="box-title">All Alert List</h3>
                            </div>
                            <div class="col-md-4 col-xs-12 d-flex justify-content-between flex-col">
                                <form method="GET">
                                    <div class="input-group">
                                        <input type="text" name="keyword" class="form-control d-block"
                                            placeholder="Search" value="{{ $request->keyword }}">
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i
                                                    class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th scope="col">Job seeker</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Industry</th>
                                    <th scope="col">Salary Period</th>
                                    <th scope="col">Salary Range</th>
                                    <th scope="col">Employment Type</th>
                                    <th scope="col">Job Location</th>
                                    <th scope="col">Date of Registration</th>
                                </tr>
                                @forelse ($data as $e)
                                    <tr>
                                        <td>{{ $e->candidate?->user?->name }}</td>
                                        <td>{{ $e->candidate?->user?->email }}</td>
                                        <td>{{ $e->industry }}</td>
                                        <td>{{ $e->salary_period }}</td>
                                        <td>{{ $e->salary_rate }}</td>
                                        <td>{{ $e->preferred_job_type }}</td>
                                        <td>{{ $e->job_location }}</td>
                                        <td>{{ $e->created_at->format('jS F Y') }}</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="7">No Data found</td>
                                    </tr>
                                @endforelse
                            </table>
                            <div class="mt-5">
                                {{ $data->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('myscript')
    @include('admin.scripts.campaign')
@endsection
