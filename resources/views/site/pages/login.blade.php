@extends('site.layout.app')
@section('title', 'Welcome')
@section('mystyle')
@endsection
@section('content')
<!-- banner-block -->

<!-- Page Loder start -->

<div class="page-loader" style="display:none;">
	<div class="spinner"></div>
</div>

<!-- Page Loder End -->


<!-- login-block -->
	<div class="login-block">
		<div class="container">
			<!-- =============== -->
				<!-- @if(session()->has('logout'))
					<script>
						toastr.success("{{session()->get('logout')}}");
					</script>
				@endif -->
				@if(session('success'))
					<script>
						toastr.success("{{session('success')}}");
					</script>
				@endif
				<div class="bd-block">
					<h4>Sign In Now</h4>
					<div class="item-sec">
						<nav>
							<div class="nav nav-tabs nav-fill btn-bd" id="nav-tab" role="tablist">
								<a class="nav-item nav-link " id="job-seeker-tab" data-toggle="tab" href="#job-seeker" role="tab" aria-controls="job-seeker" aria-selected="true">Job Seeker</a>
								<a class="nav-item nav-link active" id="employer-tab" data-toggle="tab" href="#employer-profile" role="tab" aria-controls="employer-profile" aria-selected="false">Employer</a>
								<a class="nav-item nav-link" id="career-coach-tab" data-toggle="tab" href="#career-coach" role="tab" aria-controls="career-coach" aria-selected="false">Career Coach</a>
							</div>
						</nav>
						<div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
							<!-- item-form-bd -->
								<div class="tab-pane fade " id="job-seeker" role="tabpanel" aria-labelledby="job-seeker-tab">
									<div class="form-bd">
										<form id="loginform">
											<!-- item -->
											<div class="item">
												<input type="text" placeholder="Email Address" id="candidate_email_id">
											</div>
											<!-- item -->
											<!-- item -->
											<div class="item">
												<input type="password" placeholder="Password" id="candidate_password">
											</div>
											<!-- item -->
											<!-- item -->
											<div class="item">
												<input type="button" value="Login"  data-usertype="candidate" onclick="formSubmit(this)">
											</div>
											<!-- item -->
											<!-- item -->
												<div class="item forgot-psw">
													<div class="cl-lt">
													<input type="checkbox" value="agree">
													<p>Remember me</p>
													</div>
													<!---->

													<div class="cl-rt">
													<a href="{{ route('forgot-password') }}">Forgot Password ?</a>
													</div>
													<!---->
												</div>
											<!-- item -->
											<div class="item-border">
												<p>or</p>
											</div>


											<!-- item -->
												<div class="register-sec">
													<!---->
													<p>Don't have an account yet?</p>
													<a href="{{ route('register') }}">Register</a>
													<!---->
												</div>
											<!-- item -->
										</form>
									</div>
								</div>
							<!-- item-form-bd -->
							<!-- item-form-bd -->
								<div class="tab-pane fade show active" id="employer-profile" role="tabpanel" aria-labelledby="employer-tab">
									<div class="form-bd">
										<form id="loginform">
											<!-- item -->
											<div class="item">
												<input type="text" placeholder="Email Address" id="employer_email_id">
											</div>
											<!-- item -->
											<!-- item -->
											<div class="item">
												<input type="password" placeholder="Password" id="employer_password">
											</div>
											<!-- item -->
											<!-- item -->
											<div class="item">
												<input type="button" value="Login" data-usertype="employer" onclick="formSubmit(this)">
											</div>
											<!-- item -->
											<!-- item -->
												<div class="item forgot-psw">
													<div class="cl-lt">
													<input type="checkbox" value="agree">
													<p>Remember me</p>
													</div>
													<!---->

													<div class="cl-rt">
													<a href="{{ route('forgot-password') }}">Forgot Password ?</a>
													</div>
													<!---->
												</div>
											<!-- item -->
											<div class="item-border">
												<p>or</p>
											</div>


											<!-- item -->
												<div class="register-sec">
													<!---->
													<p>Don't have an account yet?</p>
													<a href="{{ route('register') }}">Register</a>
													<!---->
												</div>
											<!-- item -->
										</form>
									</div>
								</div>
							<!-- item-form-bd -->
							<!-- item-form-bd -->
								<div class="tab-pane fade" id="career-coach" role="tabpanel" aria-labelledby="career-coach-tab">
									<div class="form-bd">
										<form id="loginform">
											<!-- item -->
											<div class="item">
												<input type="text" placeholder="Email Address" id="coach_email_id">
											</div>
											<!-- item -->
											<!-- item -->
											<div class="item">
												<input type="password" placeholder="Password" id="coach_password">
											</div>
											<!-- item -->
											<!-- item -->
											<div class="item">
												<input type="button" value="Login" data-usertype="coach" onclick="formSubmit(this)">
											</div>
											<!-- item -->
											<!-- item -->
												<div class="item forgot-psw">
													<div class="cl-lt">
													<input type="checkbox" value="agree">
													<p>Remember me</p>
													</div>
													<!---->

													<div class="cl-rt">
													<a href="{{ route('forgot-password') }}">Forgot Password ?</a>
													</div>
													<!---->
												</div>
											<!-- item -->
											<div class="item-border">
												<p>or</p>
											</div>

											<!-- item -->
												<div class="register-sec">
													<!---->
													<p>Don't have an account yet?</p>
													<a href="{{ route('register') }}">Register</a>
													<!---->
												</div>
											<!-- item -->
										</form>
									</div>
								</div>
							<!-- item-form-bd -->
						</div>
					</div>
				</div>
			<!-- ============== -->
		</div>
	</div>
<!-- login-block -->
<?php
	if(!empty($search_job) && (count($search_job) > 0)):
?>
<script>
	toastr.info("Need to login first.");
</script>
<form action="{{ route('common.job-listing') }}" method="post" id="jobsearchfromdashboard" style="display:none;">
	@csrf
	<input type="hidden" name="keyword" value="{{ @$search_job['keyword'] }}">
	<input type="hidden" name="job_location" value="{{ @$search_job['job_location'] }}">
	<input type="hidden" name="sector" value="{{ @$search_job['sector'] }}">
	<input type="hidden" name="frompage" value="welcome-login-jobsearch">
	<input type="submit" value="submit">
</form>
<?php endif; ?>
<?php
	if(!empty($joblistdata)){
		$for = $joblistdata['for'];
		if($for == 'seeker'){
?>
<script>
	$('.nav-tabs').find('.nav-link').each(function(){
		$(this).removeClass('active');
		if($(this).attr('id') == 'job-seeker-tab'){
			$(this).addClass('active');
		}
	});
	$('.tab-content').find('.tab-pane').each(function(){
		$(this).removeClass('show active');
		if($(this).attr('id') == 'job-seeker'){
			$(this).addClass('show active');
		}
	});
</script>
<?php	}
	}
?>
@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
	$(document).ready(function(){
        let jobappliid = "{{@$jobappliid}}";
        if(jobappliid != ''){
            toastr.info("You Need To Login First.");
        }
    });
	function formSubmit(element){
		$(".page-loader").show("first");
		let usertype = $(element).attr('data-usertype');
		var ajax_url = APP_URL+'/logincheck';
		let emailid = $('#'+usertype+'_email_id').val();
		let password = $('#'+usertype+'_password').val();
		var jobsearch = '{{count($search_job)}}';
		$.ajax({
			url: ajax_url,
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {usertype: usertype, email_id: emailid, password: password},
			success: function(res){
				if(res.code == '200'){
					toastr.success(res.msg);
					if((jobsearch > 0)){
						$('#jobsearchfromdashboard').submit();
						return;
					}else{
						if(res.data.for == 'seeker'){
							location.href = "{{ route('dashboard') }}" + '/' + res.data.jobid + '/' + res.data.where;
						}else{
							location.href = "{{ route('dashboard') }}";
						}
					}
				};
				if(res.code == '401'){
					toastr.warning(res.msg);
				};
				if(res.code == '403'){
					let err = res.msg;
					if(err.hasOwnProperty('email_id')){
						for(let j = 0;j < err.email_id.length;j++){
							toastr.error(err.email_id[j]);
						}
						$('#'+usertype+'_email_id').val();
					};
					if(err.hasOwnProperty('password')){
						for(let j = 0;j < err.password.length;j++){
							toastr.error(err.password[j]);
						}
						$('#'+usertype+'_password').val();
					};
				}
				if(res.code == '500'){
					toastr.error(res.msg);
					setTimeout(() => {
						location.reload();
					}, 1000);
				}
				if(res.code == '501'){
					toastr.error(res.msg);
					setTimeout(() => {
						location.reload();
					}, 1000);
				}
			},
			error: function(err){
				console.log(err);
			},
		});
		$(".page-loader").hide("slow");
	}
</script>
@endsection
