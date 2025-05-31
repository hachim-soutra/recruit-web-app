@extends('admin.layout.app')

@section('title', 'Add Plans')

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
            Manage Plans
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.plan.list') }}">Plans</a></li>
            <li class="active">Create</li>
        </ol>
    </section>
    <section class="content" id="app">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Plans Add From</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ route('admin.plan.list') }}" class="btn btn-box-tool">
                                <i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('admin.plan.store') }}" method="POST" name="planCreateFrm"
                            id="planCreateFrm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Plan Title</label>
                                        <input type="text" autocomplete="off" class="form-control" id="title"
                                            name="title" placeholder="Plan Title">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Plan Slug</label>
                                        <input type="text" autocomplete="off" class="form-control" id="slug"
                                            name="slug" placeholder="Plan Slug">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="event_date">Number Of Month</label>
                                        <input type="number" min="1" max="12" autocomplete="off"
                                            class="form-control" id="number_of_month" name="number_of_month">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="event_date">Price</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control"
                                            id="price" name="price">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="event_date">Slot Number</label>
                                        <input type="number" min="1" autocomplete="off" class="form-control"
                                            id="job_number" name="job_number">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Plan For</label>
                                    <select name="plan_for" id="plan_for" onchange="chooseStateFromCountry(this)"
                                        class="form-control">
                                        <option disabled selected readonly>Choose Type</option>
                                        @foreach (\App\Enum\Payments\PlanForEnum::cases() as $type)
                                            <option value="{{ $type->value }}">
                                                {{ $type->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Plan Type</label>
                                    <select name="plan_type" id="plan_type" class="form-control">
                                        <option disabled selected readonly>Slot Type</option>
                                        @foreach (\App\Enum\Payments\PlanTypeStatusEnum::cases() as $type)
                                            <option value="{{ $type->name }}">
                                                {{ $type->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="event_date">Description</label>
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="box">
                                        <div class="box-body table-responsive no-padding table-wrap">
                                            <table class="table table-hover" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>*</th>
                                                        <th>name</th>
                                                        <th>email</th>
                                                        <th>company</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($employers as $emp)
                                                        <tr>
                                                            <td scope="row">
                                                                <input type="checkbox" name="employers[]"
                                                                    value="{{ $emp->id }}">
                                                            </td>
                                                            <td>{{ $emp->user->name }}</td>
                                                            <td>{{ $emp->user->email }}</td>
                                                            <td>{{ $emp->company_name }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
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
    @include('admin.scripts.plan')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                form: {
                    object: "",
                    dateEnvoi: "",
                    selectedRecipients: [],
                    selectedJobs: [],
                    title: "",
                    description: ""
                },
                location: null,
                industry: null,
                industries: [],
                email: null,
                candidatesOriginal: [],
                jobsOriginal: [],
            },
            created() {
                this.fetchIndustries();
                this.fetchCandidates();
                this.fetchJobs();
            },
            methods: {
                async fetchIndustries() {
                    let self = this;
                    let url = "{{ route('industries.index') }}";
                    await $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(res) {
                            self.industries = res.data;
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                },
                async fetchCandidates() {
                    let self = this;
                    let url = "{{ route('candidates.index') }}";
                    await $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(res) {
                            self.candidatesOriginal = res.data;
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                },
                async fetchJobs() {
                    let self = this;
                    let url = "{{ route('jobs.index') }}";
                    await $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(res) {
                            self.jobsOriginal = res.data;
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                },
                async changeIndustry() {
                    let self = this;
                    let url = "{{ route('admin.campaign.filter') }}";
                    await $.ajax({
                        url: url,
                        type: 'Post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            location: self.location,
                            industry: self.industry
                        },
                        success: function(res) {
                            console.log(res);
                            self.form.selectedJobs = res.data.jobs.map((j) => parseInt(j));
                            self.form.selectedRecipients = res.data.recipients;
                        }
                    });
                },

                selectItem(item) {},
                removeItem(id) {},

                async save() {
                    let self = this;
                    let url = "{{ route('admin.campaign.store') }}";
                    await $.ajax({
                        url: url,
                        type: 'Post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ...self.form,
                            selectedRecipients: self.form.selectedRecipients,
                            selectedJobs: self.form.selectedJobs
                        },
                        success: function(res) {
                            toastr.success(res.data.message);
                            // window.location = res.data.url;
                        },
                        error: function(xhr) {
                            for (const [key, value] of Object.entries(xhr.responseJSON.errors)) {
                                toastr.error(value);
                            }
                        }
                    });
                },
            },
            computed: {

            }
        });
    </script>
@endsection
