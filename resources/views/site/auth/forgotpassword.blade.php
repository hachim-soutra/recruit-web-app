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
					<h4>Forgot Password</h4>
					<form action="{{ route('forgot-password-mail-send') }}" method="post">
                        @csrf
                        <div class="item-sec">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Email-ID <span class="text-danger">*</span></label>
                                    <input type="email" name="emailid" class="form-control" placeholder="Forgot Password Email-ID" id="emailid">
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