@extends('admin.layout.app')

@section('title', "Manage Language")

@section('mystyle')
@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Language
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.language.list') }}">Language</a></li>
        <li class="active">Update</li>
    </ol>
</section>
<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Language Edit From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.language.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                     <form name="langEditFrm" id="langEditFrm" action="{{ route('admin.language.update', $lang->id) }}" method="POST">
                        @method('patch')
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Language Name</label>
                                    <input type="text" autocomplete="off" class="form-control" id="name" name="name" placeholder="Language Name" value="{{ $lang->name }}">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="body">Description</label>
                                    <input type="text" autocomplete="off" class="form-control" id="body" name="body" placeholder="Description" value="{{ $lang->body }}">
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
@include('admin.scripts.language')
@endsection
