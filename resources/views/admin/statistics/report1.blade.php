@extends('admin.layout.app')

@section('title', "Report 1")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Report 1: Job Applications
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="">Reports</li>
		<li class="active">Report 1</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="box">
        <div class="row">
            <div class="box-body">
                <div class="col-md-8 col-xs-12">
                    <form class="form-inline" method="GET" action="">

						<div class="form-group" style="margin-right:20px;">
							<!--<label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>-->
							<div class="input-group">
							
								<select id="provider_id" name="provider_id" class="form-control">
									<option selected disabled>-- Select a Company --</option>
									@forelse ($providers as $e)
										<?php $isSelected=($e->user_id == $employer_id)?'selected':''; ?>
										<option {{ $isSelected }} value="{{ $e->user_id }}">
										    {{ $e->company_name }}
										</option>
									@empty
										ERROR: Unable to fetch the list!
									@endforelse
								</select>
							</div>
							
							&nbsp;&nbsp; OR &nbsp;&nbsp;
							
							<div class="input-group">
								<select id="employer_id" name="employer_id" class="form-control">
									<option selected disabled>-- Select a Client --</option>
									@forelse ($employers as $e)
										<?php $isSelected=($e->user_id == $employer_id)?'selected':''; ?>
										<option {{ $isSelected }} value="{{ $e->user_id }}">
										    {{ $e->name }}
										</option>
									@empty
										ERROR: Unable to fetch the list!
									@endforelse
								</select>
							</div>
							
							<!--<hr /><?php //print_r($employers[0]); ?>-->
							
							<script>
							jQuery(document).ready(function($) {
                                alert('hi');
                            });
							</script>
							
						</div>
						
						<button type="submit" class="btn btn-primary">Submit</button>
                        
                    </form>
                </div>
                <div class="col-md-4 col-xs-12">
					<!--
                    <div class="btn-group">
                        <a href="{{ route('admin.candidate.list', 'active') }}" class="btn bg-purple btn-flat btn-sm">
                            Active Candidates
                        </a>
                        <a href="{{ route('admin.candidate.list', 'archived') }}" class="btn bg-navy btn-flat btn-sm">
                            Archive Candidates
                        </a>
                    </div>
					-->
					<!--
					<form method="GET" action="">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control pull-right" placeholder="Search Employer" value="{{ (isset($request->keyword)) ? $request->keyword : null }}">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
					-->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
				<!--
                <div class="box-header">
                    <h3 class="box-title">Candidates List</h3>
                </div>
				-->
                <div class="box-body table-responsive no-padding">
					@if(count($data) > 0)
                    <table class="table table-hover">
                        <tr>
                            <th style="width:60px;text-align:center;">Job ID</th>
                            <th>Job Title</th>
							<th style="width:140px;text-align:center;">Total Applications</th>
                        </tr>
                        @forelse ($data as $d)
                            <tr>
								<td style="text-align:center;">{{ $d->job_id }}</td>
								<td>
									<a target="_blank" href="{{ route('admin.job.show', $d->job_id) }}">{{ $d->job_title }}</a>
								</td>
								<td style="text-align:center;">
									@if($d->total_apply > 0)
										<a target="_blank" href="{{ route('admin.job.applicants', $d->job_id) }}">{{ $d->total_apply }}</a>
									@else
										{{ $d->total_apply }}
									@endif
								</td>
							</tr>
                        @empty
                            <tr>
                                <td>No Record Found</td>
                            </tr>
                        @endforelse
                    </table>
					@endif
                    <div class="mt-5">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('myscript')

<script>
jQuery(document).ready(function($) {
    
    $('select#provider_id').on('change', function() {
        $('select#employer_id').val(this.value);
        //alert( this.value );
    });
    
    $('select#employer_id').on('change', function() {
        $('select#provider_id').val(this.value);
        //alert( this.value );
    });
    
    //alert('hi');
});
</script>

@endsection