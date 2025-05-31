@extends('admin.layout.app')

@section('title', "Categories List")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Jubilantlegal Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Specialities</li>
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
                    <a href="{{ route('admin.category.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
                        Create speciality
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All Speciality List</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            {{-- <th>Parent</th> --}}
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                {{-- <td>{{ ($category->parent) ? $category->parent->name : "--" }}</td> --}}
                                <td>{{ $category->createdBy->first_name }} {{ $category->createdBy->last_name }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-xs btn-warning">Edit</a>
                                        <a href="{{ route('admin.category.destroy', $category->id) }}" class="btn btn-xs btn-danger remove-category" data-id="{{ $category->id }}" data-method="DELETE">Remove</a>
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
                        {{ $categories->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('myscript')
@include('admin.scripts.category')
@endsection
