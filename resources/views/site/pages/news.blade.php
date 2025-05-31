@extends('site.layout.app')
@section('title', 'Our '.$data['type'])
@section('mystyle')
<style>

</style>
@endsection
@section('content')
<!-- banner-block -->
<div class="our-news-block bg-light-red">
    <div class="container">
        <div class="title-block mb-4">
            <h1 class="newsh">All {{ $data['type'] }}</h1>
        </div>
        <div class="row">
            @foreach($data['news'] as $n)
            <div class="mb-4 col-md-6 col-lg-4">
                <div class="item">
                    <div class="atext equalheight">
                        <h4><a href="{{ route('blog-details', ['slug' => $n->slug , 'type' =>$data['type']]) }}">{!! $n->title !!}</a></h4>
                    </div>
                    <div class="figure" style="display:<?= ($data['type'] === 'all') ? 'inline-block' : 'block';?>">
                        @if($data['type'] == 'news')
                            <div class="imgheading">{{ $n->category->category_name }}</div>
                        @endif
                        <img src="{{ $n->image }}">
                    </div>
                    <div class="row no-gutters date-t">
                        <div class="col-md-6"><i class="fa fa-user"></i>&nbsp;{{ $n->creator->name }}</div>
                        <div class="col-md-6" style="text-align: end;"><i class="fa fa-calendar"></i>&nbsp;{{ date('M d, Y', strtotime($n->created_at)) }}</div>
                    </div>
                    @if($data['type'] == 'news')
                        <div class="text">
                            <div class="describe">
                                {!! substr(strip_tags(trim(preg_replace('/\[[^\]]*\]/', '', $n->newsdetail))), 0, 140) !!}
                                @if($n->newsdetail != '')...@endif
                            </div>
                            <a class="btn" href="{{ route('blog-details', ['slug' => $n->slug , 'type' =>$data['type']]) }}">Read More</a>
                        </div>
                    @else
                        <div class="text">
                            <div class="describe">
                                {!! substr(strip_tags(trim(preg_replace('/\[[^\]]*\]/', '', $n->details))), 0, 140) !!}
                                @if($n->detailsl != '')...@endif
                            </div>
                            <a class="btn" href="{{ route('blog-details', ['slug' => $n->slug, 'type' =>$data['type']]) }}">Read More</a>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
            <div class="col-md-12" style="display:flex;justify-content:center;margin-top: 15px;">
                {{ $data['news']->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
            </div>
        </div>
    </div>
</div>
@endsection
