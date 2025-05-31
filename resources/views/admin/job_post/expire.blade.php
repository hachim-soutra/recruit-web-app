@extends('admin.layout.app')

@section('title', "Expired Job")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Job Post
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Job Post</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="row">
            <div class="box-body">
                <div class="col-md-4 col-xs-12">
                    <form method="GET" action="">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control pull-right" placeholder="Search" value="{{ (isset($request->keyword)) ? $request->keyword : null }}">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 col-xs-12">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All Expired Job List</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Employer</th>
                            <th scope="col">Job Location</th>
                            <th scope="col">Industry</th>
                            <th scope="col" class="text-center">Skills</th>
                            <th scope="col" class="text-center">Expiry Status</th>
                            <th scope="col" class="text-center">Job Status</th>
                            <th scope="col">Action</th>
                        </tr>
                        @forelse ($data as $e)
                        <tr>
                            <td id="job_title_{{$e->id}}">{{ $e->job_title }}</td>
                            <td>{{ $e->employer->name }}</td>
                            <td>{{ $e->job_location }}</td>
                            <td>{{ $e->functional_area }}</td>
                            <td>
                                @foreach (json_decode($e->job_skills) as $member)
                                    {{ $member }}
                                @endforeach
                            </td>
                            <td>
                                @if ($e->job_expiry_date> date('Y-m-d'))
                                    <span>Job Active</span>
                                @else
                                    <span  data-toggle="tooltip" data-placement="bottom" title="To active this job upgrade expiry date">
                                        Job Expired
                                    </span>
                                @endif
                            </td>
                            <td>{{ ($e->job_status == "Save as Draft")? "Save As Drft" : "Published" }}</td>
                            <td>
                                <a href="javascript:void(0);" onclick="updateExpireJob(this)" data-employer_name="{{$e->employer->name}}" data-job_expire_date="{{$e->job_expiry_date}}" data-row_id="{{ $e->id }}"><i class="fa fa-calendar"></i></a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td class="text-center">No Data found</td>
                            </tr>
                        @endforelse
                    </table>
                    <div class="mt-5">
                        {{ $data->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- modal  -->
<div id="job_expire_date_update" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Job Info</h4>
      </div>
      <form id="expire_job_adte_change_form">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-9">
                    <label>Job Title</label>
                    <p id="changed_job_title"></p>
                </div>
                <div class="col-md-3">
                    <label for="">Job Expired Date</label>
                    <p id="changed_job_expire_date"></p>
                </div>
            </div>        
            <div class="row">
                <div class="col-md-12">
                    <label for="">New Date</label>
                    <input type="date" name="expire_date_new" id="expire_date_new" class="form-control">
                </div>
            </div> 
            <input type="hidden" name="rowid" id="rowid">       
            <input type="hidden" name="empname" id="empname">       
        </div>
        <div class="modal-footer">
            <button type="button" onclick="dateChange()" class="btn btn-primary"><i class="fa fa-send-o"></i>Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection

@section('myscript')
<script>
    function updateExpireJob(element){
        let rowid = $(element).attr('data-row_id');
        let job_expire_date = $(element).attr('data-job_expire_date');
        let jobtitile = $('#job_title_'+rowid).html();
        let employer_name = $(element).attr('data-employer_name');
        $('#changed_job_title').html(jobtitile);
        $('#changed_job_expire_date').html(job_expire_date);
        $('#rowid').val(rowid);
        $('#empname').val(employer_name);
        $('#job_expire_date_update').modal('show');
    }

    function dateChange(){
        let employer_name = $('#empname').val();
        Swal.fire({  
            title: 'Do you want to submit? If yes, then '+employer_name+"'s job ballance decreased, and this job goes to published.",  
            showDenyButton: true,  showCancelButton: true,  
            confirmButtonText: `Go`,  
            denyButtonText: `Stay`,
        }).then((result) => {   
            if(result.value){   
                dateUpdatePublishJob();
            }else{   
                $('#job_expire_date_update').modal('hide'); 
                return false; 
            }
        });
    }

    function dateUpdatePublishJob(){
        let date = $('#expire_date_new').val();
        let rowid = $('#rowid').val();
        $.ajax({
            type: "POST",
            url: "{{ route('admin.job.expire-job-update') }}",
            data: {'_token': '{{ csrf_token() }}', job_expire_date: date, rowid: rowid},
            dataType: "json",
            beforeSend: function() {
                loderStart();
            },
            complete: function() {},
            success: function(res) {
                loderStop();
                if(res.code == '200'){
                    toastr.success(res.msg);
                    $('#job_expire_date_update').modal('hide');
                    $('#expire_date_new').val('');
                    location.reload();
                }
                if(res.code == '403'){
                    if(res.msg.hasOwnProperty('rowid')){
                        toastr.error("Refresh the page please.");
                    }
                    if(res.msg.hasOwnProperty('job_expire_date')){
                        for(let i = 0;i < res.msg.job_expire_date.length;i++){
                            toastr.error(res.msg.job_expire_date[i]);
                        }
                    }
                    $('#job_expire_date_update').modal('show');
                }
                if(res.code == '401'){
                    toastr.info(res.msg);
                    $('#job_expire_date_update').modal('hide');
                    $('#expire_date_new').val('');
                }
            },
            error: function(error) {
                loderStop();
                console.log(error);
                $('#job_expire_date_update').modal('hide');
                $('#expire_date_new').val('');
            }
        });
    }
</script>
@include('admin.scripts.job')
@endsection
