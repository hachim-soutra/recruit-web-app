@extends('admin.layout.app')

@section('title', "Add Manage Job Post")

@section('mystyle')
<style>
    .add-edu{
        margin-top: 25px;
    }
</style>
@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Job Post
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.job.list') }}">Jobs</a></li>
        <li class="active">Import</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Import CSV File For Job Post</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.job.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form name="jobCreateFrm" id="jobCreateFrm" action="{{ route('admin.job.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                             <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="employer">Employer</label>
                                    <select class="form-control" name="employer" id="employer">
                                        <option></option>
                                         @forelse ($employers as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->name }}
                                            </option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" style="top: 25px;">
                                <div class="form-group">
                                    <a href="{{ route('admin.employer.create')}}">
                                    <input type="button" value="Add Employer" class="btn btn-primary">  </a>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="total_hire">Import CSV</label>
                                  <input type="file" autocomplete="off" class="form-control" id="import_file" name="import_file" placeholder="Import Job Post CSV File" accept=".csv">
                                </div>
                            </div>

                        </div>

                       <hr/>
                        <div class="row">
                            <div class="col-md-12">
                                <button id="education-btn-submit" type="submit" class="btn btn-success btn-block">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('myscript')<!-- InputMask -->
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
@include('admin.scripts.job')
@include('admin.scripts.location')
@endsection
