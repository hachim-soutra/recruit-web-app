@extends('admin.layout.app')

@section('title', 'Admin Plans')

@section('mystyle')
    <style>
        .copy-link-button {
            cursor: pointer;
            font-size: 1.3rem;
            color: #EB1829;
        }

        .copy-link-button:hover {
            text-decoration: underline;
            color: #dd4b39;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Manage Inactive Subscriptions
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Inactive Subscriptions</li>
        </ol>
    </section>
    <section class="content">
        <div class="box">
            <div class="row">
                <div class="box-body">
                    <div class="col-md-4 col-xs-12">
                        <form method="GET" action="">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control pull-right" placeholder="Search"
                                    value="{{ isset($request->keyword) ? $request->keyword : null }}">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">All Inactive Subscriptions List</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th scope="col">Plan For</th>
                                <th scope="col">Plan Type</th>
                                <th scope="col">Title</th>
                                <th scope="col">Slot Number</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Company Name</th>
                                <th scope="col">Company Email</th>
                                <th scope="col" class="text-center">Payment Link</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                            @forelse ($data as $e)
                                <tr>
                                    <td>{{ $e->plan_package->plan->plan_for->value }}</td>
                                    <td>{{ $e->plan_package->plan->plan_type->value }}</td>
                                    <td>{{ $e->plan_package->plan->title }}</td>
                                    <td>{{ $e->plan_package->plan->job_number }}</td>
                                    <td>{{ $e->plan_package->number_of_month }} Months</td>
                                    <td>{{ $e->user && $e->user->employer ? $e->user->employer->company_name : '' }}</td>
                                    <td>{{ $e->user ? $e->user->email : '' }}</td>
                                    <td class="text-center">
                                        <a class="copy-link-button" data-id="{{ $e->payment_link }}">
                                            Copy link
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if ($e->status == \App\Enum\Payments\SubscriptionStatusEnum::WAITING)
                                            <a class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top"
                                                title="Not PAYED">
                                                <i class="fa fa-fw fa-check"></i> Not Payed
                                            </a>
                                        @elseif($e->status == \App\Enum\Payments\SubscriptionStatusEnum::PURCHASED)
                                            <a href="{{ route('admin.subscription.active_waiting_subscription', $e) }}"
                                                class="btn btn-sm btn-primary blockActivateSubscription"
                                                data-id="{{ $e->id }}" data-method="POST" data-toggle="tooltip"
                                                data-placement="top" title="ACTIVE">
                                                <i class="fa fa-fw fa-check"></i> Active Subscription
                                            </a>
                                        @else
                                            <a class="btn btn-sm btn-success blockIns" data-toggle="tooltip"
                                                data-placement="top" title="ACTIVATED">
                                                <i class="fa fa-fw fa-check"></i> Activated
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="9">No Data found</td>
                                </tr>
                            @endforelse
                        </table>
                        <div class="mt-5">
                            {{ $data->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('myscript')
    @include('admin.scripts.subscription')
@endsection
