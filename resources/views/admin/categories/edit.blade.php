@extends('admin.layout.app')

@section('title', "Speciality Edit")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.categories') }}">Specialties</a></li>
        <li class="active">Edit</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Speciality Create From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.categories') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.category.update', $category->id) }}" method="post" name="category-edit-frm" id="category-edit-frm" class="category-edit-frm">
                        @csrf
                        @method('patch')
                         <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label" for="name">Speciality Name <span class="required">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Speciality Name" value="{{ $category->name }}">
                        </div>
                        <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                            <label class="control-label" for="description">Speciality Description</label>
                            <textarea name="description" class="form-control" id="description" rows="3">{{ $category->description }}</textarea>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('admin.categories') }}" class="btn btn-primary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('myscript')
@include('admin.scripts.category')
@endsection
