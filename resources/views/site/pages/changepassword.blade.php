@extends('site.layout.app')
@section('title', 'Welcome')
@section('mystyle')
@endsection
@section('content')
<!-- banner-block -->	

<!-- login-block -->
	<div class="login-block">
		<div class="container">
            @if(!empty($errors))
				@foreach ($errors->all() as $error)
					<script>
						toastr.warning("{{$error}}");
					</script>
				@endforeach
			@endif
            @if(session('error'))
				<script>
					toastr.error("{{session('error')}}");
				</script>
			@endif	
            @if(session('success'))
				<script>
					toastr.success("{{session('success')}}");
				</script>
			@endif	
			<!-- =============== -->
				<div class="bd-block">
					<h4>Change Password</h4>
					<form action="{{ route('change-password-save') }}" method="post">
                        @csrf
                        <div class="item-sec">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Email-ID <span class="text-danger">*</span></label>
                                    <input type="email" value="{{ @$mail }}" readonly name="emailid" class="form-control" placeholder="Forgot Password Email-ID" id="emailid">
                                </div>
                                <div class="col-md-12">
                                    <label for="">Password</label>
                                    <input type="password" name="password" value="{{ old('password') }}" id="password" class="form-control" placeholder="Enter A Password.">
                                </div>
                                <div class="col-md-12">
                                    <label for="">Confirm Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Enter Confirm Password.">
                                </div>
                                <!-- item -->
                                <div class="col-md-12">
                                    <button type="submit" style="width: 100%;" class="btn btn-danger"><i class="fa fa-submit"></i>Submit</button>
                                </div>
                                <!-- item -->
                            </div>
                        </div>
                    </form>
				</div>
			<!-- ============== -->
		</div>
	</div>
<!-- login-block -->

@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
	
</script>
@endsection