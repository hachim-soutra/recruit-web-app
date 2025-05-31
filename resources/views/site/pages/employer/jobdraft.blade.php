@extends('site.layout.app')
@section('title', 'Draft Job')
@section('mystyle')
<style>
	.pagination{
		width: 100%;
	}
	.publishedbtn{
		background: url("{{asset('frontend/images/send_black-icon.svg')}}") no-repeat left 20px center #009688 !important;
	}
	.expirejob{
		color: #ed1c24 !important;
		font-size: 16px !important;
		font-weight: bolder !important;
		border: 1px solid;
		border-radius: 9px;
		width: 100px;
		text-align: center;
	}
</style>
@endsection
@section('content')
<script>
	var expired_or_not = false;
</script>
@if(@$data['firstJobDetail']->job_expiry_date < Carbon::now())
<script>
	expired_or_not = true;
</script>
@else
<script> expired_or_not = false; </script>
@endif
<!-- banner-block -->	

<!-- post-resume-block -->
	<div class="post-resume-one">
		<div class="container">
			<div class="bd-block">
				<div class="row">   
                    <div class="col-lg-12">
                        <h4>Draft Jobs</h4>
                    </div>
					<!-- item lt-->
					<div class="col-lg-5">
						@if(!empty($data['jobDraft']) && count($data['jobDraft']) > 0)
							@foreach($data['jobDraft'] as $jd)
							<!-- item-col 1-->
								<div class="item-col <?php if($jd->id == @$data['firstJobDetail']->id)echo 'active-div';?>" onclick="showJobPost('<?php echo $jd->id?>')">
									<!--top-->
									<div class="top">
										<div class="lt">
											<h3>{{ $jd->job_title }}</h3>
											<span>{{ $jd->CompanyName }}</span>
										</div>
									</div>
									<!--top-->
									<!-- middle -->
									<div class="middle">
										<div class="lt">
											<span>{{ $jd->job_location }}</span>
											<p>{{ $jd->preferred_job_type }}</p>
										</div>
										<div class="rt">
											<div class="img-sec">
												<img src="{{ $jd->company_logo }}">
											</div>
										</div>
									</div>
									<!-- middle -->
									<!-- bottom -->
									<div class="bottom">
										<p>{{$jd->salary_currency}}{{$jd->salary_from}} - {{$jd->salary_currency}}{{$jd->salary_to}} {{$jd->salary_period}}</p>
										@if($jd->job_expiry_date < Carbon::now())
										<span class="expirejob">Job Expired</span>
										@endif
										<span><b>Posted :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime($jd->created_at))->diffForHumans() }}</span>
									</div>
									<!-- bottom -->
								</div>
							<!-- item-col -->
							@endforeach
							{{ $data['jobDraft']->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
						@else
							<p>No Drft Job Found.</p>
						@endif
					</div>
					<!-- item -->
					@if(!empty($data['firstJobDetail']))
					<!-- item rt-->
					<div class="col-lg-7">
						<!---->
						<div class="item-tr-sec">
							<!---->
							<div class="item-col">

								<div class="top">
									<div class="figure">
										<img src="{{ @$data['firstJobDetail']->company_logo }}">
									</div>
									<p>{{ $data['firstJobDetail']->company_name }}</p>
									<h4>{{ $data['firstJobDetail']->job_title }}</h4>
                                    <?php $jobid = $data["firstJobDetail"]->id; ?>
									<input type="button" class="publishedbtn" value="Published" name="" onclick="published('{{ $jobid }}', 'check')">
								</div>

								<div class="text">
									<div class="lt">
										<span>{{ $data['firstJobDetail']->salary_period }}</span>
										<p>{{ $data['firstJobDetail']->salary_currency }}{{ $data['firstJobDetail']->salary_from }} - {{ $data['firstJobDetail']->salary_currency }}{{ $data['firstJobDetail']->salary_to }} {{ $data['firstJobDetail']->salary_period }}</p>
									</div>

									<div class="rt">
										<span><b>Posted :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime($data['firstJobDetail']->created_at))->diffForHumans() }}</span>
									</div>
								</div>

							</div>
							<!---->

							<!---->
							<div class="item-col-txt">

								<div class="item location-sec">
									<span>Job location</span>
									<p>{{ $data['firstJobDetail']->job_location }}</p>
								</div>



								<div class="item job-titlec">
									<h5>Job Title</h5>
									<p>{{ $data['firstJobDetail']->job_title }}</p>
								</div>

								<div class="item job-desc">
									<h5>Job Descriptions</h5>
									<p>{!! nl2br(preg_replace('/(\r\n|\n|\r)/','<br/>',$data['firstJobDetail']->job_details)) !!}</p>
								</div>

								<div class="item job-titlec">
									<h5>Qualifications</h5>
									<?php
										$replace = str_replace("[", ' ', str_replace("]", ' ', $data['firstJobDetail']->qualifications));
										$qualification_explode = explode('|', $replace);
									?>
									<?php for($i = 0;$i < count($qualification_explode);$i++): ?>
										<p>{{ str_replace('"', ' ', $qualification_explode[$i]) }}</p>
									<?php endfor; ?>
								</div>

								<div class="item job-desc">
									<h5>Skills</h5>
									<?php
										$skill_explode = array();
										$replace = str_replace("[", ' ', str_replace("]", ' ', $data['firstJobDetail']->job_skills));
										if(str_contains($replace, '|')){
											$skill_explode = explode('|', $replace);
										}
										if(str_contains($replace, ',')){
											$skill_explode = explode(',', $replace);
										}
									?>
									<ul>
										<?php for($i = 0;$i < count($skill_explode);$i++): ?>
											<li>{{ str_replace('"', ' ', $skill_explode[$i]) }}</li>
										<?php endfor; ?>
									</ul>
								</div>


								<div class="item job-desc">
									<h5>Experience</h5>
									<?php
										$replace = str_replace("[", ' ', str_replace("]", ' ', $data['firstJobDetail']->experience));
										$experience_explode = explode('|', $replace);
									?>
									<ul>
										<?php for($i = 0;$i < count($experience_explode);$i++): ?>
											<li>{{ str_replace('"', ' ', $experience_explode[$i]) }}</li>
										<?php endfor; ?>
									</ul>
								</div>



							</div>
							<!---->

						</div>
						<!---->
					</div>
					<!-- item -->
					@endif
				</div>
			</div>
		</div>
	</div>
	@if(!empty($data['firstJobDetail']))
	<!-- post-resume-block -->
	<div id="expireDateModal" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Expired Date Update</h4>
			</div>
			<div class="modal-body">
				<p>Job Title: {{ $data['firstJobDetail']->job_title }}. &nbsp;
				Job Expiration date: {{ $data['firstJobDetail']->job_expiry_date }}.</p>
				<div class="row">
					<div class="col-md-12">
						<label for=""><i class="fa fa-calendar"></i> Expire Date </label>
						<input type="date" min="{{ date('Y-m-d') }}" name="job_expire_date" id="job_expire_date" class="form-control">
					</div>
				</div>
				<input type="hidden" name="firstJob_id" id="firstJob_id" value="{{ $data['firstJobDetail']->id }}">
			</div>
			<div class="modal-footer">
				<button type="button" onclick="updateJobExpireDate()" class="btn btn-submit" style="background-color:#ed1c24;color:white;">Submit</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</div>

		</div>
	</div>
	@endif
@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
	function showJobPost(jobPostId){
		location.href = "{{ route('draft-job') }}"+'/'+jobPostId;
	}
    function published(jobid, publishtype){
		if(expired_or_not && publishtype == 'check'){
			$('#expireDateModal').modal('show');
			return false;
		}else{
			if(publishtype == 'check'){
				if(!confirm("Do You Want To Published This Job?")) return false;
			}			
			$.ajax({
				url: APP_URL + '/change-job-status',
				type: 'POST',
				data: {_token: '{{ csrf_token() }}', jobid: jobid, status: 'Published'},
				success: function(res){
					if(res.code == '200'){
						toastr.success(res.msg);
						setTimeout(() => {
							location.href="{{ route('draft-job') }}";
						}, 400);
					};
					if(res.code == '401'){
						toastr.error(res.msg);
					};
				},
				error: function(err){
					console.log(err);
				}
			});
		}        
	}

	function updateJobExpireDate(){
		let job_expire_date = $('#job_expire_date').val();
		let firstJob_id = $('#firstJob_id').val();
		$.ajax({
			url: APP_URL + '/change-job-expire-date',
			type: 'POST',
			data: {_token: '{{ csrf_token() }}', job_expire_date: job_expire_date, firstJob_id: firstJob_id},
			success: function(res){
				if(res.code == '200'){
					toastr.success(res.msg);
					setTimeout(() => {
						published(firstJob_id, 'continue');
					}, 1000);					
				};
				if(res.code == '401'){
					toastr.error(res.msg);
				};
			},
			error: function(err){
				console.log(err);
			}
		});
	}
</script>
@endsection