@extends('site.layout.app')
@section('title', 'Permanent Recruitment')
@section('mystyle')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">

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


	<section class="cms-pagedesign">
	    <div class="container">

	    	<div class="section-titel text-center mb-4 mb-lg-5">
	    		<h5>{{ @$staff->content_title }}</h5>
				<h2>{{ @$staff->banner_heading }}</h2>
				<p>{{ @$staff->page_content }}</p>
	    	</div> 

			<div class="cst-img mb-4 mb-lg-5">
				<img src="{{ asset('uploads/staff/'.@$staff->content_file) }}" alt="">
			</div>

			<div class="section-titel text-center  mb-4 mb-lg-4">
				<h2>RECRUIT.IE</h2>
				<h5>Takes Care Of The Entire Cycle For The Recruitment Process</h5>
			</div>
				

				<div class="row mb-4">
					<?php 
						$process_name = $staff->process_name;
						$exp = explode('|', $process_name);
						for($i = 0;$i < count($exp);$i++):
					?>

					<div class="col-md-4 mb-4">
						<div class="content-widget">
							<?php 
								$count = $i + 1;
								if($count < 9){
									$count = str_pad($count, 2, '0', STR_PAD_LEFT);
								}
							?>
							<div class="number">{{ $count }}</div>
							<div class="box-content">
								<h5>{{$exp[$i]}}</h5>
							</div>
						</div>
					</div>

					<?php endfor; ?>
				</div>
				<div class="section-titel text-center mb-4">
			    	<h2>What Is The End Result?</h2>
				</div>

				<div class="row mb-4">
					<?php
						$exp_result = explode('|', $staff->result_image);
						$proccess_des = explode('|', $staff->proccess_des);
						for($i = 0;$i < count($exp_result);$i++):
					?>
					<div class="col-md-4">
						<div class="endrs-box">
							<img src="{{ asset('uploads/staff/'.$exp_result[$i]) }}" alt="">
							<span>{{ $proccess_des[$i] }}</span>
						</div>
					</div>
					<?php endfor; ?>
				</div>


				<div id="gllery-slide" class="owl-carousel">
					
						<?php
							$gallery = explode('|', $staff->gallery);
							for($i = 0;$i < count($gallery);$i++):
						?>
						<div class="item">
							<a  class="gallery" href="{{ asset('uploads/staff/'.$gallery[$i]) }}" data-fancybox data-toolbar="true">
								<img src="{{ asset('uploads/staff/'.$gallery[$i]) }}" alt="">
								<div class="overlay-img">
									<img src="{{ asset('frontend/images/plus.png') }}">
								</div>				
							</a>
						</div>
						<?php endfor; ?>
				
				</div>
		</div>
	</section>

</div>

@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
</script>
@endsection
