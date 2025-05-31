@extends('site.layout.app')
@section('title', 'Contact Support')
@section('mystyle')
<style>
    .row{
        display: flex;
        justify-content: center;
    }
    .rowmargin{
        margin: 0 20px 10px;
    }
    .colstyle{
        margin-top: 1%;
    }
    .button{
        width: 95.5% !important;
    }
    .deleteaccount_col{
        margin-top: 30px;
        margin-left: 2%;
    }
    .deleteaccount_btn{
        background-color: white;
        color: #eb1829;
        width: 49%;
        margin-left: -24px;
        border: 1px solid;
        border-radius: 5px;
    }
    .error { 
        color: #eb1829;
    }
    .ck-content{
        height: 150px;
    }
    .col3{
        margin-left: -18% !important;
        max-width: 5% !important;
    }
</style>
@endsection
@section('content')
<!-- banner-block -->	

<!-- post-resume-block -->
	<div class="post-resume-one">
		<div class="container">
			<div class="bd-block">
				<div class="row">   
                    <div class="section-titel text-center mb-4 mb-lg-5">
                        <h2>Contact Support</h2>
                        <p>Let us know how we can help</p>
                    </div>
				</div>
                @if(!empty($errors))
                    @foreach ($errors->all() as $error)
                        <script>
                            toastr.warning("{{$error}}");
                        </script>
                    @endforeach
                @endif
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
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <div class="row">
                                <div class="col-md-10">
                                    <h3>{{ $contact->heading }}</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="">Contact Email: <a href="mailto:{{ $setting->contact_email }}">{{ $setting->contact_email }}</a></label>
                                </div>   
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-3 col3"><label for=""><i class="fa fa-map-marker" aria-hidden="true"></i></label></div>
                                        <div class="col-md-9">                                        
                                            <p>{{$contact->contactlocation}}</p>    
                                        </div>
                                    </div>
                                </div>    
                                <hr>                     
                                <div class="col-md-10">
                                    <p class="mb-3">{!! nl2br($contact->detail) !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('contact-query') }}" method="post">
                                @csrf
                                <div class="row rowmargin">
                                    <div class="col-md-12 colstyle c-support">
                                        <label for="">Subject</label>
                                        <input type="text" name="subject" id="subject" value="{{old('subject')}}" class="form-control" placeholder="Contact Subject">
                                        @if ($errors->has('subject'))
                                            <span class="error">{{ $errors->first('subject') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row rowmargin">
                                    <div class="col-md-12 colstyle">
                                        <label for="">Your Query</label>
                                        <textarea class="textarea" name="sms_body" id="sms_body" placeholder="Write Down Your Query."
                                                style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                        @if ($errors->has('sms_body'))
                                            <span class="error">{{ $errors->first('sms_body') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11 colstyle" style="text-align: center;">
                                        <button type="submit" class="btn btn-submit button" style="background-color:#eb1829;color:white;width:100%;">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
<!-- post-resume-block -->

@endsection
@section('myscript')
<script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
<script>
	const APP_URL = '<?= env('APP_URL')?>';
    ClassicEditor.create( document.querySelector( '#sms_body' ) )
    .then( editor => {console.log( editor );} )
    .catch( error => {console.error( error );} );
    $('.deleteaccount_btn').on('click', function(){
        if(!confirm("Do You Want To Delete Your Account?")) return false;
    });
    setTimeout(() => {
        $('.error').hide();
    }, 4000);
</script>
@endsection