<form wire:submit.prevent="save" name="campaignCreateFrm">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="object">Subject :</label>
                <input type="text" autocomplete="off" class="form-control @error('object') is-invalid @enderror"
                    id="object" wire:model="object" name="object" placeholder="objet">
                @error('object')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="date_envoi">Date envoi :</label>
                <input type="datetime-local" class="form-control @error('dateEnvoi') is-invalid @enderror"
                    id="date_envoi" name="date_envoi" wire:model="dateEnvoi" placeholder="Date envoi">
                @error('dateEnvoi')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="date_envoi">Sectur :</label>
                <select name="keyword" id="keyword" class="form-control" wire:model="industry"
                    wire:change="changeIndustry">
                    <option value="Any" selected>Any</option>
                    @foreach ($industries as $industry)
                        <option value="{{ $industry->name }}">{{ $industry->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="job_location">Location :</label>
                <input type="text" placeholder="Location" name="job_location" id="job_location" class="form-control"
                    wire:model="location" wire:change="changeIndustry" />
                <input type="hidden" id="start_latitude" name="start_latitude" value="" />
                <input type="hidden" id="start_longitude" name="start_longitude" value="" />
            </div>
        </div>
        <div class="col-md-12 row m-0 h-100 mb-3" style="margin-bottom: 1rem">
            <div class="col-md-12">
                <label for="">Add custom recipient</label>
            </div>
            <div class="col-md-6">
                <input class="form-control @error('email') is-invalid @enderror" wire:model="email">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary btn-block" wire:click="addEmailCandidate()">add</button>
            </div>
            <br />
            <br />
            <br />
            <br />
            <div class="col-md-6 ">
                <label>recipients ( {{ count($selectedRecipients) }}) @error('selectedRecipients')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </label>
                <hr style="margin: 0px">
                <ul class="h-100" style="max-height: 300px; overflow:auto">
                    @foreach ($selectedRecipients as $candidate)
                        <li style="padding: 5px 0px" wire:click="removeCandidate({{ $candidate }})"
                            wire:key="{{ $candidate }}">
                            {{ $candidate }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6">
                <label>candidates( {{ count($candidates) }})</label>
                <hr style="margin: 0px">
                <ul class="h-100" style="max-height: 300px; overflow:auto">
                    @foreach ($candidates as $candidate)
                        <li style="padding: 5px 0px" wire:click="addCandidate({{ $candidate }})"
                            wire:key="{{ $candidate->id }}">
                            {{ $candidate->name }} ({{ $candidate->email }})
                            @if (isset($candidate->candidate?->alerts[0]))
                                <i class="fa fa-location-arrow" aria-hidden="true"></i>
                                {{ $candidate->candidate?->alerts[0]->job_location ?? 'Any' }}
                                <i class="fa fa-industry" aria-hidden="true"></i>
                                {{ $candidate->candidate?->alerts[0]->industry ?? 'Any' }}
                            @endif

                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
        <br>
        <br>
        <div class="col-md-12 row m-0 h-100">
            <div class="col-md-6 ">
                <label>Selected jobs( {{ count($selectedJobs) }}) @error('selectedJobs')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </label>
                <hr style="margin: 0px">
                <ul class="h-100" style="max-height: 300px; overflow:auto;list-style: none;padding-left: 0;">
                    @foreach ($selectedJobsModel as $job)
                        <li style="padding: 10px 0px" wire:click="removeJob({{ $job->id }})"
                            wire:key="{{ $job->id }}">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i>
                            {{ $job->job_title }} ({{ $job->company_name }}) <i class="fa fa-location-arrow"
                                aria-hidden="true"></i> {{ $job->job_location }}
                            <i class="fa fa-industry" aria-hidden="true"></i> {{ $job->functional_area }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6">
                <label>jobs( {{ count($jobs) }})</label>
                <hr style="margin: 0px">
                <ul class="h-100" style="max-height: 300px; overflow:auto;list-style: none;padding-left: 0;">
                    @foreach ($jobs as $job)
                        <li style="padding: 10px 0px" wire:click="selectJob({{ $job->id }})"
                            wire:key="{{ $job->id }}">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            {{ $job->job_title }} ({{ $job->company_name }}) <i class="fa fa-location-arrow"
                                aria-hidden="true"></i> {{ $job->job_location }}
                            <i class="fa fa-industry" aria-hidden="true"></i> {{ $job->functional_area }}

                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="title">Title :</label>
                <textarea wire:model="title" name="title" id="title" class="form-control" style="width: 100%;"
                    rows="2" cols="3"></textarea>
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea wire:model="description" name="description" id="description" class="form-control" style="width: 100%;"
                    rows="6" cols="3"></textarea>
            </div>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-success btn-block">Save</button>
            <button type="button" wire:click="send" class="btn btn-primary btn-block">Send Compaign now</button>
            <button type="button" wire:click="preview" class="btn btn-primary btn-block">Send test mail</button>
        </div>
    </div>
</form>
