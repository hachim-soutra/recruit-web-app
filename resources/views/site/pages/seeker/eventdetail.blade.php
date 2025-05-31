@extends('site.layout.app')
@section('title', 'Events')
@section('mystyle')
<style>
	.heading{
		position: relative;
		width: 100%;
		min-height: 66px;
		padding-right: 15px;
		padding-left: 15px;
	}
    .img{
        width: 100%;
        height: 400px;
        border-top-left-radius: 25px;
        border-top-right-radius: 25px;
    }
</style>
@endsection
@section('content')

<!-- post-resume-block -->
	<div class="post-resume-one">
		<div class="container">
			<div class="bd-block">
				@if(Auth::user()->user_type === 'candidate')
                <div class="row">
                    <div class="col-lg-12" style="text-align: center;">
                        <img src="{{ $event->image }}" class="img">
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-6"><h3>{{$event->title}}</h3></div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <i class="fa fa-calendar"></i>
                        <span>{{ date("jS F, Y",strtotime($event->event_date));  }}</span>
                    </div>
                    <div class="col-md-1" style="max-width:5.333333%;">|</div>
                    <div class="col-md-3">
                        <i class="fa fa fa-clock-o"></i>
                        <span>{{ date("h:i",strtotime($event->time));  }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    {!! nl2br($event->details) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-top: 5%;">
                        <button type="button" class="btn btn-submit" style="background-color:#eb1829;color:white;width:100%;"><i class="fa fa-plus-square-o"></i>&nbsp;Register</button>
                    </div>
                </div>
				@endif
			</div>
		</div>
	</div>
<!-- post-resume-block -->

@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
</script>
@endsection