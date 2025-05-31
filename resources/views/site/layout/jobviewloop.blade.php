<div class="item-col <?php if($jp->id == @$data['firstJobDetail']->id)echo 'active-div';?>" onclick="showJobPost('<?php echo $jp->id ?>', '<?php echo Str::slug($jp->job_title) ?>')">

	<?php //echo Str::slug($jp->job_title) ?>

	<!--top-->
	<div class="top">
		<div class="lt">
			<h3 title="{{ $jp->job_title }}">{{ $jp->job_title }}</h3>
			<span><a title="{{ $jp->company_name }}" href="#">{{ $jp->company_name }}</a></span>
		</div>
	</div>
	<!--top-->
	<!-- middle -->
	<div class="middle">
		<div class="lt">
			<?php			$location_arr = array_filter(explode(',' , trim($jp->job_location)));			if(!empty($location_arr)):				$location_term = trim(strtolower($location_arr[0]));				$location_permalink = trim(route('job-location').'/'.$location_term);				?>				<span><a title="{{ $jp->job_location }}" href="{{ $location_permalink }}">{{ $jp->job_location }}</a></span>			<?php endif; ?>
			<?php
			$job_type_term = trim(strtolower($jp->preferred_job_type));
			$job_type_permalink = trim(route('job-type').'/'.$job_type_term);
			?>
			<p><a title="{{ $jp->preferred_job_type }}" href="{{ $job_type_permalink }}">{{ $jp->preferred_job_type }}</a></p>
		</div>
		<div class="rt">
			<div class="img-sec">
				<img src="{{ $jp->company_logo }}" alt="{{ $jp->company_name }}" />
			</div>
		</div>
	</div>
	<!-- middle -->
	<!-- bottom -->
	<div class="bottom">
        @if($jp->hide_salary != 'yes')
            <p>{{$jp->salary_currency}}{{$jp->salary_from}} - {{$jp->salary_currency}}{{$jp->salary_to}} {{$jp->salary_period}}</p>
        @endif
        <div class="rt">
            <span><b>Posted :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime($jp->created_at))->diffForHumans() }}</span>
        </div>
		@foreach($jp->applicatons as $jp_a)
			@if(($jp_a->job_id === $jp->id) && ($jp_a->candidate_id === Auth::id()))
				<p class="appliedtext">Applied</p>
			@endif
			@break
		@endforeach
	</div>
	<!-- bottom -->
</div>
