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
</style>
@endsection
@section('content')

<!-- post-resume-block -->
	<div class="login-block">
		<div class="container">
			<div class="bd-block">
				@if(Auth::user()->user_type === 'candidate')
                <!-- <div class="row">
					<div class="col-mg-12 heading" style="text-align: center;">
						<span>Events/ Webinar</span>
					</div>
				</div> -->

                <div class="section-titel text-center mb-4 mb-lg-5">
                    <h4>Events/ Webinar</h4>
                  </div>
                <div class="row">
				    @forelse($data['events'] as $e)
                        <div class="col-lg-12" onclick="eventDetail('{{ $e->id }}')" style="padding: 0 5px 5px;cursor: pointer;">
                            <!-- item-col 1-->
                                <div class="item-col">
                                    <!-- middle -->
                                    <div class="middle">
                                        <div class="rt">
                                            <div class="img-sec">
                                                <img src="{{ $e->image }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- middle -->
                                    <!-- bottom -->
                                    <div class="bottom">
                                        <p>{{$e->title}}</p>
                                        <span><b>Posted :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime($e->created_at))->diffForHumans() }}</span>
                                    </div>
                                    <!-- bottom -->
                                </div>
                            <!-- item-col -->
                        </div>
                    @empty
                    <h3 class="text-center" style="color:red;width: 100%;">No Data Found.</h3>
                    @endforelse
                </div>
                <div class="col-md-12" style="display:flex;justify-content:center;margin-top: 40px;">
                    {{$data['events']->appends(Request::except('page'))->onEachSide(1)->links("pagination::bootstrap-4") }}
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
    function eventDetail(eventid){
        location.href="{{ route('event-detail') }}" + '/' + eventid;
    }
</script>
@endsection