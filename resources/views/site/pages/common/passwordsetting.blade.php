@extends('site.layout.app')
@section('title', 'Reset Password')
@section('mystyle')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
  
    .button{
        width: 100% !important;
    }
    
    .deleteaccount_btn{
        background-color: white;
        color: #eb1829;
        width: 100%;
        border: 1px solid;
        border-radius: 5px;
        height: 45px;
        font-size: 14px;
    }
     .deleteaccount_btn:hover{
        background: #eb1829;;
        color: #fff;
     }
    .error { 
        color: #eb1829;
    }
</style>
@endsection
@section('content')
<!-- banner-block -->	

<!-- post-resume-block -->
	<div class="login-block">
		<div class="container">
			<div class="bd-block findjobmain">
				<div class="row">   
                    <div class="col-lg-12">
                        <h4>Reset Password</h4>
                    </div>
				</div>
                @if(session('success'))
					<script>
						toastr.success("{{session('success')}}");
					</script>
				@endif
                @if(Session::has('error'))
                    <script>
						toastr.error("{{ Session::get('error') }}");
					</script>
                @endif
                <form action="{{ route('reset-password') }}" method="post">
                        @csrf
                       
                            <div class="form-group ">
                                <label for="">Old Password</label>
                                <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Your Old Password">
                                @if ($errors->has('old_password'))
                                    <span class="error">{{ $errors->first('old_password') }}</span>
                                @endif
                            </div>
                      
                        
                            <div class="form-group ">
                                <label for="">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password">
                                @if ($errors->has('new_password'))
                                    <span class="error">{{ $errors->first('new_password') }}</span>
                                @endif
                            </div>
                        
                      
                            <div class="form-group ">
                                <label for="">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                                @if ($errors->has('confirm_password'))
                                    <span class="error">{{ $errors->first('confirm_password') }}</span>
                                @endif
                            </div>
                       
                        
                            <div class="form-group ">
                                <button type="submit" class="btn btn-submit button" style="background-color: #eb1829; color: white; width:100% !important;">Submit</button>
                            </div>
                        
                        <div class="form-group mb-0">
                            <div class="deleteaccount_col" style="text-align: center;">
                                <button type="button" onclick="deleteAccount()" class="btn btn-submit deleteaccount_btn">Delete your account</button>
                            </div>
                        </div>
                    </form>
			</div>
		</div>
	</div>
<!-- post-resume-block -->

@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
    $('.deleteaccount_btn').on('click', function(){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'Deleted Successfully.',
                    'success'
                )
                location.href = "{{ route('delete-account') }}";
            }
        });      
    });
    setTimeout(() => {
        $('.error').hide();
    }, 4000);
</script>
@endsection