@extends('site.layout.app')
@section('title', 'Permanent Recruitment')
@section('mystyle')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
				<div class="col-md-6 mr-auto text-left">
					<h2 class="heading">{{ @$staff->banner_heading }}</h2>
					<p class="banner-short-description">{{ @$staff->short_description }}</p>		
				</div>
			</div>
		</div>
	</div>
</section>

<section class="cms-pagedesign pb-0">
	<div class="container">
		<div class="row mb-5">
			<div class="col-md-5">
				<div class="left-pimg">
					<img class="img-1" src="{{ asset('uploads/staff/'.@$staff->left_file) }}" alt="">
					<img class="img-2" src="{{ asset('uploads/staff/'.@$staff->right_file) }}" alt="">
				</div>
			</div>
			
			<div class="col-md-7 pl-md-5">
				<div class="section-titel text-left mb-3 mb-lg-3">
					<h5>{{ @$staff->content_title }}</h5>
					<h2>{{ @$staff->banner_heading }}</h2>
					<!-- <p>{{ @$staff->page_content }}</p> -->
				</div>
				<p>{!! nl2br(@$staff->left_content) !!}</p>
				<span class="list-design">{!! nl2br(@$staff->right_content) !!}</span>
			</div>
		</div>
	</div>

<div class="vrcul-grot" style="background: linear-gradient(90deg, #c8222ad4, #e91e63), url({{ asset('uploads/staff/'.@$staff->middle_banner_file) }}) center no-repeat;">
	<div class="container">
		<div class="section-titel text-center mb-3 mb-lg-4">
			<h2 class="mb-1">{{@$staff->middle_banner_heading}}</h2>
			<h5>{!! nl2br(@$staff->middle_short_description) !!}</h5>
		</div>

		<div class="row">
			<?php 
				$widget_heading = explode('|', @$staff->widget_heading); 
				$widget_descriptiuon = explode('|', @$staff->widget_descriptiuon); 
			?>
			@for($i = 0;$i < count($widget_heading);$i++)
			<div class="col-sm-6 col-lg-4 mb-4">
				<div class="dtls-box">
					<h3>{{ $widget_heading[$i] }}</h3>
					<p>{{ $widget_descriptiuon[$i] }}</p>
				</div>
			</div>
			@endfor
			<?php ?>
		</div>
	</div>
</div>	

<div class="find-outmore text-left">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-md-6">
				<div class="left-pimg">
					<img class="img-1" src="{{ asset('uploads/staff/'.@$staff->left_file) }}" alt="">
					<img class="img-2" src="{{ asset('uploads/staff/'.@$staff->right_file) }}" alt="">
				</div>
			</div>
			<div class="col-md-6 pl-5">
				<div class="section-titel">

					<h5>RECRUIT.IE</h5>
					<h2>Visit Our Events</h2>
					<ul class="sochal-i">
						<li><a href="{{ @$staff->fb_link }}"><i class="fa fa-facebook"></i></a></li>
						<li><a href="{{ @$staff->twitter_link }}"><i class="fa fa-twitter"></i></a></li>
						<li><a href="{{ @$staff->linkedin_link }}"><i class="fa fa-linkedin"></i></a></li>
					</ul>
					<a href="https://www.virtualrecruitment.ie/" type="button" class="btn btn-findout"> Find Out More </a>

				</div>
			</div>
		</div>
	</div>
</div>
		
<div class="vrcul-grot findout-div" style="background: linear-gradient(90deg, #0000, #0000), url({{ asset('uploads/staff/'.@$staff->bottom_banner_file) }}) center no-repeat;">
	<div class="banner-layer11"></div>
	<div class="banner-layer2"></div>
	<div class="banner-layer3"></div>
	<div class="container position-relative">
		<div class="section-titel text-center mb-3 mb-lg-4">
			<h2 class="mb-5">{{@$staff->bottom_banner_heading}}</h2>
			<!-- <h5>{!! nl2br(@$staff->middle_short_description) !!}</h5> -->
			<a href="https://www.virtualrecruitment.ie/" type="button" class="btn btn-findout mr-3"> Get Started </a>
			<a href="https://www.virtualrecruitment.ie/" type="button" class="btn btn-findout"> Contact Us </a>
		</div>
	</div>
</div>	

<!-- <div class="container"> -->


		<!-- <div class="row">
			<div class="col-md-12 content-title" style="padding: 60px 21px 60px 15px;">
				<span>RECRUIT.IE</span>
			</div>					
		</div>
		<div class="row">
			<div class="col-md-12 content-title-description">
				<span>Visit Our Events</span>
				<a href="{{ @$staff->event_link }}"><button type="button" class="btn btn-lg btn-warning">Find Out More</button></a>
			</div>	
			<div class="col-md-12">
				<a href="{{ @$staff->fb_link }}"><i class="fa fa-facebook"></i></a>
				<a href="{{ @$staff->twitter_link }}"><i class="fa fa-twitter"></i></a>
				<a href="{{ @$staff->linkedin_link }}"><i class="fa fa-linkedin"></i></a>
			</div>
		</div> -->
		<!-- <div class="row">
			<div class="col-md-11 content-img">
				<img src="{{ asset('uploads/staff/'.@$staff->bottom_banner_file) }}" alt="">
			</div>
			<div class="col-md-12">
				<h2>{{@$staff->bottom_banner_heading}}</h2>
			</div>
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

