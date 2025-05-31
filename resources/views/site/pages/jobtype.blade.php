@extends('site.layout.app')

@section('title', $data['meta_title'])
@section('meta_desc', $data['meta_desc'])

@if( !empty($data['jobid']) && $data['jobid'] > 0 && !empty($data['firstJobDetail']))
	<?php
	$job_slug = trim( $data['firstJobDetail']->id.'-'.Str::slug($data['firstJobDetail']->job_title) );
	$meta_canonical = trim(route('job-listing').'/'.$job_slug);
	?>
	@section('meta_canonical', $meta_canonical)
@endif

@section('mystyle')
<style>
	.heading{
		position: relative;
		width: 100%;
		min-height: 66px;
		padding-right: 15px;
		padding-left: 15px;
	}
	.appliedbtn{
		border-radius: 25px;
		font-weight: lighter;
		font-size: 13px;
    	height: 37px;
    	padding-left: 20px;
    	padding-right: 20px;
	}
	.appliedbtn i{
		font-size: 12px !important;
	}
	.appliedtext{
		border: 1px solid;
		border-radius: 10px;
		width: 15%;
		text-align: center;
		color: green !important;
		font-size: 12px;
		font-weight: 500;
	}
	.profileimg{
		border-radius: 50%;
    	width: 30px;
    	height: 30px;
        margin-right: 10px;
        margin-top: -10px;
	}
	.socialicon{
		font-size: 24px;
		border: 1px solid;
		width: 25%;
		display: flex;
		border-radius: 50%;
		justify-content: center;
		height: 22%;
	}
	.banner-block{
		margin: 0 !important;
		padding: 0 !important;
		width: 100% !important;
		height: 370px !important;
		padding: 25px 0 0 0 !important;
	}
</style>
<style>
    .img{
        width: 100%;
        height: 400px;
        border-top-left-radius: 25px;
        border-top-right-radius: 25px;
    }
    .university h3{
        font-size: 16px;
        font-weight: 500;
    }
    .tablinks{
        border: none;
        margin-right: 80px;
        border-top: none;
        cursor: pointer;
    }
     .tablinks:focus
    {
        box-shadow: none;
        outline: none;
        border: 0px;
    }
    .tablinks.last{
        margin-right: 0px !important;
    }
    .tabrow{
        border-bottom: 1px solid #8080801f;
        margin-left: -2px;
        margin-top: 4%;
    }
    .tab .active{
        color: #c12128 !important;
    }
    .tabcontent{
        padding: 1%;
        display: none;
    }
    .findbtn{
        width: 101%;
        color: white;
        background-color: #c12128;
        margin-left: -5px;
        margin-top: 15px;
    }
	.pagination{
		width: 100%;
	}
	.select2-container .select2-container--default .select2-container--open{
		top: 137px !important;
    	left: 786.219px !important;
	}
	.select2-container--open .select2-dropdown--below{
		width: 336.016px !important;
    	margin-left: -31px !important;
	}
	.select2-container--default .select2-selection--single .select2-selection__arrow{
		top: 10px !important;
	}
	.select2-container--default .select2-selection--single{
		border: none !important;
	}
	.select2-container .select2-selection--single{
		height: 50px !important;
		display: flex !important;
		align-items: center !important;
	}
	.inner-form-custom .banner-block .banner-bd h1{
		display: block !important;
	}
    .skill-listing
    {
        margin-left: 5%;
    }
    .skill-listing li
    {
        font-size: 15px;
        font-weight: 600;
    }
    .h5span{
        color: #ed1c24;
        font-size: 25px;
        font-weight: 900;
    }
</style>
@endsection
@section('content')
<?php
    //Line 14
    //{{ $data['jobPost']->appends(Request::except('page'))->onEachSide(1)->links("pagination::bootstrap-4") }}
    //session()->forget('search_job');
    //session()->forget('frompage');
?>
<!-- banner-block -->
<div class="<?php if(@$data['count'] > 0):echo 'post-resume-one pt-5';else:echo 'login-block';endif;?>">
    <div class="container">
        <div class="bd-block">
            <div class="title-block mb-4">
                <h1>All <span style="text-transform:capitalize;">{{ @$data['term'] }}</span> Jobs</h1>
            </div>
            @if(session('success'))
                <script>
                    toastr.success("{{session('success')}}");
                </script>
            @endif
            @if(session('error'))
                <script>
                    toastr.error("{{session('error')}}");
                </script>
            @endif
            <div class="row">
                <div class="col-lg-12 h-auto mb-3">
                    @if(@$data['count'] > 0)
                    <h5 class="mb-0"><span class="h5span">{{ @$data['count'] }}</span> Matching Results</h5>
                    @else
                    <h5 class="mb-0 text-center"><span class="h5span">No</span> Matching Result Found.</h5>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    @foreach($data['jobPost'] as $jp)
                    <!-- item-col 1-->
                        @include('site.layout.jobviewloop')
                    <!-- item-col -->
                    @endforeach
                
                </div>
                @if(!empty($data['firstJobDetail']) && $data['count'] > 0)
                <div class="col-lg-7">
                    
					@include('site.layout.firstjobdetails')
					
					@if( !empty($data['firstJobDetail']) )
						@include('site.layout.jobviewstatistics', ['jobid' => $data['firstJobDetail']->id])
					@endif
                </div>
                @endif
            </div>
			
			<!--{{ $data['jobPost']->currentPage() }}-->			
			<!--<pre><?php //print_r($data); ?></pre>-->
			<div class="row pagination-row" style="margin-top:20px;">
                <div class="col-md-12">
					{{ $data['jobPost']->links() }}
				</div>
			</div>
			
        </div>
    </div>
</div>
@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
	
    function showJobPost(jobpostid, jobtitleslug = null){
		
		var job_permalink=jobpostid;
		if(jobtitleslug != null && jobtitleslug != '') {job_permalink=jobpostid+'-'+jobtitleslug;}
		
		var href="{{ route('job-type') }}" + '/' + "{{ @$data['term'] }}" + '/job/' + job_permalink;
		
		var page=1;
		@if($data['jobPost']->currentPage() > 1)
			page={{ $data['jobPost']->currentPage() }};
		@endif
		if(page>1) { href = href + '/?page=' + page; }
		
		location.href=href;
    }

    function jobListApply(jobid){
        $.ajax({
            url: "{{ route('job-listing-apply') }}",
            type: 'POST',
            data: {_token: '{{ csrf_token() }}', jobid: jobid},
            success: function(res){
                if(res.code == 500){
                    toastr.warning("Something Went Wrong.");
                }else{
                    if(res.jobapply.loginid != null){
                        location.href = "{{ route('dashboard') }}" + '/' + res.jobapply.jobid + '/' + res.jobapply.where;
                    }else{
                        location.href = "{{ route('signin') }}" + '/' + res.jobapply.jobid;
                    }
                }
            },
            error: function(err){
                console.log(err);
            }
        });
    }
</script>
@endsection