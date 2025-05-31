@extends('site.layout.app')
@section('title', 'Profile')
@section('mystyle')
@endsection
@section('content')
<!-- banner-block -->	

<!-- clients-block -->
<div class="clients-block">
    <div class="container">
        <div class="title-block">
            <h2>Some of our clients</h2>
        </div>
        <!-- slider-bd -->
            <div class="slider-bd">
                <div id="logo-slider" class="owl-carousel">
                @foreach($data['clients'] as $c)
                    <!-- item -->
                        <div class="item">
                            <img src="{{ env('APP_URL').'/uploads/'.($c->image) }}">					
                        </div>
                    <!-- item -->
                @endforeach
                </div>
            </div>
        <!-- slider-bd -->

    </div>
</div>
<!-- clients-block -->
<!-- our-awesome-team -->
<div class="our-awesome-team">
    <div class="container">
        <div class="title-block">
            <h2>Our Awesome Team Partners</h2>
        </div>
        <div class="bd-block">
            @foreach($data['team'] as $t)
            <!-- item -->
                <div class="item">
                    <div class="item-bd">
                        <div class="figure">
                            <img src="{{ env('APP_URL').'/uploads/'.($t->image) }}">
                        </div>
                        <div class="text">
                            <h4>{{ $t->name }}</h4>
                            <p>{{ $t->designation }}</p>
                            <ul>
                                <li><a href="{{ $t->fblink }}"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="{{ $t->twlink }}"><i class="fa fa-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <!-- item -->
            @endforeach
        </div>
    </div>
</div>
<!-- our-awesome-team -->

@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
</script>
@endsection