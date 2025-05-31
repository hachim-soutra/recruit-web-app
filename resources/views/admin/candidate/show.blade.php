@extends('admin.layout.app')

@section('title', "Candidate Details")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Candidates
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.candidate.list') }}">Candidates</a></li>
        <li class="active">Details</li>
    </ol>
</section>
<style>
    #my{
zoom: 100%;
}
</style>
<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    @if ($data->avatar)
                        <img class="profile-student-img img-responsive img-circle" style="margin:auto;" src="{{ $data->avatar }}"
                        alt="Student profile picture">
                    @else
                        <img class="profile-student-img img-responsive img-circle" style="margin:auto;" src="{{ asset('backend/img/no-image.png') }}"
                        alt="Student profile picture">
                    @endif
                    <h3 class="profile-username text-center">{{ $data->name }}</h3>
                    <p class="text-muted text-center"><i class="fa fa-at" aria-hidden="true"></i>   {{ $data->email }}</p>
                    @if ($data->mobile)
                     <p class="text-muted text-center"><i class="fa fa-mobile" aria-hidden="true"></i>   {{ $data->mobile }}</p>
                    @endif

                </div>
            </div>
            
            @if ($data->candidate->resume)
            <div class="panel">
                <a target="_blank" href="{{ $data->candidate->resume }}" class="btn btn-primary" style="display:block;"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;&nbsp; Download CV</a>
            </div>
            @endif
            
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">About {{ $data->name }}</h3>
                </div>
                <div class="box-body">
                    <strong><i class="fa fa-calendar margin-r-5"></i> Date Of Birth</strong>
                    <p class="text-muted">{{ $data->candidate->date_of_birth }}</p>
                    <hr>
                    <strong><i class="fa fa-user margin-r-5"></i> Gender</strong>
                    <p class="text-muted">{{ Str::ucfirst($data->candidate->gender) }}</p>
                    <hr>
                    <strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
                    <p class="text-muted">
                        {{ $data->candidate->address }} {{ $data->candidate->city }} {{ $data->candidate->state }} {{ $data->candidate->country }} {{ $data->candidate->zip }}
                    </p>
                    <hr>
                    <strong><i class="fa fa-phone-square margin-r-5"></i> Contact</strong>
                    <p class="text-muted">{{ $data->candidate->alternate_mobile_number }}</p>

                </div>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">

                    <li class="active"><a href="#other-details" data-toggle="tab">Other Details</a></li>
                    <li class=""><a href="#subscription" data-toggle="tab">Bio Data</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="other-details">
                       <p>Total year of experience: {{ $data->candidate->total_experience_year }} {{$data->candidate->total_experience_month}} months</p><br>
                       <p>Highest Qualification: {{ $data->candidate->highest_qualification }}</p><br>
                       <p>Specialization: {{ $data->candidate->specialization }}</p><br>
                       <p>University/Institute: {{ $data->candidate->university_or_institute }}</p><br>
                       <p>Year Of Graduation: {{ $data->candidate->year_of_graduation }}</p><br>
                       <p>Education Type: {{ $data->candidate->education_type }}</p><br>
                       <p>Candidate Type: {{ $data->candidate->candidate_type }}</p><br>
                       <p>Preferred Job Type: {{ $data->candidate->preferred_job_type }}</p><br>
                       <p>Nationality: {{ $data->candidate->nationality }}</p><br>
                       <p>Current Salary: {{ $data->candidate->current_salary }}</p><br>
                       <p>Expected Salary: {{ $data->candidate->expected_salary }}</p><br>
                       <p>Salary Currency: {{ $data->candidate->salary_currency }}</p><br>
                       <p>Linkedin Link: {{ $data->candidate->linkedin_link }}</p><br>
                       <p>Other Known Languages: {{ $data->candidate->languages }}</p><br>
                       
                        @if (false && $data->candidate->resume)
                            <p>Resume Title: {{ $data->candidate->resume_title }}</p><br>
                            <p>Resume:
                            <iframe class="doc" src="{{ $data->candidate->resume }}" alt="{{ $data->candidate->resume_title }}"  width="" height="" border="0" id="my"></iframe></p><br>
                        @endif

                        @if ($data->candidate->cover_letter)
                            <p>Cover Letter:
                            <iframe class="doc" src="{{ $data->candidate->cover_letter }}" alt="Cover Letter"></iframe></p><br>
                        @endif


                    </div>
                    <div class="tab-pane" id="subscription">
                        @php
                            echo nl2br($data->candidate->bio);
                        @endphp
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@section('myscript')
@include('admin.scripts.candidate')
@endsection
