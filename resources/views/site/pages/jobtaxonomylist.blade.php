@extends('site.layout.app')
@section('title', 'Jobs Taxonomy List')
@section('robots','noindex')
@section('mystyle')
<style>
    .atext{
        margin: 0;
        padding: 16px 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .atext a{
        color: #ed1c24;
        font-weight: 500;
        text-align: left;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
    } 
    .text{
        min-height: 0px !important;
    }
    .item-col{
		margin: 0 0 21px 0;
		padding: 15px 10px 0px 10px;
		background: #FFFFFF;
		border: 1px solid #E9E9E9;
		border-radius: 5px;
	}
    .select2-container .select2-container--default .select2-container--open{
		top: 137px !important;
    	left: 786.219px !important;
	}
	.select2-container--open .select2-dropdown--below{
		width: 332.016px !important;
    	margin-left: -45.65px !important;
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
	.sector-item{
	    padding:10px;
	    font-family: 'Poppins', sans-serif;
	    margin:4px;
	    border-radius:3px;
	    background:#d4d4d4;
	    color:#292929;
	}
</style>
@endsection

@section('content')
<!-- banner-block -->	
@include('site.layout.banner')
<!-- banner-block -->

<div class="your-sector">
    <div class="container">

        <div class="title-block">
            <h2>Jobs based on <span style="color:red;">Functional Areas</span></h2>
            <!--<h3>Find your sector or browse for some inspiration</h3>-->
        </div>

        <!-- bd -->
        <div class="bd-block">
            <ul>
            @foreach($data['functional_area'] as $l)
                <li>
				<?php
				$term = trim(strtolower($l->functional_area));
				$term = trim(str_ireplace('/' , '-', strtolower($term)));
				$term_permalink = trim(route('job-category').'/'.$term); ?>
				<span><a title="{{ $l->functional_area }}" href="{{ $term_permalink }}">{{ $l->functional_area }}</a></span>
				</li>
            @endforeach
            </ul>
        </div>
        <!-- bd -->
    </div>
</div>
<!-- your-sector -->

<div class="your-sector">
    <div class="container">

        <div class="title-block">
            <h2>Jobs based on <span style="color:red;">Job Types</span></h2>
            <!--<h3>Find your sector or browse for some inspiration</h3>-->
        </div>

        <!-- bd -->
        <div class="bd-block">
            <ul>
            @foreach($data['job_type'] as $l)
                <li>
				<?php
				$term = trim(strtolower($l->preferred_job_type));
				//$term = trim(str_ireplace('/' , '-', strtolower($term)));
				$term_permalink = trim(route('job-type').'/'.$term); ?>
				<span><a title="{{ $l->preferred_job_type }}" href="{{ $term_permalink }}">{{ $l->preferred_job_type }}</a></span>
				</li>
            @endforeach
            </ul>
        </div>
        <!-- bd -->
    </div>
</div>
<!-- your-sector -->

<div class="your-sector">
    <div class="container">

        <div class="title-block">
            <h2>Jobs based on <span style="color:red;">Job Locations</span></h2>
            <!--<h3>Find your sector or browse for some inspiration</h3>-->
        </div>

        <!-- bd -->
        <div class="bd-block">
            <ul>
            @foreach($data['job_location'] as $l)
                <li>
				<?php
				$term = trim(strtolower($l));
				$term_permalink = trim(route('job-location').'/'.$term); ?>
				<span><a title="{{ $l }}" href="{{ $term_permalink }}">{{ $l }}</a></span>
				</li>
            @endforeach
            </ul>
        </div>
        <!-- bd -->
    </div>
</div>
<!-- your-sector -->






<!-- start-working -->
<div class="start-working">
    <div class="container">
        <div class="row align-items-center">
            <!-- item -->
            <div class="col-lg-7 col-md-6">
                <div class="item-text">

                    <div class="title-block">
                        <h2>Start Working Right Now</h2>
                        <h3>Available for iOS and Android</h3>
                    </div>

                    <ul>
                        <li><a href="https://play.google.com/store/apps/details?id=app.recruit.ie&hl=en&gl=US"><img src="{{ asset('frontend/images/img1.png') }}"></a></li>
                        <li><a href="https://apps.apple.com/us/app/recruit-ie/id1638680033"><img src="{{ asset('frontend/images/img2.png') }}"></a></li>
                    </ul>

                </div>
            </div>
            <!-- item -->

            <!-- item -->
            <div class="col-lg-5 col-md-6">
                <div class="figure">
                    <img src="{{ asset('frontend/images/phone01.png') }}">
                    <div class="img2">
                        <img src="{{ asset('frontend/images/phone02.png') }}">
                    </div>
                </div>
            </div>
            <!-- item -->



        </div>
    </div>
</div>
<!-- start-working -->


@endsection

@section('myscript')
@endsection