@extends('admin.layout.app')

@section('title', "Plans Details")

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
        Plan Details
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.plan.list') }}">Plans</a></li>
        <li class="active">Details</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Plan Details</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.plan.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                @if ($plan->status==\App\Enum\Payments\PlanStatusEnum::ACTIVE)
                                    <a href="#" class="btn btn-sm btn-success blockIns" data-id="{{ $plan->id }}" data-method="PATCH" data-toggle="tooltip" data-placement="bottom" title="ACTIVE">
                                        <i class="fa fa-fw fa-check"></i> Active
                                    </a>
                                @else
                                    <a href="#" class="btn btn-sm btn-danger activeIns" data-id="{{ $plan->id }}" data-method="PATCH" data-toggle="tooltip" data-placement="bottom" title="INACTIVE">
                                        <i class="fa fa-fw fa-times"></i> In-active
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Plan Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="title" name="title" placeholder="Event Title" value="{{ $plan->title }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Plan Slug</label>
                                    <input type="text" autocomplete="off" class="form-control" id="slug" name="slug" placeholder="Event Slug" value="{{ $plan->slug }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Plan For</label>
                                    <input type="text" autocomplete="off" class="form-control" id="plan_for" name="plan_for" value="{{ $plan->plan_for->value }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="time">Slot Number</label>
                                    <input type="text" name="slot_number" class="form-control" id="slot_number" placeholder="Slot Number" value="{{ $plan->job_number }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Number Of Month</label>
                                    <input type="number" min="1" max="12" autocomplete="off" class="form-control" id="number_of_month" name="number_of_month" value="{{ $plan->packages[0]->number_of_month }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Price</label>
                                    <input type="number" min="1" autocomplete="off" class="form-control" id="price" name="price" value="{{ $plan->packages[0]->price }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Creation Date</label>
                                    <input type="text" autocomplete="off" class="form-control" id="created_at" name="created_at" value="{{ $plan->created_at }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="details">Description</label>
                                    <br />
                                 {!! $plan->description !!}
                                </div>
                            </div>
                        </div>
                        <hr/>
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
