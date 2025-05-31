@extends('site.layout.app')
@section('title', 'News')
@section('mystyle')

@endsection
@section('content')
<!-- banner-block -->	
<div class="our-news-block bg-light-red">
    <div class="container">
        <div class="title-block">
            <h1 class="newsh">News <?= ($data['type'] === 'all') ? '' : ''?></h1>
        </div>
        <div class="row">
            @foreach($data['news'] as $n)
            <div style="padding: 10px 10px 10px 10px;" class="col-lg-<?= ($data['type'] === 'all') ? 4 : 8?>">
                <div class="item" style="<?= ($data['type'] === 'all') ? '' : 'box-shadow:none;border:none;'?>">
                    <?php if($data['type'] === 'all'){ ?>
                        <div class="atext">
                            <h4><a href="{{ route('news-details', ['slug' => $n->slug]) }}">{{ substr($n->title, 0, 35) }} ..</a></h4>
                        </div>
                    <?php }else{ ?>
                        <div class="text">
                            <h4>{{ $n->title }}</h4>
                        </div>
                    <?php } ?>
                    <div class="figure" style="display:<?= ($data['type'] === 'all') ? 'inline-block' : 'block';?>">
                        <div class="imgheading">{{ $n->category->category_name }}</div>
                        <img src="{{ env('APP_URL').'/uploads/'.($n->image) }}">
                    </div>
                    <div class="row no-gutters date-t">
                        <div class="col-md-6"><i class="fa fa-user"></i>&nbsp;{{ $n->creator->name }}</div>
                        <div class="col-md-6" style="text-align: end;"><i class="fa fa-calendar"></i>&nbsp;{{ date('M d, Y', strtotime($n->created_at)) }}</div>
                    </div>
                    <?php if($data['type'] === 'all'){ ?>
                        <div class="text">
                            <div class="describe">
                                <p>{!! substr(strip_tags($n->newsdetail), 0, 186) !!} ...</p>
                            </div>
                            <a class="btn" href="{{ route('news-details', ['slug' => $n->slug]) }}">Read More</a>
                        </div>
                    <?php }else{ ?>
                        <br>
                        <div class="text">
                            <p>{!! nl2br(strip_tags($n->newsdetail)) !!}</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
            @endforeach
            @if($data['type'] === 'all')
                <div class="col-md-12" style="display:flex;justify-content:center;margin-top: 10px;">
                    {{ $data['news']->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                </div>
            @endif
            @if(!empty($data['similartype']))
            <div class="col-md-4">
                <h5 style="text-align:center;">Similar News</h5>
                @foreach($data['similartype'] as $news_st)
                <div class="col-lg-12 colmargin"> 
                    <div class="item">
                        <div class="atext">
                            <p><a href="{{ route('news-details', ['slug' => $news_st->slug]) }}">{{substr($news_st->title, 0, 35)}}...</a></p>
                        </div>
                        <div class="figure" style="display:<?= ($data['type'] === 'all') ? 'inline-block' : 'block';?>">
                            <img src="{{ env('APP_URL').'/uploads/'.($news_st->image) }}">
                        </div>
                        <div class="row" style="margin:10px;">
                            <div class="col-md-6"><i class="fa fa-user"></i>&nbsp;{{ $news_st->creator->name }}</div>
                            <div class="col-md-6" style="text-align: end;"><i class="fa fa-calendar"></i>&nbsp;{{ date('M d, Y', strtotime($news_st->created_at)) }}</div>
                        </div>
                        <div class="text">
                            <p>{!! substr(strip_tags($news_st->newsdetail), 0, 100) !!}...</p>
                            <a class="btn" href="{{ route('news-details', ['slug' => $news_st->slug]) }}">Read More</a>
                        </div>
                    </div>
                </div>
                @endforeach
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
