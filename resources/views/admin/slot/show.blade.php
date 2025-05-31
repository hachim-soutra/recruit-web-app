@extends('admin.layout.app')

@section('title', "Slots Details")

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
        Slots Details
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.slot.list') }}">Slots</a></li>
        <li class="active">Details</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Slots Details</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.slot.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                @if ($slot->status==\App\Enum\Payments\SlotStatusEnum::ACTIVE)
                                    <span class="btn btn-sm btn-success blockIns" data-id="{{ $slot->id }}" data-method="PATCH" data-toggle="tooltip" data-placement="bottom" title="ACTIVE">
                                        <i class="fa fa-fw fa-check"></i> Active
                                    </span>
                                @else
                                    <span class="btn btn-sm btn-danger activeIns" data-id="{{ $slot->id }}" data-method="PATCH" data-toggle="tooltip" data-placement="bottom" title="INACTIVE">
                                        <i class="fa fa-fw fa-times"></i> In-active
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Slot Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="title" name="title" placeholder="Slot Title" value="{{ $slot->title }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Slot Slug</label>
                                    <input type="text" autocomplete="off" class="form-control" id="slug" name="slug" placeholder="Slot Slug" value="{{ $slot->slug }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Slot For</label>
                                    <input type="text" autocomplete="off" class="form-control" id="plan_for" name="plan_for" value="{{ $slot->good_type->value }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="time">Slot Number</label>
                                    <input type="text" name="slot_number" class="form-control" id="slot_number" placeholder="Slot Number" value="{{ $slot->good_number }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Price</label>
                                    <input type="number" min="1" autocomplete="off" class="form-control" id="price" name="price" value="{{ $slot->price }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Creation Date</label>
                                    <input type="text" autocomplete="off" class="form-control" id="created_at" name="created_at" value="{{ $slot->created_at }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="details">Description</label>
                                    <br />
                                 {!! $slot->description !!}
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">Packages Linked To Slot</h3>
                                    </div>
                                    <div class="box-body table-responsive no-padding">
                                        <table class="table table-hover">
                                            <tr>
                                                <th scope="col">Plan For</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Slug</th>
                                                <th scope="col">Slot Number</th>
                                                <th scope="col">Price</th>
                                                <th scope="col" class="text-center">Status</th>
                                            </tr>
                                            @forelse ($slot->packages as $e)
                                                <tr>
                                                    <td>{{ $e->plan->plan_for->value }}</td>
                                                    <td>{{ $e->plan->title }}</td>
                                                    <td>{{ $e->plan->slug }}</td>
                                                    <td>{{ $e->plan->job_number }}</td>
                                                    <td>{{ $e->price }}</td>
                                                    <td class="text-center">
                                                        @if ($e->plan->status==\App\Enum\Payments\PlanStatusEnum::ACTIVE)
                                                            <span class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="ACTIVE">
                                                                <i class="fa fa-fw fa-check"></i> Active
                                                            </span>
                                                        @else
                                                            <span class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="INACTIVE">
                                                                <i class="fa fa-fw fa-times"></i> In-active
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center">No Data found</td>
                                                </tr>
                                            @endforelse

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
