@extends('site.layout.app')
@section('title', 'About Us')
@section('mystyle')
    <style>
        /*  .box{
                    width: 15%;
                    height: 100%;
                    object-fit: cover;
                }
                .text h4 span{
                    color: #ed1c24;
                    font-weight: 500;
                    text-align: left;
                    font-family: 'Poppins', sans-serif;
                    font-size: 13px;
                }
                .text p span{
                    font-weight: 500;
                    text-align: left;
                    font-family: 'Poppins', sans-serif;
                    font-size: 13px;
                }*/
    </style>
@endsection
@section('content')
    <!-- banner-block -->
    <div class="our-news-block bg-light-red">
        <div class="container">
            <div class="title-block mb-4">
                <h1>Career Coach</h1>
            </div>
            <div class="row">
                @forelse($coach as $c)
                    @if (@$c->user->name != '' || @$c->user->email != '')
                        <div class="col-sm-6 col-lg-4" onclick="showCoachDetail('{{ $c->user_id }}')">
                            <div class="item career-coachmain">
                                <div class="figure box" style="display:inline-block">
                                    <img class=""
                                        src="{{ @$c->user->avatar != '' ? @$c->user->avatar : env('APP_URL') . '/' . 'uploads/noimg.jpg' }}">
                                </div>
                                <div class="text describe-text equalheight newsdsk">
                                    <h4><b>Name : </b> <span>{{ @$c->user->name != '' ? @$c->user->name : '' }}</span>
                                    </h4>
                                    @if (@$c->university_or_institute != '')
                                        <p><b>Company Name : </b> <span>{{ @$c->university_or_institute }}</span></p>
                                    @endif
                                    <p><b><i class="fa fa-envelope"></i></b> <span><a
                                                href="mailto:{{ @$c->user->email }}">{{ @$c->user->email != '' ? @$c->user->email : '' }}</a></span>
                                    </p>
                                    @if (@$c->user->mobile != '')
                                        <p><b><i class="fa fa-phone"></i></b> <span><a
                                                    href="tel:{{ @$c->user->mobile }}">{{ @$c->user->mobile != '' ? @$c->user->mobile : '' }}</a></span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-sm-6">
                        <p>No Data Found.</p>
                    </div>
                @endforelse
                <div class="col-md-12" style="display:flex;justify-content:center;margin-top: 40px;">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';

        function showCoachDetail(coachid) {
            location.href = "{{ route('coach-detail') }}" + '/' + coachid;
        }
    </script>
@endsection
