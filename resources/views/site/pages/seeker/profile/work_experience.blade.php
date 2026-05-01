<div id="work-experience" class="tabcontent active" style="display:none;">
    <div id="candidateWorks">
        @foreach ($data['candidateWorks'] as $work)
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <p class="mb-0">
                            {{ $work->job_title  }} | {{ $work->company  }}
                        </p>
                        <div class="d-flex flex-row align-items-center">
                            <span class="text-info" onclick="handleEditClicked({{$work}})"><i class="fa fa-pencil"></i></span>
                            <span class="mx-3 text-danger"><i class="fa fa-trash" onclick="handleDeleteClicked({{$work}})"></i></span>
                            <span data-toggle="collapse" data-target="#collapse-{{$work->id}}"><i class="fa fa-caret-down"></i></span>
                        </div>
                    </div>

                </div>
                <div id="collapse-{{$work->id}}" class="collapse" aria-labelledby="headingTwo" data-parent="#candidateWorks">
                    <div class="card-body">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <h5 class="card-title">{{$work->job_title}}</h5>
                            <p>
                                {{ date('jS M, y', strtotime($work->from_date)) }} -
                                @if ($work->currently_work_here != '1')
                                    {{ date('jS M, y', strtotime($work->end_date)) }}
                                @else
                                    Currently Working
                                @endif
                            </p>
                        </div>
                        <div class="d-flex flex-row align-items-start">
                            <i class="fa fa-building mr-1"></i>
                            <span><strong>Company:</strong> {{$work->company}}</span>
                        </div>
                        <div class="d-flex flex-column mt-2">
                            <strong>Details:</strong>
                            <p class="card-text">{{$work->details}}</p>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-md-12 addsection_workexp">
            <form action="{{ route('profile-work-experience') }}" id="workExpForm" method="post">
                <div class="row my-4">
                    <div class="col-12">
                        <div class="d-flex flex-row justify-content-between">
                            <div class="d-flex flex-column align-items-start justify-content-start">
                                <h4>Work experience</h4>
                                <span>Share details about jobs you've worked</span>
                            </div>
                            <div>
                                <button type="submit" id="work-experience-add-button" class="btn btn-danger btn-cs mr-1"><i
                                        class="fa fa-send-o"></i> Add
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-cs" onclick="resetWorkForm()">
                                    X Cancel
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Job Role</label>
                        <input type="text" name="job_role" value="{{ old('job_role') }}" class="form-control"
                               placeholder="Your Job Role." id="job_role">
                    </div>
                    <div class="col-md-6">
                        <label for="">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-control"
                               placeholder="Previous Company Name." id="company_name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Company Address</label>
                        <textarea name="company_address" value="{{ old('company_address') }}" id="company_address"
                                  class="form-control"
                                  placeholder="Enter Company Address."></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Currently Working Here</label>&nbsp;&nbsp;
                        <label class="mr-3">
                            <input type="radio" class="current_work_here_yes"
                                   onclick="currentlyWorking(this.value)" name="current_work_here"
                                   id="current_work_here_yes" value="1">Yes
                        </label>

                        <label class="mr-3">
                            <input type="radio" class="current_work_here_no"
                                   onclick="currentlyWorking(this.value)" name="current_work_here"
                                   id="current_work_here_no" value="0">No
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="">From Date</label>
                        <input type="date" max="<?php echo date('Y-m-d'); ?>" name="form_date"
                               value="{{ old('form_date') }}" class="form-control" id="form_date">
                    </div>
                    <div class="col-md-6">
                        <label for="">To Date</label>
                        <input type="date" onclick="toDateGtFromDate()"
                               onchange="removedDate()" name="to_date" value="{{ old('to_date') }}"
                               class="form-control" id="to_date">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Details</label>
                        <textarea name="company_detail" value="{{ old('company_detail') }}"
                                  id="company_detail" class="form-control"
                                  placeholder="Enter Some Details About Your Previous Company.">{{ old('company_detail') }}</textarea>
                    </div>
                </div>
                <input type="hidden" name="exprowid" value="{{ old('exprowid') }}"
                       id="exprowid">
                <input type="hidden" name="deleteexprowid" value="{{ old('exprowid') }}"
                       id="deleteexprowid">
            </form>
        </div>
    </div>
</div>

<script>
    function handleEditClicked(work) {
        $('#exprowid').val(work.id);
        $('#job_role').val(work.job_title);
        $('#company_name').val(work.company);
        $('#company_address').val(work.address);
        $('#form_date').val(normalizeDate(work.from_date, 'start'));
        $('#to_date').val(normalizeDate(work.end_date, 'end'));
        $('#company_detail').val(work.details);
        if (work.currently_work_here) {
            $('#current_work_here_yes').prop('checked', true);
        } else {
            $('#current_work_here_no').prop('checked', true);
        }
        $('#work-experience-add-button').contents().filter(function () {return this.nodeType === 3;}).first().replaceWith(' Update');
    }

    function resetWorkForm() {
        $('#exprowid').val('');
        $('#deleteexprowid').val('');
        $('#job_role').val('');
        $('#company_name').val('');
        $('#company_address').val('');
        $('#form_date').val('');
        $('#to_date').val('');
        $('#company_detail').val('');
        $('#current_work_here_yes').prop('checked', false);
        $('#current_work_here_no').prop('checked', false);
        $('#work-experience-add-button').contents().filter(function () {return this.nodeType === 3;}).first().replaceWith(' Add');
    }

    function handleDeleteClicked(work) {
        if(!confirm("re you want to delete this experience ?")) {
            $('#deleteexprowid').val(null);
            return;
        }
        $('#deleteexprowid').val(work.id);
        $('#workExpForm').submit();
    }

    function normalizeDate(dateStr, type) {
        if (!dateStr) return '';
        if (dateStr.length === 10) return dateStr;
        const [y, m] = dateStr.split('-');
        if (type === 'start') {
            return `${y}-${m}-01`;
        }

        if (type === 'end') {
            const lastDay = new Date(y, m, 0).getDate();
            return `${y}-${m}-${lastDay}`;
        }
        return '';
    }
</script>
