@extends('admin.layout.app')

@section('title', "Dashboard")

@section('mystyle')

@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Recruit.ie Admin
        <small>Admin dashboard</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            {{-- <h3 class="box-title">Title</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
                    <i class="fa fa-minus"></i></button>
            </div> --}}
        </div>

        <div class="box-body">
            <div class="dashboard-wrap">
                <div class="block-content text-center">
                    <div class="dashbord-box sdasbordmain row">
                            <div class="col-md-3 ">
                                <div class="widget widget-seven candidate-box">
                                    <div class="widget-body">
                                        <div href="javascript: void(0);" class="widget-body-inner">
                                            <a href="{{ route('admin.candidate.list') }}">
                                            <h5 class="text-uppercase">Candidates</h5>
                                            </a>
                                            <div class="dash-bottom">
                                                <div class="dash-icon">
                                                    <i class="fa fa-users" aria-hidden="true"></i>
                                                </div>
                                                <div class="dash-count">
                                                    <span class="counter-count">
                                                        <span class="counter-init" data-from="0" data-to="67">{{ $candidate }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3 ">
                                <div class="widget widget-seven employer-box">
                                    <div class="widget-body">
                                        <div href="javascript: void(0);" class="widget-body-inner">
                                            <a href="{{ route('admin.employer.list') }}">
                                            <h5 class="text-uppercase">Employers</h5>
                                            </a>
                                            <div class="dash-bottom">
                                                <div class="dash-icon">
                                                    <i class="fa fa-users" aria-hidden="true"></i>
                                                </div>
                                                <div class="dash-count">
                                                    <span class="counter-count">
                                                        <span class="counter-init" data-from="0" data-to="67">{{ $employer }}</span>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class=" widget widget-seven background-success coach-box">
                                    <div class="widget-body">
                                        <div href="javascript: void(0);" class="widget-body-inner">
                                            <a href="{{ route('admin.coach.list') }}">
                                                <h5 class="text-uppercase">Coaches</h5>
                                            </a>
                                            <div class="dash-bottom">
                                                <div class="dash-icon">
                                                    <i class="fa fa-trophy" aria-hidden="true"></i>
                                                </div>
                                                <div class="dash-count">
                                                    <span class="counter-count">
                                                        <span class="counter-init" data-from="25" data-to="50">{{ $coach }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class=" widget widget-seven background-success job-box">
                                    <div class="widget-body">
                                        <div class="widget-body-inner">
                                            <a href="{{ route('admin.job.listonly', 'Published') }}">
                                                <h5 class="text-uppercase">Published Jobs</h5>
                                            </a>
                                            <div class="dash-bottom">
                                                <div class="dash-icon">
                                                    <i class="fa fa-trophy" aria-hidden="true"></i>
                                                </div>
                                                <div class="dash-count">
                                                    <span class="counter-count">
                                                        <span class="counter-init" data-from="25" data-to="50">{{ $publish_jobs }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class=" widget widget-seven background-success job-box">
                                    <div class="widget-body">
                                        <div class="widget-body-inner">
                                            <a href="{{ route('admin.job.listonly', 'Save as Draft') }}">
                                                <h5 class="text-uppercase">Draft Jobs</h5>
                                            </a>
                                            <div class="dash-bottom">
                                                <div class="dash-icon">
                                                    <i class="fa fa-trophy" aria-hidden="true"></i>
                                                </div>
                                                <div class="dash-count">
                                                    <span class="counter-count">
                                                        <span class="counter-init" data-from="25" data-to="50">{{ $draft_jobs }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class=" widget widget-seven background-success job-box">
                                    <div class="widget-body">
                                        <div class="widget-body-inner">
                                            <a href="{{ route('admin.job.expire') }}">
                                                <h5 class="text-uppercase">Expired Jobs</h5>
                                            </a>
                                            <div class="dash-bottom">
                                                <div class="dash-icon">
                                                    <i class="fa fa-trophy" aria-hidden="true"></i>
                                                </div>
                                                <div class="dash-count">
                                                    <span class="counter-count">
                                                        <span class="counter-init" data-from="25" data-to="50">{{ $expire_jobs }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="widget widget-box widget-seven background-success image-box">
                                <div class="widget-body">
                                    <div href="javascript: void(0);" class="widget-body-inner">
                                        <a href="">
                                        <h5 class="text-uppercase">Horses</h5>
                                        </a>
                                        <div class="dash-bottom">
                                            <div class="dash-icon">
                                                <i class="fa fa-trophy" aria-hidden="true"></i>
                                            </div>
                                            <div class="dash-count">
                                                <span class="counter-count">
                                                    <span class="counter-init" data-from="25" data-to="50">23</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                    </div>
                </div>
            </div>
        </div>

        <div class="box-footer">

        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection

@section('myscript')
@endsection
