@extends('admin.layout.app')

@section('title', "Manage Qualification")

@section('mystyle')
@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Qualifications
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.qualification.list') }}">Qualifications</a></li>
        <li class="active">Update</li>
    </ol>
</section>
<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Qualification Edit From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.qualification.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form name="qualificationEditFrm" id="qualificationEditFrm" action="{{ route('admin.qualification.update', $qua->id) }}" method="POST">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" autocomplete="off" class="form-control" id="name" name="name" placeholder="Qualification Name" value="{{ $qua->name }}">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" autocomplete="off" class="form-control" id="description" name="description"  value="{{ $qua->description }}">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn waves-effect waves-light btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('myscript')
@include('admin.scripts.qualification')
@endsection
