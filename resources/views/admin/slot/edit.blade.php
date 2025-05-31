@extends('admin.layout.app')

@section('title', "Update Slots")

@section('mystyle')
@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.slot.list') }}">Slots</a></li>
        <li class="active">Update</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Plans Update From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.plan.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.slot.update', $slot->id) }}" method="post" name="slotEditFrm" id="slotEditFrm">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Package Slot</label>
                                <select name="packages[]" id="packages"
                                        class="form-control" multiple>
                                    <option disabled readonly>Choose Package</option>
                                    @foreach ($packages as $item)
                                        <option value="{{ $item->id }}" {{ $slot->packages->where('id' , $item->id)->count() > 0 ? 'selected': '' }}>
                                            {{ $item->plan->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="event_date">Status</label>
                                <div class="form-group">
                                    <div class="form-check  radio-inline">
                                        <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="{{\App\Enum\Payments\SlotStatusEnum::ACTIVE->value}}" {{ $slot->status == \App\Enum\Payments\SlotStatusEnum::ACTIVE ? 'checked' : ''}}/>
                                        <label class="form-check-label" for="inlineRadio1">Active</label>
                                    </div>
                                    <div class="form-check radio-inline">
                                        <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="{{\App\Enum\Payments\SlotStatusEnum::INACTIVE->value}}" {{ $slot->status == \App\Enum\Payments\SlotStatusEnum::INACTIVE ? 'checked' : ''}} />
                                        <label class="form-check-label" for="inlineRadio2">In-active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Slot Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="title" name="title" placeholder="Slot Title" value="{{ $slot->title }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Slot Slug</label>
                                    <input type="text" autocomplete="off" class="form-control" id="slug" name="slug" placeholder="Slot Slug" value="{{ $slot->slug }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Slot Number</label>
                                    <input type="number" min="1" autocomplete="off" class="form-control" id="good_number" name="good_number" value="{{ $slot->good_number }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="">Slot For</label>
                                <select name="good_type" id="good_type"
                                        class="form-control">
                                    <option disabled selected readonly>Choose Type</option>
                                    @foreach (\App\Enum\Payments\SlotGoodTypeStatusEnum::cases() as $type)
                                        <option value="{{ $type->value }}" {{ $slot->good_type->value == $type->value ? 'selected': '' }}>
                                            {{ $type->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Price</label>
                                    <input type="number" min="1" autocomplete="off" class="form-control" id="price" name="price" value="{{ $slot->price }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Description</label>
                                    <textarea class="form-control" id="description" name="description">{{ $slot->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-block">Save</button>
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
@include('admin.scripts.slot')
@endsection
