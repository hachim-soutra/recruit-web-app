@extends('admin.layout.app')

@section('title', 'Coach List')

@section('mystyle')
    <style>
        .m-auto {
            margin: auto;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Manage Coach
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.coach.list') }}">Coaches</a></li>
            <li class="active">Details</li>
        </ol>
    </section>

    <!-- =============Main Page====================== -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        @if ($data->avatar)
                            <img class="profile-student-img img-responsive img-circle m-auto" src="{{ $data->avatar }}"
                                alt="Student profile picture">
                        @else
                            <img class="profile-student-img img-responsive img-circle m-auto"
                                src="{{ asset('backend/img/no-image.png') }}" alt="Student profile picture">
                        @endif
                        <h3 class="profile-username text-center">{{ $data->name }}</h3>
                        <p class="text-muted text-center"><i class="fa fa-at" aria-hidden="true"></i> {{ $data->email }}
                        </p>
                        @if ($data->mobile)
                            <p class="text-muted text-center"><i class="fa fa-mobile" aria-hidden="true"></i>
                                {{ $data->mobile }}</p>
                        @endif

                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">About {{ $data->name }}</h3>
                    </div>
                    <div class="box-body">

                        <strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
                        <p class="text-muted">
                            {{ $data->coach->address }} {{ $data->coach->city }} {{ $data->coach->state }}
                            {{ $data->coach->country }} {{ $data->coach->zip }}
                        </p>
                        <hr>
                        <strong><i class="fa fa-phone-square margin-r-5"></i> Contact</strong>
                        <p class="text-muted">{{ $data->alternate_mobile_number }}</p>

                    </div>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">

                        <li class="active"><a href="#other-details" data-toggle="tab">Other Details</a></li>
                        <li class=""><a href="#subscription" data-toggle="tab">Bio Data</a></li>
                        <li class=""><a href="#transaction" data-toggle="tab">Transaction List</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="other-details">
                            <p>Experience: {{ $data->coach->experience }}</p><br>
                            <p>Qualification: {{ $data->coach->highest_qualification }}</p><br>
                            <p>Specialization: {{ $data->coach->specialization }}</p><br>
                            <p>University/Institute: {{ $data->coach->university_or_institute }}</p><br>
                            <p>Year of graduation: {{ $data->coach->year_of_graduation }}</p><br>
                            <p>Education type: {{ $data->coach->education_type }}</p><br>
                            <p>Coach type: {{ $data->coach->coach_type }}</p><br>
                            <p>Preferred job type: {{ $data->coach->preferred_job_type }}</p><br>
                            <p>Linkedin link: {{ $data->coach->linkedin_link }}</p><br>
                            <p>Facebook link: {{ $data->coach->facebook_link }}</p><br>
                            <p>Instagram link: {{ $data->coach->instagram_link }}</p><br>

                        </div>
                        <div class="tab-pane" id="subscription">
                            @php
                                echo nl2br($data->coach->bio);
                            @endphp
                        </div>
                        <div class="tab-pane" id="transaction">
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Transaction For</th>
                                        <th>Amount</th>
                                        <th>Date</th>

                                    </tr>
                                    @forelse ($transactions as $e)
                                        <tr>
                                            <td>{{ $e->transaction_id }}</td>
                                            <td>Yearly Plan Subscription</td>
                                            <td>{{ $e->amount }}</td>
                                            <td>{{ Carbon::createFromTimeStamp(strtotime($e->created_at))->format('jS F Y') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>No Record Found</td>
                                        </tr>
                                    @endforelse
                                </table>

                            </div>
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
