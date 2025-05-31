@extends('admin.layout.app')

@section('title', "Update Events")

@section('mystyle')
@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.event.list') }}">Events</a></li>
        <li class="active">Update</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Events Update From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.event.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.event.update', $event->id) }}" method="post" name="eventEditFrm" id="eventEditFrm" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Event Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="title" name="title" placeholder="Event Title" value="{{ $event->title }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" autocomplete="off" class="form-control" id="image" name="image">

                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Event Date</label>
                                    <input type="text" autocomplete="off" class="form-control" id="event_date" name="event_date" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" value="{{ $event->event_date }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="time">Event Time</label>
                                    <input type="text" name="time" class="form-control timepicker" id="time" placeholder="Event Time" value="{{ $event->time }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="registration_link">Registration Link</label>
                                  <input type="text" name="registration_link" class="form-control" id="registration_link" placeholder="Enter Registration Link" value="{{ $event->registration_link }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Status</label>
                                <select name="status" id="status"
                                        class="form-control">
                                    <option disabled selected readonly>Choose Status</option>
                                    @foreach (\App\Enum\EventStatusEnum::cases() as $status)
                                        <option value="{{ $status->value }}" {{ $event->status->value == $status->value ? 'selected': '' }}>
                                            {{ $status->value }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('status'))
                                    <div class="erroralert">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="details">Details</label>
                                    <div style="margin: 10px 0">
                                        <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                            Add a button event
                                        </button>
                                        <div class="collapse" id="collapseExample">
                                            <div class="card card-body">
                                                copy the below short code to the line wher you need the button and then change the title and link parameters with your choice<br />
                                                <span style="font-weight: bold; font-size: 14px;">[button_shortcode title="Button" link="recruit.ie"]</span>

                                            </div>
                                        </div>
                                    </div>
                                  <textarea class="textarea" name="details" id="details" placeholder="Place some text here"
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $event->details }}</textarea>
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

@section('myscript')
<!-- InputMask -->
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
@include('admin.scripts.event')
@endsection
