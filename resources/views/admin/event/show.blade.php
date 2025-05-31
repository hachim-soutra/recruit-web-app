@extends('admin.layout.app')

@section('title', "Events Details")

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
        Events Details
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.event.list') }}">Events</a></li>
        <li class="active">Details</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Events Details</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.event.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Event Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="title" name="title" placeholder="Event Title" value="{{ $event->title }}" readonly>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Image</label>

                                    <img src="{{ $event->image }}" style="width: 50; height:50px;">

                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Event Date</label>
                                    <input type="text" autocomplete="off" class="form-control" id="event_date" name="event_date" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" value="{{ $event->event_date }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="time">Event Time</label>
                                    <input type="text" name="time" class="form-control" id="time" placeholder="Event Time" value="{{ $event->time }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="registration_link">Registration Link</label>
                                 {!! $event->registration_link !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="details">Details</label>
                                 {!! $event->details !!}
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">Registered Users List</h3>
                                    </div>
                                    <div class="box-body table-responsive no-padding">
                                        <table class="table table-hover">
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Mobile</th>
                                            </tr>
                                            @if (count($event_user) > 0)
                                                @forelse ($event_user as $e)
                                                    <tr>
                                                        <td>{{ $e->r_users['name'] }}</td>
                                                        <td>{{ $e->r_users['email'] }}</td>
                                                        <td>{{ ($e->r_users['mobile'])? $e->r_users['mobile'] : "N/A" }}</td>
                                                    </tr>
                                                @empty

                                                @endforelse
                                            @else
                                                <tr>
                                                    <td class="text-center">No Data found</td>
                                                </tr>
                                            @endif

                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
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
@include('admin.scripts.event')
@endsection
