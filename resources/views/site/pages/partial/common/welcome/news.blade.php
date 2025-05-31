<div class="our-news-block">
    <div class="container">
        <div class="title-block">
            <h2>Our Blogs</h2>
        </div>
        <div class="row">
            <div class="col-12">
                <nav class="nav nav-pills flex-row justify-content-center">
                    <a class="nav-item nav-link our-news-link active" href="#events" data-toggle="tab">Events</a>
                    <a class="nav-item nav-link our-news-link" href="#news" data-toggle="tab">News</a>
                    <a class="nav-item nav-link our-news-link" href="#advices" data-toggle="tab">Career Advice</a>
                </nav>
            </div>
            <div class="col-12 mt-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="events">
                        <div class="row justify-content-center">
                            @foreach ($events as $e)
                                <!-- item -->
                                <div class="col-lg-4 mb-4">
                                    <div class="item">
                                        <div class="atext equalheight">
                                            <h4><a
                                                    href="{{ route('blog-details', ['slug' => $e->slug, 'type' => 'events']) }}">{!! $e->title !!}</a>
                                            </h4>
                                        </div>
                                        <div class="figure">
                                            <img src="{{ $e->image }}" loading="lazy">
                                        </div>
                                        <div class="row date-t">
                                            <div class="col-md-6"><i
                                                    class="fa fa-user"></i>&nbsp;{{ $e->createdBy->name }}</div>
                                            <div class="col-md-6 text-right"><i
                                                    class="fa fa-calendar"></i>&nbsp;{{ date('M d, Y', strtotime($e->created_at)) }}
                                            </div>
                                        </div>
                                        <div class="text">
                                            <div class="describe">
                                                {!! substr(strip_tags(trim(preg_replace('/\[[^\]]*\]/', '', $e->details))), 0, 140) !!}
                                                @if ($e->details != '')
                                                    ...
                                                @endif
                                            </div>
                                            <a class="btn"
                                                href="{{ route('blog-details', ['slug' => $e->slug, 'type' => 'events']) }}">Read
                                                More</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- item -->
                            @endforeach
                        </div>
                        @if ($events)
                        @endif
                        <div class="row justify-content-center mt-3">
                            <div class="col-12 col-md-3 text-center">
                                <a class=" px-5 py-2 btn btn-danger text-white" href="{{ route('blogs', ['type' => 'events']) }}">Show More</a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="news">
                        <div class="row justify-content-center">
                            @foreach ($news as $n)
                                <!-- item -->
                                <div class="col-lg-4 mb-4">
                                    <div class="item">
                                        <div class="atext equalheight">
                                            <h4><a
                                                    href="{{ route('blog-details', ['slug' => $n->slug, 'type' => 'news']) }}">{!! $n->title !!}</a>
                                            </h4>
                                        </div>
                                        <div class="figure">
                                            <img src="{{ $n->image }}" loading="lazy">
                                        </div>
                                        <div class="row date-t">
                                            <div class="col-md-6"><i
                                                    class="fa fa-user"></i>&nbsp;{{ $n->creator->name }}</div>
                                            <div class="col-md-6 text-right"><i
                                                    class="fa fa-calendar"></i>&nbsp;{{ date('M d, Y', strtotime($n->created_at)) }}
                                            </div>
                                        </div>
                                        <div class="text">
                                            <div class="describe">
                                                {!! substr(strip_tags(trim(preg_replace('/\[[^\]]*\]/', '', $n->newsdetail))), 0, 140) !!}
                                                @if ($n->newsdetail != '')
                                                    ...
                                                @endif
                                            </div>
                                            <a class="btn"
                                                href="{{ route('blog-details', ['slug' => $n->slug, 'type' => 'news']) }}">Read
                                                More</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- item -->
                            @endforeach
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-12 col-md-3 text-center">
                                <a class=" px-5 py-2 btn btn-danger text-white"
                                    href="{{ route('blogs', ['type' => 'news']) }}">Show More</a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="advices">
                        <div class="row justify-content-center">
                            @foreach ($advices as $a)
                                <!-- item -->
                                <div class="col-lg-4 mb-4">
                                    <div class="item">
                                        <div class="atext equalheight">
                                            <h4><a
                                                    href="{{ route('blog-details', ['slug' => $a->slug, 'type' => 'advices']) }}">{!! $a->title !!}</a>
                                            </h4>
                                        </div>
                                        <div class="figure">
                                            <img src="{{ $a->image }}" loading="lazy">
                                        </div>
                                        <div class="row date-t">
                                            <div class="col-md-6"><i
                                                    class="fa fa-user"></i>&nbsp;{{ $a->creator->name }}</div>
                                            <div class="col-md-6 text-right"><i
                                                    class="fa fa-calendar"></i>&nbsp;{{ date('M d, Y', strtotime($a->created_at)) }}
                                            </div>
                                        </div>
                                        <div class="text">
                                            <div class="describe">
                                                {!! substr(strip_tags(trim(preg_replace('/\[[^\]]*\]/', '', $a->details))), 0, 140) !!}
                                                @if ($a->details != '')
                                                    ...
                                                @endif
                                            </div>
                                            <a class="btn"
                                                href="{{ route('blog-details', ['slug' => $a->slug, 'type' => 'advices']) }}">Read
                                                More</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- item -->
                            @endforeach
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-12 col-md-3 text-center">
                                <a class=" px-5 py-2 btn btn-danger text-white"
                                    href="{{ route('blogs', ['type' => 'advices']) }}">Show More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
