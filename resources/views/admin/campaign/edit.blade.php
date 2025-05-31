@extends('admin.layout.app')

@section('title', 'Add campaigns')

@section('mystyle')
    <style>
        .nav>li>a {
            background-color: #f10c37;
        }

        .panel-tabs {
            display: flex;
            gap: 1.5rem;
        }

        .panel-tabs a {
            padding: 1rem 1.5rem;
            border: 1px solid black;
            color: black;
            border-radius: 12px;
            cursor: pointer;
        }

        .panel-tabs .active a {
            border: 1px solid #f10c37;
            background-color: #f10c37;
            color: #dddddd;
        }

        li {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Update campaign
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.campaign.index') }}">campaigns</a></li>
            <li class="active">Update</li>
        </ol>
    </section>
    <section class="content" id="app">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Update campaign
                        </h3>
                    </div>
                    <div class="box-body">
                        <form @submit.prevent="save" name="campaignCreateFrm" id="app">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="object">Subject :</label>
                                        <input type="text" class="form-control" id="object" v-model="form.object"
                                            name="object" placeholder="objet">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dateEnvoi">Date envoi :</label>
                                        <input type="datetime-local" class="form-control" id="dateEnvoi"
                                            v-model="form.dateEnvoi" name="dateEnvoi">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="industry">Sectur :</label>
                                        <select name="keyword" id="keyword" class="form-control" v-model="industry"
                                            @change="changeIndustry">
                                            <option value="Any" selected>Any</option>
                                            <option v-for="industry in industries" :value="industry.name">
                                                @{{ industry.name }}
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="job_location">Location :</label>
                                        <input type="text" placeholder="Location" name="job_location" id="job_location"
                                            class="form-control" v-model="location" @input="changeIndustry" />
                                        <input type="hidden" id="start_latitude" name="start_latitude" value="" />
                                        <input type="hidden" id="start_longitude" name="start_longitude" value="" />
                                    </div>
                                </div>
                                <div class="col-md-12 row m-0 h-100 mb-3" style="margin-bottom: 1rem">
                                    <div class="col-md-12">
                                        <label>Add custom recipient</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" v-model="email">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary btn-block"
                                            @click="addEmailCandidate(email)">add</button>
                                    </div>
                                    <br />
                                    <br />
                                    <br />
                                    <br />
                                    <div class="col-md-6 ">
                                        <label>recipients ( @{{ form.selectedRecipients.length }})</label>
                                        <hr style="margin: 0px">
                                        <ul class="h-100" style="max-height: 300px; overflow:auto">
                                            <li v-for="candidate in form.selectedRecipients" style="padding: 5px 0px"
                                                @click="removeCandidate(candidate)" :key="candidate"
                                                v-html="candidate">
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <label>candidates( @{{ candidates.length }})</label>
                                        <hr style="margin: 0px">
                                        <ul class="h-100" style="max-height: 300px; overflow:auto">
                                            <li style="padding: 5px 0px" v-for="candidate in candidates"
                                                @click="addEmailCandidate(candidate.email)">
                                                @{{ candidate.name }} (@{{ candidate.email }})
                                                <span
                                                    v-if="candidate.candidate
                                                && candidate.candidate.alerts && candidate.candidate.alerts[0]
                                                !==undefined">
                                                    <i class="fa fa-location-arrow" aria-hidden="true"></i>
                                                    @{{ candidate.candidate?.alerts[0].job_location ?? 'Any' }}
                                                    <i class="fa fa-industry" aria-hidden="true"></i>
                                                    @{{ candidate.candidate?.alerts[0].industry ?? 'Any' }}
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                                <div class="col-md-12 row m-0 h-100">
                                    <div class="col-md-6 ">
                                        <label>Selected jobs( @{{ form.selectedJobs.length }})
                                        </label>
                                        <hr style="margin: 0px">
                                        <ul class="h-100"
                                            style="max-height: 300px; overflow:auto;list-style: none;padding-left: 0;">

                                            <li style="padding: 10px 0px" v-for="job in selectedJobs"
                                                style="padding: 5px 0px" @click="removeJob(job.id)"
                                                :key="job.id">
                                                <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                                @{{ job.job_title }} (@{{ job.company_name }}) <i
                                                    class="fa fa-location-arrow" aria-hidden="true"></i>
                                                @{{ job.job_location }}
                                                <i class="fa fa-industry" aria-hidden="true"></i>
                                                @{{ job.functional_area }}
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <label>jobs( @{{ jobs.length }})</label>
                                        <hr style="margin: 0px">
                                        <ul class="h-100"
                                            style="max-height: 300px; overflow:auto;list-style: none;padding-left: 0;">
                                            <li style="padding: 10px 0px" v-for="job in jobs" style="padding: 5px 0px"
                                                @click="selectJob(job)" :key="job.id">
                                                <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                                @{{ job.job_title }} (@{{ job.company_name }}) <i
                                                    class="fa fa-location-arrow" aria-hidden="true"></i>
                                                @{{ job.job_location }}
                                                <i class="fa fa-industry" aria-hidden="true"></i>
                                                @{{ job.functional_area }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Title :</label>
                                        <textarea v-model="form.title" name="title" id="title" class="form-control" style="width: 100%;"
                                            rows="2" cols="3"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description :</label>
                                        <textarea v-model="form.description" name="description" id="description" class="form-control" style="width: 100%;"
                                            rows="6" cols="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success btn-block">Update</button>
                                    <button type="button" @click="preview" class="btn btn-primary btn-block">Send test
                                        mail</button>
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
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                form: {
                    object: "{{ $campaign->object }}",
                    dateEnvoi: "{{ $campaign->date_envoi->format('Y-m-d H:i') }}",
                    selectedRecipients: [],
                    selectedJobs: [],
                    title: "{{ $campaign->title }}",
                    description: "{{ $campaign->description }}"
                },
                location: null,
                industry: null,
                industries: [],
                email: null,
                candidatesOriginal: [],
                jobsOriginal: [],
            },
            mounted() {
                this.fetchCampaign();
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
                async fetchCampaign() {
                    let self = this;
                    let url = "{{ route('admin.campaign.info', ['id' => $campaign->id]) }}";
                    await $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(res) {
                            self.form.selectedJobs = res.data.jobs.map((j) => parseInt(j));
                            self.form.selectedRecipients = res.data.recipients;
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
                addEmailCandidate(email) {
                    var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
                    if (!email.match(validRegex)) {
                        toastr.error('Email invalid');
                        return
                    }
                    if (this.form.selectedRecipients.includes(email)) {
                        toastr.error('Email areday exsits');
                        return
                    }
                    this.form.selectedRecipients.push(email);
                    this.email = "";
                },
                removeCandidate(email) {
                    this.form.selectedRecipients = this.form.selectedRecipients.filter((em) => em != email);
                },
                selectJob(job) {
                    this.form.selectedJobs.push(job.id);
                },
                removeJob(id) {
                    this.form.selectedJobs = this.form.selectedJobs.filter((em) => em != id);
                },
                async preview() {
                    let self = this;
                    let url = "{{ route('admin.campaign.preview') }}";
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
                        },
                        error: function(xhr) {
                            for (const [key, value] of Object.entries(xhr.responseJSON.errors)) {
                                toastr.error(value);
                            }
                        }
                    });
                },
                async save() {
                    let self = this;
                    let id = {{ $campaign->id }};

                    let url = "{{ route('admin.campaign.update', ['id' => $campaign->id]) }}";
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
                            window.location = res.data.url;
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
                candidates() {
                    return this.candidatesOriginal.filter(candidate => {
                        return !this.form.selectedRecipients
                            .includes(candidate.email.toLowerCase())
                    })
                },
                jobs() {
                    return this.jobsOriginal.filter(job => {
                        return !this.form.selectedJobs
                            .includes(job.id)
                    })
                },
                selectedJobs() {
                    return this.jobsOriginal.filter(job => {
                        return this.form.selectedJobs
                            .includes(job.id)
                    })
                },
            }
        });
    </script>
@endsection
