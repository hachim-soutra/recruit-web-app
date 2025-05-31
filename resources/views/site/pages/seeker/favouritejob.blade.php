@extends('site.layout.app')
@section('title', 'Favourite Jobs')
@section('mystyle')
<style>
	.heading{
		position: relative;
		width: 100%;
		min-height: 66px;
		padding-right: 15px;
		padding-left: 15px;
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
	.item-col:hover {
		border-color: #ed1c24 !important;
	}
</style>
@endsection
@section('content')

<!-- post-resume-block -->
	<div class="<?php if(count($data['bookmarks']) > 0)echo 'post-resume-one';else echo 'login-block';?>">
		<div class="container">
			<div class="bd-block">
				@if(Auth::user()->user_type === 'candidate')
				<!-- <div class="row">
					<div class="col-mg-12 heading" style="text-align: center;">
						<h4>Favourite Job</h4> <p><span>{{ $data['totalResult'] }}</span> Matching Results</p>						
					</div>
				</div> -->

				<div class="section-titel text-center mb-3 mb-lg-4">
                    <h2>FAVOURITE JOB</h2>
					<h5>{{ $data['totalResult'] }}</span> Matching Results</h5>
                  </div>

				<div class="row" style="display: flex;justify-content: center;">
					<!-- item lt-->
					<div class="col-lg-7">
						@forelse($data['bookmarks'] as $fevjob)
							@if($fevjob->jobs->job_expiry_date < Carbon::now())
							<div class="item-col" style="cursor: <?php if($fevjob->jobs->job_expiry_date < Carbon::now())echo 'not-allowed'; ?>">
							@else
							<div class="item-col" onclick="openjobDetail('{{ $fevjob->job_id }}')">
							@endif
								<!--top-->
								<div class="top">
									<div class="lt">
										<h3>{{ $fevjob->jobs->job_title }} 
											@if($fevjob->jobs->job_expiry_date < Carbon::now())
											<span><i class="fa fa-heart" style='color: #9e9e9e;margin-left:100%;'></i></span>
											@else
											<span><i class="fa fa-heart" style='color: red;margin-left:100%;'></i></span>
											@endif
										</h3>
										<span>{{ $fevjob->jobs->company_name }}</span> 
									</div>
								</div>
								<!--top-->
								<!-- middle -->
								<div class="middle">
									<div class="lt">
										<span>{{ $fevjob->jobs->job_location }}</span>
										<p>{{ $fevjob->jobs->preferred_job_type }}</p>
									</div>
									<div class="rt">
										<div class="img-sec">
											<img src="{{ $fevjob->jobs->company_logo }}">
										</div>
									</div>
								</div>
								<!-- middle -->
								<!-- bottom -->
								<div class="bottom">
									<p>{{$fevjob->jobs->salary_currency}}{{$fevjob->jobs->salary_from}} - {{$fevjob->jobs->salary_currency}}{{$fevjob->jobs->salary_to}} {{$fevjob->jobs->salary_period}}</p>
									@if($fevjob->jobs->job_expiry_date < Carbon::now())
									<span class="expirejob">Job Expired</span>
									@endif
									<span><b>Posted :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime($fevjob->created_at))->diffForHumans() }}</span>
								</div>
								<!-- bottom -->
							</div>
						@empty
						<h3 class="text-center" style="color:red;width: 100%;">No Data Found.</h3>
						@endforelse
						{{ $data['bookmarks']->appends(Request::except('page'))->onEachSide(1)->links("pagination::bootstrap-4") }}
					</div>
					<!-- item -->
				</div>
				@endif
			</div>
		</div>
	</div>
<!-- post-resume-block -->

@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
	function openjobDetail(jobid){
		location.href = "{{route('dashboard')}}" + '/' + jobid;
	}
</script>
@endsection