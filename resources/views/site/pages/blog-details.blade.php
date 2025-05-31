@extends('site.layout.app')
@section('title', $data['meta_title'])
@section('meta_desc', $data['meta_desc'])
@section('mystyle')
    <style>

    </style>
@endsection
@section('content')
    <!-- banner-block -->
    <div class="our-news-block bg-light-red">
        <div class="container">
            <div class="row">
                <div class="mb-4 col-md-6 col-lg-8 news-details">
                    <div class="item" style="box-shadow:none;border:none;">
                        <div class="text">
                            <h4>{!! $data['news']->title !!}</h4>
                        </div>
                        <div class="figure d-block">
                            @if($data['type'] == 'news')
                                <div class="imgheading">{{ $data['news']->category->category_name }}</div>
                            @endif
                            <img src="{{ $data['news']->image }}">
                        </div>
                        <div class="row no-gutters date-t">
                            <div class="col-md-6"><i class="fa fa-user"></i>&nbsp;{{ $data['news']->creator->name }}</div>
                            <div class="col-md-6" style="text-align: end;"><i class="fa fa-calendar"></i>&nbsp;{{ date('M d, Y', strtotime($data['news']->created_at)) }}</div>
                        </div>
                        <br>
                        <div class="text">
                            @if($data['type'] == 'news')
                                <p>{!! nl2br($data['news']->newsdetail) !!}</p>
                            @else
                                <p>{!! nl2br($data['news']->details) !!}</p>
                            @endif
                        </div>
                        <div class="text">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="sochal-footer">
                                        <li>
                                            <a href="https://twitter.com/recruit_ie" target="_blank"><i class="fa fa-twitter"></i></a>
                                        </li>
                                        <li>
                                            <a href="https://www.facebook.com/recruitiejobs" target="_blank"><i class="fa fa-facebook"></i></a>
                                        </li>
                                        <li>
                                            <a href="https://www.instagram.com/recruit.ie/" target="_blank"><i class="fa fa-instagram"></i></a>
                                        </li>
                                        <li>
                                            <a href="https://www.linkedin.com/company/recruitie/" target="_blank"><i class="fa fa-linkedin"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($data['similar']))
                    <div class="col-md-4">
                        <div class="news-right-bar">
                            <h5>Similar News</h5>
                            <div class="row">
                                @foreach($data['similar'] as $news_st)
                                    <div class="col-lg-12 colmargin">
                                        <div class="item">
                                            <div class="atext">
                                                <p><a href="{{ route('blog-details', ['slug' => $news_st->slug, 'type' => $data['type']]) }}">{!! $news_st->title !!}</a></p>
                                            </div>
                                            <div class="figure" style="display:<?= ($data['type'] === 'all') ? 'inline-block' : 'block';?>">
                                                <img src="{{ $news_st->image }}">
                                            </div>
                                            <div class="row no-gutters date-t">
                                                <div class="col-md-6"><i class="fa fa-user"></i>&nbsp;{{ $news_st->creator->name }}</div>
                                                <div class="col-md-6" style="text-align: end;"><i class="fa fa-calendar"></i>&nbsp;{{ date('M d, Y', strtotime($news_st->created_at)) }}</div>
                                            </div>
                                            <div class="text">
                                                @if($data['type'] == 'news')
                                                    <div class="describe">
                                                        {!! substr(strip_tags(trim(preg_replace('/\[[^\]]*\]/', '', $news_st->newsdetail))), 0, 140) !!}...
                                                    </div>
                                                @else
                                                    <div class="describe">
                                                        {!! substr(strip_tags(trim(preg_replace('/\[[^\]]*\]/', '', $news_st->details))), 0, 140) !!}...
                                                    </div>
                                                @endif

                                                <a class="btn" href="{{ route('blog-details', ['slug' => $news_st->slug, 'type' => $data['type']]) }}">Read More</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-lg-12 colmargin">
                                    <a class="w-100 px-5 py-2 btn btn-danger text-white" href="{{ route('blogs', ['type' => $data['type']]) }}">Show More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL')?>';
    </script>
@endsection
