@extends('site.layout.app')
@section('title', 'Subscription')
@section('mystyle')
    <style>
        .error {
            text-align: center;
            margin-left: 25%;
        }

        #generic_price_table {
            background-color: #f0eded;
        }

        /*PRICE COLOR CODE START*/
        #generic_price_table .generic_content {
            background-color: #fff;
        }

        #generic_price_table .generic_content .generic_head_price {
            background-color: #f6f6f6;
        }

        #generic_price_table .generic_content .generic_head_price .generic_head_content .head_bg {
            border-color: #e4e4e4 rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) #e4e4e4;
        }

        #generic_price_table .generic_content .generic_feature_list ul li {
            color: #a7a7a7;
        }

        #generic_price_table .generic_content .generic_feature_list ul li span {
            color: #414141;
        }

        #generic_price_table .generic_content .generic_feature_list ul li:hover {
            background-color: #E4E4E4;
            border-left: 5px solid var(--red-color);
        }

        #generic_price_table .generic_content .generic_price_btn a {
            border: 1px solid var(--red-color);
            color: var(--red-color);
        }

        #generic_price_table .generic_content.active .generic_head_price .generic_head_content .head_bg,
        #generic_price_table .generic_content:hover .generic_head_price .generic_head_content .head_bg {
            border-color: var(--red-color) rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) var(--red-color);
            color: #fff;
        }

        #generic_price_table .generic_content:hover .generic_head_price .generic_head_content .head span,
        #generic_price_table .generic_content.active .generic_head_price .generic_head_content .head span {
            color: #fff;
        }

        #generic_price_table .generic_content:hover .generic_price_btn a,
        #generic_price_table .generic_content.active .generic_price_btn a {
            background-color: var(--red-color);
            color: #fff;
        }

        #generic_price_table {
            margin: 50px 0 50px 0;
            font-family: 'Raleway', sans-serif;
        }

        .row .table {
            padding: 28px 0;
        }

        /*PRICE BODY CODE START*/

        #generic_price_table .generic_content {
            overflow: hidden;
            position: relative;
            text-align: center;
        }

        #generic_price_table .generic_content {
            padding: 0 0 20px 0;
        }

        #generic_price_table .generic_content .generic_head_price .generic_head_content {
            margin: 0 0 50px 0;
        }

        #generic_price_table .generic_content .generic_head_price .generic_head_content .head_bg {
            border-style: solid;
            border-width: 90px 1411px 23px 399px;
            position: absolute;
        }

        #generic_price_table .generic_content .generic_head_price .generic_head_content .head {
            padding-top: 40px;
            position: relative;
            z-index: 1;
        }

        #generic_price_table .generic_content .generic_head_price .generic_head_content .head span {
            font-family: "Raleway", sans-serif;
            font-size: 28px;
            font-weight: 400;
            letter-spacing: 2px;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag {
            padding: 0 0 20px;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .price {
            display: block;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .price .sign {
            display: inline-block;
            font-family: "Lato", sans-serif;
            font-size: 28px;
            font-weight: 400;
            vertical-align: middle;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .price .currency {
            font-family: "Lato", sans-serif;
            font-size: 2rem;
            font-weight: 300;
            letter-spacing: -2px;
            padding: 0;
            vertical-align: middle;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .month {
            font-family: "Lato", sans-serif;
            font-size: 12px;
            font-weight: 400;
            vertical-align: bottom;
        }

        #generic_price_table .generic_content .generic_feature_list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #generic_price_table .generic_content .generic_feature_list ul li {
            font-family: "Lato", sans-serif;
            font-size: 18px;
            padding: 15px 0;
            transition: all 0.3s ease-in-out 0s;
        }



        #generic_price_table .generic_content .generic_feature_list ul li .fa {
            padding: 0 10px;
        }



        #generic_price_table .generic_content .generic_price_btn a {
            border-radius: 50px;
            -moz-border-radius: 50px;
            -ms-border-radius: 50px;
            -o-border-radius: 50px;
            -webkit-border-radius: 50px;
            display: inline-block;
            font-family: "Lato", sans-serif;
            font-size: 16px;
            outline: medium none;
            padding: 10px 24px;
            text-decoration: none;
        }

        #generic_price_table .generic_content,
        #generic_price_table .generic_content:hover,
        @media (max-width: 767px) {
            #generic_price_table .generic_content {
                margin-bottom: 75px;
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            #generic_price_table .col-md-3 {
                float: left;
                width: 50%;
            }

            #generic_price_table .col-md-4 {
                float: left;
                width: 50%;
            }

            #generic_price_table .generic_content {
                margin-bottom: 75px;
            }
        }

        a.nav-link.border-0.active {
            background-color: var(--red-color) !important;
            color: white !important;
            border-radius: 16px;
        }

        .border-tabs {
            border: 1px solid red !important;
            border-radius: 16px;
        }
    </style>
@endsection
@section('content')
    <!-- banner-block -->

    <!-- post-resume-block -->
    <div id="generic_price_table" class="mt-0">
        <div class="post-resume-one">
            <div class="container">
                <div class="bd-block">
                    <div class="row justify-content-start mb-5">

                        @if ($subscriptionActive && $subscriptionActive->plan_package)
                            @include('site.pages.partial.subscription.subscription-plan-item', [
                                'package' => $subscriptionActive->plan_package,
                                'slots' => $subscriptionActive->slots,
                            ])
                            @if (count($subscriptionActive->plan_package->slots) > 0)
                                <div class="col-md-12">
                                    <h5 class="mb-3">
                                        <a class="text-body" href="#">
                                            Add a job slot ({{ count($subscriptionActive->plan_package->slots) }})
                                        </a>
                                    </h5>
                                    <hr>

                                    @foreach ($subscriptionActive->plan_package->slots as $slot)
                                        @include('site.pages.partial.subscription.subscription-slot')
                                    @endforeach

                                </div>
                            @endif
                        @else
                            <div class="col-12 section-titel text-center my-5 w-100">
                                <h2 class="text-center">Choose a subscription</h2>
                                <p>Post your job to get quality candidates.</p>
                            </div>
                            @foreach ($plans as $plan)
                                @include('site.pages.partial.subscription.subscription-item')
                            @endforeach
                        @endif


                    </div>
                    <div class="row">
                        <div class="col-md-6 error">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-warning" role="alert">
                                        {{ $error }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                            @if (session('success'))
                                <script>
                                    toastr.success("{{ session('success') }}");
                                </script>
                            @endif
                            @if (session('error'))
                                <script>
                                    toastr.error("{{ session('error') }}");
                                </script>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('myscript')

    <script></script>
@endsection
