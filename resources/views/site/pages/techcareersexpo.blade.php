@extends('site.layout.app')
@section('title', 'Permanent Recruitment')
@section('mystyle')
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet"> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
<style>
	/*.heading{
		font-family: "Muli", Sans-serif;
		font-size: 76px;
		font-weight: 800;
		text-transform: capitalize;
		margin: 0;
		color: #FFFFFF;
		position: absolute;
		top: -;
		bottom: 540px;
	}
	.banner-short-description{
		font-weight: 800;
		text-transform: capitalize;
		margin: 0;
		color: #FFFFFF;
		position: absolute;
		top: -;
		bottom: 600px;
	}
	.content-header{
		padding: 8em 0em 5em 0em;
		color: #636363;
		font-size: 16px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 3.4px;
	}
	.content-center{
		display: flex;
		justify-content: center;
	}
	.content-content{
		color: #2D2E36;
		font-family: "Muli", Sans-serif;
		font-size: 52px;
		font-weight: 800;
		text-transform: capitalize;
		letter-spacing: -1px;
	}
	.content-description{
		border: 0;
		font-size: 100%;
		font-style: inherit;
		font-weight: inherit;
		margin: 0;
		outline: 0;
		padding: 0;
		vertical-align: baseline;
		margin-bottom: 1.6em;
	}
	.content-img{
		border-radius: 10px 10px 10px 10px;
		vertical-align: middle;
		display: inline-block;
		height: auto;
		max-width: 100%;
		border: none;
		border-radius: 0;
		-webkit-box-shadow: none;
		box-shadow: none;
	}
	.content-title{
		color: #636363;
		font-size: 16px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 3.4px;
	}
	.content-widget {
		padding: 20px 20px 20px 20px;
		border-style: solid;
		border-width: 3px 3px 3px 3px;
		border-color: #ED1C2496;
		border-radius: 10px 10px 10px 10px;
	}
	.elementor-icon-box-title{
		color: #2D2E36;
		font-family: "Muli", Sans-serif;
		font-size: 22px;
		font-weight: 800;
		text-transform: capitalize;
	}
	.elementor-widget-icon-box .elementor-icon-box-content{
		-webkit-box-flex: 1;
		-ms-flex-positive: 1;
		flex-grow: 1;
	}
	.elementor-widget-icon-box.elementor-position-left .elementor-icon-box-icon{
		margin-right: var(--icon-box-icon-margin,15px);
		margin-left: 0;
		margin-bottom: unset;
	}
	.elementor-widget-container{
		padding: 20px 20px 20px 20px;
		border-style: solid;
		border-width: 3px 3px 3px 3px;
		border-color: #ED1C2496;
		border-radius: 10px 10px 10px 10px;
	}
	.content-title-description{
		margin: 0px 0px 20px 0px;
		font-size: 21px;
		width: 100%;
		color: #2D2E36;
		font-family: "Muli", Sans-serif;
		font-weight: 800;
		text-transform: capitalize;
		letter-spacing: -1px;
	}
	.elementor-row{
		width: 100%;
		display: flex;
	}
	.counter {
		transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
    	padding: 100px 0px 100px 0px;
		background-color: var( --e-global-color-6a72811 );
	}
	.textcolor {
		background-color: #FFFFFF;
	}
	.textcolor > h3{
		color: #000000;
		font-size: 15px;
		font-weight: 300;
		text-transform: uppercase;
		letter-spacing: 5px;
	}*/
</style>
@endsection
@section('content')
<!-- banner-block -->	
<div class="post-resume-one p-0">

<section class="inner-banner-div">
	<img src="{{ asset('uploads/staff/'.@$staff->banner_file) }}" alt="">
	<div class="banner-layer1"></div>
	<div class="banner-layer2"></div>
	<div class="banner-layer3"></div>
	<div class="banner-text">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8 mr-auto text-left">
					<h2 class="heading">{{ @$staff->banner_heading }}</h2>
					<p class="banner-short-description">{{ @$staff->short_description }}</p>		
				</div>
			</div>
		</div>
	</div>
</section>

<section class="cms-pagedesign">
	<div class="find-outmore pt-0 text-left">
		<div class="container">
			<div class="row align-items-center">
				
				<div class="col-md-7">
					<div class="section-titel">

						<h5>RECRUIT.IE</h5>
						<h2>{{ @$staff->banner_heading }}</h2>
						<p>{!! nl2br(@$staff->left_content) !!}</p>
						<p>{!! nl2br(@$staff->left_content) !!}</p>
					</div>
				</div>
				<div class="col-md-5">
					<div class="left-pimg">
						<img class="img-1" src="{{ asset('uploads/staff/'.@$staff->left_file) }}" alt="">
						<img class="img-2" src="{{ asset('uploads/staff/'.@$staff->right_file) }}" alt="">
					</div>
				</div>
			</div>
		</div>
	</div>

<div class="vrcul-grot py-5" style="background: linear-gradient(90deg, #c8222ad4, #e91e63), url({{ asset('uploads/staff/'.@$staff->middle_banner_file) }}) center no-repeat;">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
				<div class="counterbox">
					<h2>{{ number_format(@$staff->candidate_count) }}</h2>
					<h3>CANDIDATES</h3>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
				<div class="counterbox">
					<h2>{{ number_format(@$staff->employer_count) }}</h2>
					<h3>EMPLOYERS</h3>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
				<div class="counterbox">
					<h2>{{ number_format(@$staff->roles_count) }}</h2>
					<h3>ROLES</h3>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="pt-5">
	<div class="container">
		<div class="row">
			<?php 
				$process_icon = explode('|', @$staff->process_icon); 
				$process_name = explode('|', @$staff->process_name); 
				$process_detail = explode('|', @$staff->process_detail); 
			?>
			@for($i = 0;$i < count($process_icon);$i++)
				<div class="col-md-6 mb-4 col-lg-4">
					<div class="content-widget tac-crxpo align-items-start">
		                <div class="number mt-1"><i class="{{ $process_icon[$i] }}"></i></div>
						<div class="box-content">
							<h5>{{ @$process_name[$i] }}</h5>
							<p>{{ @$process_detail[$i] }}</p>
						</div>
					</div>
				</div>
			@endfor
		</div>
	</div>
</div>	

	<!-- <div class="container"> -->
		<!-- <div class="row">
			<div class="col-md-6">
				<h2 class="heading">{{ @$staff->banner_heading }}</h2>				
			</div>
		</div> -->
		<!-- <div class="row content-center">
			<h3 class="content-content">{{ @$staff->banner_heading }}</h3>
		</div> -->
		<!-- <div class="row">
			<div class="col-md-4">
				<span>{!! nl2br(@$staff->left_content) !!}</span>
			</div>
			<div class="col-md-4">
				<div class="col-md-6">
					<img style="width: 290px;height: auto;" src="{{ asset('uploads/staff/'.@$staff->left_file) }}" alt="">
				</div>
				<div class="col-md-6"><img style="width: 100px;" src="{{ asset('uploads/staff/'.@$staff->right_file) }}" alt=""></div>
			</div>
			<div class="col-md-4">
				<span>{!! nl2br(@$staff->right_content) !!}</span>
			</div>
		</div> -->
		<!-- <div class="row counter">
			<div class="col-md-4 textcolor">
				<p>{{ number_format(@$staff->candidate_count) }}</p>
				<h3>CANDIDATES</h3>
			</div>
			<div class="col-md-4 textcolor">
				<p>{{ number_format(@$staff->employer_count) }}</p>
				<h3>EMPLOYERS</h3>
			</div>
			<div class="col-md-4 textcolor">
				<p>{{ number_format(@$staff->roles_count) }}</p>
				<h3>ROLES</h3>
			</div>
		</div> -->
		<!-- <div class="row">
			<?php 
				$process_icon = explode('|', @$staff->process_icon); 
				$process_name = explode('|', @$staff->process_name); 
				$process_detail = explode('|', @$staff->process_detail); 
			?>
			@for($i = 0;$i < count($process_icon);$i++)
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-1"><span><i class="{{ $process_icon[$i] }}"></i></span></div>
					<div class="col-md-10"><p>{{ @$process_name[$i] }}</p></div>
				</div>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-10"><p>{{ @$process_detail[$i] }}</p></div>
				</div>				
			</div>
			@endfor
		</div> -->
	<!-- </div> -->
</section>
	
</div>
@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
</script>
@endsection
