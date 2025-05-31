@extends('site.layout.app')
@section('title', 'Subscription')
@section('mystyle')
    <style>
        .h4 h4{
            text-align: center;
        }
        .top{
            margin-bottom: 10px !important;
        }
        .top .lt{
            border: 1px solid;
            text-align: justify;
            border-radius: 8px;
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .title1{
            background-color: #0080003b;
            color: green;
        }
        .title2{
            background-color: #0080003b;
            color: green;
        }
        .title3{
            background-color: #0000ff24;
            color: blue;
        }
        .title4{
            background-color: #0000ff24;
            color: blue;
        }
        .title5{
            background-color: #ffc1074d;
            color: #795548;
        }
        .title6{
            background-color: #ffc1074d;
            color: #795548;
        }
        .title7{
            background-color: #ff572229;
            color: #ff5722;
            border-radius: 5px;
        }
        .title8{
            background-color: #ff572229;
            color: #ff5722;
            border-radius: 5px;
        }
        .title9{
            background-color: #607d8b40;
            border-radius: 5px;
        }
        .title10{
            background-color: #607d8b40;
            border-radius: 5px;
        }
        .subscribe_title{
            background-color: #fff;
            color: #eb1829;
        }
        .error{
            text-align: center;
            margin-left: 25%;
        }
        .choose{
            background-color:#eb1829;
            color:white;
            width: 100%;
        }
        .mute-text{
            color: #9e9e9e;
            font-size: 85px;
        }
        .titletext{
            font-size: 30px;
            width: 100%;
            display: flex;
            justify-content: center;
            font-weight: 900;
        }
        .small{
            font-size: 95% !important;
            color: #eb1829;
            font-weight: 900;
        }
        .originalprice{
            margin-bottom: 5px !important;
        }
        .post-resume-one .bd-block .item-col .middle .lt p{
            color: black !important;
        }
    </style>
@endsection
@section('content')
    <!-- banner-block -->

    <!-- post-resume-block -->
    <div class="post-resume-one">
        <div class="container">
            <div class="bd-block">
                <div class="row" style="margin-bottom: -3% !important;">
                    <div class="section-titel text-center mb-4 mb-lg-5 w-100">
                        <h2 class="text-center">Active Plan Details</h2>
                    </div>
                </div>
                @if(count((array)$data['subscription']) <= 0)
                    <div class="row" style="justify-content: center;padding: 7px;">
                        <div class="col-lg-6">
                            <div class="item-col">
                                <h3>One month free trail</h3>
                                <div class="middle">
                                    <div class="lt">
                                        @if(MyHelper::checkFreeAccess() == '1')
                                            Free trial expiry date: {{MyHelper::userRegistrationDate()}}
                                        @else
                                            @if(MyHelper::checkSubscriptionStatus() == '1')
                                                Next Renewal Date: {{MyHelper::nextNewalDate()}}
                                            @else
                                                You don't have any subscription.
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row" style="margin-bottom: -5% !important;">
                    <div class="section-titel text-center mb-4 mb-lg-5 w-100">
                        <h2 class="text-center">Choose a subscription</h2>
                        <p>Post your job to get quality candidates.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 error">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div  class="alert alert-warning" role="alert">
                                    {{$error}}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <script>
                                    activeEdit();
                                </script>
                            @endforeach
                        @endif
                        @if(session('success'))
                            <script>
                                toastr.success("{{session('success')}}");
                            </script>
                        @endif
                        @if(session('error'))
                            <script>
                                toastr.error("{{session('error')}}");
                            </script>
                        @endif
                    </div>
                </div>
                <!-- item lt-->
                @if(count((array)$data['subscription']) <= 0)
                    @php $count = 1; @endphp
                    @foreach($data['packages'] as $p)
                        <div class="row" style="justify-content: center;padding: 7px;" >
                            <div class="col-lg-6" onclick="chooseSubscription('{{ base64_encode($p->id) }}')">
                                <div class="item-col">
                                    <!--top-->
                                    <div class="top">
                                        <div class="lt title{{ $count++ }} titletext">{{ $p->title }}</div>
                                    </div>
                                    <!--top-->
                                    <!-- middle -->
                                    <div class="middle originalprice">
                                        <div class="lt">
                                        </div>
                                    </div>
                                    <!-- middle -->
                                    <!-- middle -->
                                    <div class="middle">
                                        <div class="lt">
                                            Package Price Per {{ strpos($p->title, "Yearly") !== false ? "Year" : "Month"  }}: &#128;{{ number_format((float)$p->vatprice, 2, '.', '') }}
                                            <p class="mute-text"><small class="small">Included VAT (@23%)</small></p>
                                        </div>
                                        <div class="rt">
                                            @if($p->number_of_job_post == '-1')
                                                Unlimited Job Listing
                                            @else
                                                Up to {{$p->number_of_job_post}} Job Listing
                                            @endif
                                        </div>

                                    </div>
                                    <!-- middle -->
                                    <!-- bottom -->
                                    <div class="bottom">
                                        <button type="button" value="Choose" class="btn btn-submit choose">Choose&nbsp;({{ $p->title }})&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                                    </div>
                                    <!-- bottom -->
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    @php
                        $renewaldate = 'next '.$data['subscription']->plan_interval;
                        $subscription_id = $data["subscription"]->id;
                    @endphp
                    <div class="row" style="justify-content: center;padding: 7px;">
                        <div class="col-lg-6">
                            <div class="item-col">
                                <!--top-->
                                <div class="top">
                                    <div class="lt subscribe_title titletext">{{ $data['subscription']->plan->title }}</div>
                                </div>
                                <!--top-->
                                <!-- middle -->
                                <div class="middle">
                                    <div class="lt">
                                        Dated: <i class="fa fa-calendar"></i>&nbsp;{{ date('d/m/y', strtotime($data['subscription']->plan_period_start)) }}
                                    </div>
                                    <div class="rt">
                                        Package Amount: {{$settings->currency}} {{$data["subscription"]->plan->price}}
                                    </div>
                                </div>
                                <!-- middle -->
                                <!-- middle -->
                                <div class="middle">
                                    <div class="lt">
                                        <p>VAT (@23%): {{$settings->currency}} {{ ($data["subscription"]->plan->price*23)/100 }} (<small>round up {{round(($data["subscription"]->plan->price*23)/100)}}</small>)</p>

                                    </div>
                                    <div class="rt">
                                        Total Amount: {{$settings->currency}} {{$data["subscription"]->paid_amount}}
                                    </div>
                                </div>
                                <!-- middle -->
                                <!-- middle -->
                                <div class="middle">
                                    <div class="lt">
                                        Next Renewal Date: <i class="fa fa-calendar"></i>&nbsp;{{ date('d/m/y', strtotime($renewaldate, strtotime($data['subscription']->plan_period_start))) }}
                                    </div>
                                    <div class="rt">
                                        Post limit: {{ $data['subscription']->plan->number_of_job_post == '-1' ? 'Unlimited' : $data['subscription']->plan->number_of_job_post }}
                                    </div>
                                </div>
                                <!-- middle -->
                                <!-- bottom -->
                                <div class="bottom">
                                    <button type="button" value="Choose" onclick="subscriptionCancel('{{ $subscription_id }}')" class="btn btn-submit" style="background-color:#eb1829;color:white;border-radius: 5px;margin-top: 10px;width:100%;">Cancel</button>
                                </div>
                                <!-- bottom -->
                            </div>
                        </div>
                    </div>
                @endif
                <!-- item -->
            </div>
        </div>
    </div>
    <!-- post-resume-block -->
    <!-- cancel modal  -->
    <div class="modal" id="cancel_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Reason</label>
                            <textarea name="reason" id="reason" class="form-control" placeholder="Subscription Cancel Reason."></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="subscription_id" id="subscription_id">
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="cancelSubmit()" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL')?>';
        function subscriptionCancel(subscription_id){
            if(!confirm("Do you want to cancel subscription?This process cannot be undone!!!"))return false;
            $('#reason').val('');
            $('#subscription_id').val(subscription_id);
            $('#cancel_modal').modal('show');
        }
        function cancelSubmit(){
            let reason = ($('#reason').val() == '') ? 'No Reason' : $('#reason').val();
            let subscription_id = $('#subscription_id').val();
            $.ajax({
                url: "{{ route('cancel-subscription') }}",
                type: 'POST',
                data: {_token: '{{ csrf_token() }}', cancel_reason: reason, subscription_id: subscription_id},
                success: function(res){
                    $('#cancel_modal').modal('hide');
                    if(res.code == '200'){
                        toastr.success(res.msg);
                        location.reload();
                    };
                    if(res.code == '500'){
                        toastr.error(res.msg);
                    };
                    if(res.code == '403'){
                        let err = res.msg;
                        if(err.hasOwnProperty('subscription_id')){
                            for(let j = 0;j < err.subscription_id.length;j++){
                                toastr.error("Something Went Wrong.");
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }

                        };
                    }
                },
                error: function(err){
                    console.log(err);
                }
            });
        }
        function chooseSubscription(package_id){
            location.href="{{ route('choose-subscription2') }}" + '/' + package_id;
        }
        setTimeout(() => {
            $('.alert').hide();
        }, 2500);
    </script>
@endsection
