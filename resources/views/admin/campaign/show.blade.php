@extends('admin.layout.app')

@section('title', 'Campaign Details')

@section('mystyle')
    <style>
        .add-edu {
            margin-top: 25px;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Campaign details
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.campaign.index') }}">campaigns</a></li>
            <li class="active">Details</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Campaign Details</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ route('admin.campaign.index') }}" class="btn btn-box-tool">
                                <i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <livewire:campaigns.show-campaign-component :campaign="$campaign" />
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
