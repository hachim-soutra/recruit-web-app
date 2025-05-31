@extends('site.layout.app')
@section('title', 'Testimonial')
@section('mystyle')
@endsection
@section('content')
<!-- banner-block -->	

<!-- testimonials -->
<div class="testimonials-sec">
    <div class="container">
        <div class="title-block">
            <h2>Testimonials</h2>
        </div>
        <!---->
            <div class="slider-bd">
                <div id="bghgk" class="">
                @foreach($data['testimonial'] as $t)
                    <!-- item -->
                        <div class="item">
                            <h3>{!! $t->subject !!}</h3>
                            <div class="figure">					
                                <img src="{{ env('APP_URL').'/uploads/'.($t->image) }}">
                            </div>
                            <h4>{{ $t->name }}</h4>
                            <p>{!! str_replace(array('<b>', '</b>', '<p>', '</p>'), '', $t->designation) !!}</p>
                        </div>
                    <!-- item -->
                @endforeach
                </div>
            </div>
        <!---->

    </div>
</div>
<!-- testimonials -->
@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
</script>
@endsection