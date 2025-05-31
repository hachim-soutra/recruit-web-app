@extends('admin.layout.app')

@section('title', "User Details")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.users') }}">Users</a></li>
        <li class="active">Details</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    @if ($user->avatar)
                        <img class="profile-user-img img-responsive img-circle" src="{{ $user->avatar }}"
                        alt="User profile picture">
                    @else
                        <img class="profile-user-img img-responsive img-circle" src="{{ asset('backend/img/user4-128x128.jpg') }}"
                        alt="User profile picture">
                    @endif
                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">{{ $user->email }}</p>
                    <p class="text-muted text-center">{{ $user->mobile }}</p>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">About Me</h3>
                </div>
                <div class="box-body">
                    <strong><i class="fa fa-calendar margin-r-5"></i> Date Of Birth</strong>
                    <p class="text-muted">{{ $user->dob }}</p>
                    <hr>
                    <strong><i class="fa fa-user margin-r-5"></i> Gender</strong>
                    <p class="text-muted">{{ $user->gender }}</p>
                    <hr>
                    <strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
                    <p class="text-muted">
                        {{ $user->address }}, {{ $user->zip }}, {{ $user->country }}
                    </p>
                    <hr>
                    <strong><i class="fa fa-phone-square margin-r-5"></i> Conatct</strong>
                    <p class="text-muted">{{ $user->mobile }}</p>
                    <hr>
                    <strong><i class="fa fa-pencil margin-r-5"></i> Roles</strong>
                    <p>
                        @foreach ($user->roles as $item)
                            <span class="label label-danger">{{ $item->name }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#subscription" data-toggle="tab">Bio Data</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="subscription">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('myscript')
@include('admin.scripts.user')
@endsection
