<div class="col-md-12">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="list-group">
                <span class="list-group-item visitor">
                    <h3 class="pull-right">
                        <i class="fa fa-eye"></i>
                    </h3>
                    <h4 class="list-group-item-heading count">
                        {{ $slots->sum('good_number') + $package->plan->job_number }}</h4>
                    <p class="list-group-item-text">
                        Total job posts</p>
                </span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="list-group">
                <span class="list-group-item visitor">
                    <h3 class="pull-right">
                        <i class="fa fa-eye"></i>
                    </h3>
                    <h4 class="list-group-item-heading count">
                        {{ $subscriptionActive->jobPosts->count() }}</h4>
                    <p class="list-group-item-text">
                        Consumed job posts</p>
                </span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="list-group">
                <span
                    class="list-group-item visitor {{ auth()->user()->available_jobs_number > 1 ? 'text-success' : 'text-danger' }}">
                    <h3 class="pull-right">
                        <i class="fa fa-eye"></i>
                    </h3>
                    <h4 class="list-group-item-heading count">
                        {{ auth()->user()->available_jobs_number }}</h4>
                    <p class="list-group-item-text">
                        Remaining job posts</p>
                </span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="list-group">
                <span
                    class="list-group-item visitor {{ $subscriptionActive->estimated_end_date->diffInWeeks() > 1 ? 'text-success' : 'text-danger' }}">
                    <h3 class="pull-right">
                        <i class="fa fa-date"></i>
                    </h3>
                    <h4 class="list-group-item-heading count">
                        Plan expiry date
                    </h4>
                    <p class="list-group-item-text">
                        {{ $subscriptionActive->estimated_end_date->diffForHumans() }}
                    </p>
                </span>
            </div>
        </div>
    </div>

    <h5 class="mb-3">
        <a href="#!" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>
            Active Plan Details ({{ $subscriptionActive->slot_with_period }} / €{{ $subscriptionActive->amount }})
        </a>
    </h5>
    <hr>
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between flex-column flex-md-row">
                <div class="d-flex flex-row align-items-center justify-content-around w-100 w-md-25">
                    <div class="ms-3 w-100">
                        <h5>{{ $package->plan->title }}</h5>
                        <p class="m-0">{{ $package->plan->slug }}</p>
                        <p class="m-0">Start date: {{ $subscriptionActive->created_at->format('d M Y') }}</p>
                        <p class="m-0">End date: {{ $subscriptionActive->estimated_end_date->format('d M Y') }}
                        </p>
                    </div>
                </div>
                <div class="d-flex flex-row align-items-center justify-content-center w-100 w-md-50 mt-2 mt-md-0">
                    <div class="ms-3">
                        <h5 class="text-center">
                            {{ $package->plan->slot_description }}<br />
                            valid until <br />
                            {{ $subscriptionActive->slot_with_period }}
                        </h5>
                    </div>
                </div>
                <div class="d-flex flex-row align-items-center justify-content-end w-100 w-md-25 justify-md-content-end mt-2 mt-md-0">

                    <div style="width: 80px;">
                        <h5 class="mb-0">€{{ $package->price }}</h5>
                    </div>
                    <a href="#!" style="color: #cecece;"><i class="fas fa-trash-alt"></i></a>
                </div>
            </div>
        </div>
    </div>
    @if (count($slots) > 0)
        <h5 class="mb-3">
            <a class="text-body" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                aria-controls="collapseExample"><i class="fas fa-long-arrow-alt-left me-2"></i>
                Jobs Details ({{ $slots->sum('good_number') }})
                <i class="fa fa-solid fa-caret-down"></i>
            </a>
        </h5>
        <hr>
        <div class="collapse" id="collapseExample">
            @foreach ($slots as $slot)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center w-25">
                                <div class="ms-3">
                                    <h5>{{ $slot->title }}</h5>
                                    <p class="m-0">{{ $slot->created_at->format('d M Y') }}</p>

                                </div>
                            </div>
                            <div class="d-flex flex-row align-items-center w-50">
                                <div class="ms-3">
                                    <h5>{{ $slot->label }}</h5>
                                </div>
                            </div>
                            <div class="d-flex flex-row align-items-center w-25 justify-content-end">

                                <div style="width: 80px;">
                                    <h5 class="mb-0">€{{ $slot->price }}</h5>
                                </div>
                                <a href="#!" style="color: #cecece;"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-row align-items-center w-25">
                            <div class="ms-3">
                                Total:
                            </div>
                        </div>
                        <div class="d-flex flex-row align-items-center w-50">
                            <div class="ms-3">
                                <h5>{{ $slots->sum('good_number') + $package->plan->job_number }}</h5>
                            </div>
                        </div>
                        <div class="d-flex flex-row align-items-center w-25 justify-content-end">

                            <div style="width: 80px;">
                                <h5 class="mb-0">€{{ $subscriptionActive->amount }}</h5>
                            </div>
                            <a href="#!" style="color: #cecece;"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
