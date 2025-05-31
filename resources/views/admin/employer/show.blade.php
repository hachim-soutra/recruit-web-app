@extends('admin.layout.app')

@section('title', 'Employer List')

@section('mystyle')

@endsection

@section('content')
    <section class="content-header">
        <h1>
            Manage Employer
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.employer.list') }}">Employers</a></li>
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
                            <img class="profile-student-img img-responsive img-circle" src="{{ $data->avatar }}"
                                alt="Student profile picture">
                        @else
                            <img class="profile-student-img img-responsive img-circle"
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
                        <strong><i class="fa fa-calendar margin-r-5"></i> Date Of Established</strong>
                        <p class="text-muted">{{ $data->employer->established_in ?? '' }}</p>
                        <hr>
                        <strong><i class="fa fa-calendar margin-r-5"></i>Date of Registration</strong>
                        <p class="text-muted">{{ Carbon::parse($data->created_at)->format('jS F Y') }}</p>
                        <hr>

                        <strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
                        <p class="text-muted">
                            {{ $data->employer->address ?? '' }} {{ $data->employer->city ?? '' }}
                            {{ $data->employer->state ?? '' }} {{ $data->employer->country ?? '' }}
                            {{ $data->employer->zip ?? '' }}
                        </p>
                        <hr>
                        <strong><i class="fa fa-phone-square margin-r-5"></i> Contact</strong>
                        <p class="text-muted">{{ $data->phone_number }}</p>

                    </div>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#other-details" data-toggle="tab">Other Details</a></li>
                        <li class=""><a href="#subscription" data-toggle="tab">Company Details</a></li>
                        <li class=""><a href="#transaction" data-toggle="tab">Transaction History</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="other-details">
                            <p>Contact Person : {{ $data->employer->company_ceo ?? '' }}</p><br>
                            <p>Ownership Type : {{ $data->employer->ownership_type ?? '' }}</p><br>
                            <p>Number Of Employees : {{ $data->employer->number_of_employees ?? '' }}</p><br>
                            <p>Website Link : {{ $data->employer->website_link ?? '' }}</p><br>
                            <p>Linkedin Link : {{ $data->employer->linkedin_link ?? '' }}</p><br>

                        </div>

                        <div class="tab-pane" id="subscription">
                            <p>
                                @php
                                    if (isset($data->employer->company_details) && !empty($data->employer->company_details)) {
                                        echo nl2br($data->employer->company_details);
                                    }
                                @endphp
                            </p><br>
                            <p>Logo : <img class="profile-student-img img-responsive img-circle"
                                    src="{{ $data->employer->company_logo ?? '/appbackend/uploads/company_logo/no_logo.jpeg' }}">
                            </p><br>

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
                                            <td>{{ $e->job->job_title ?? '' }}</td>
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
