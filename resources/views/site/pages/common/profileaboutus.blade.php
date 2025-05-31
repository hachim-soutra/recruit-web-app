@extends('site.layout.app')
@section('title', 'About Us')
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
        width: 32%;
        border-radius: 20px;
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
        line-height: 0;
    }
    .aboutimg{
        height: 50%;
        width: auto;
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
                    <div class="col-lg-12" style="text-align: center;">
                        <h4>About Us</h4>
                    </div>
				</div>
                <br>
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
                    @foreach($about as $key => $a)
                    @if($key % 2 == 0)
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ asset('uploads/cms/about/'.@$a->aboutus_image) }}" class="aboutimg" alt="">
                        </div>
                        <div class="col-md-8">
                            <h3>{{ @$a->heading }}</h3>
                            <p>{!! nl2br(@$a->detail) !!}</p>
                        </div>
                    </div>
                    <br>
                    @else
                    <div class="row">                        
                        <div class="col-md-8">
                            <h3>{{ @$a->heading }}</h3>
                            <p>{!! nl2br(@$a->detail) !!}</p>
                        </div>
                        <div class="col-md-4">
                            <img src="{{ asset('uploads/cms/about/'.@$a->aboutus_image) }}" class="aboutimg" alt="">
                        </div>
                    </div>
                    @endif
                    @endforeach
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
    setTimeout(() => {
        $('.error').hide();
    }, 4000);
</script>
@endsection