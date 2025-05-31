@extends('site.layout.app')
@section('title', 'Transaction History')
@section('mystyle')
    <style>
        .h4 {
            text-align: center;
        }

        .btn-success {
            color: #fff;
            background-color: #ed1c24;
            border-color: #ed1c24;
        }
    </style>
@endsection
@section('content')
    <!-- banner-block -->

    <!-- post-resume-block -->
    <div class="post-resume-one">
        <div class="container">
            <div class="bd-block">
                <div class="row">
                    <div class="col-lg-12 h4">
                        <h4>Subscription Transaction History</h4>
                    </div>
                </div>
                <!-- item lt-->
                @foreach($subscriptions as $t)
                    <div class="row" style="justify-content: center;padding: 7px;">
                        <div class="col-lg-6">
                            <div class="item-col">
                                <!--top-->
                                <div class="top">
                                    <div class="lt"><i
                                            class="fa fa-calendar"></i> {{ date("j M, Y", strtotime($t->created_at)) }}
                                    </div>
                                </div>
                                <!--top-->
                                <!-- middle -->
                                <div class="middle">
                                    <div class="lt">
                                        Payment for Job Post
                                    </div>
                                    <div class="rt">
                                        EUR/HTT&nbsp;&nbsp;€{{$t->plan_package->price }}
                                    </div>
                                </div>
                                <!-- middle -->
                                <!-- bottom -->
                                @if($t->subscription_id)
                                    <div class="bottom">
                                        <a target="_blank"
                                           href="{{ route('invoice', ['id' => $t->subscription_id]) }}">
                                            <button type="button" class="btn btn-success"><i class="fa fa-download"></i>Download
                                                Invoice
                                            </button>
                                        </a>
                                    </div>
                                @endif
                                <!-- bottom -->
                            </div>
                        </div>
                    </div>
                    @foreach($t->slots as $slot)
                        <div class="row" style="justify-content: center;padding: 7px;">
                            <div class="col-lg-6">
                                <div class="item-col">
                                    <!--top-->
                                    <div class="top">
                                        <div class="lt"><i
                                                class="fa fa-calendar"></i> {{ date("j M, Y", strtotime($slot->pivot->created_at)) }}
                                        </div>
                                    </div>
                                    <!--top-->
                                    <!-- middle -->
                                    <div class="middle">
                                        <div class="lt">
                                            Payment for Job Slot
                                        </div>
                                        <div class="rt">
                                            EUR/HTT&nbsp;&nbsp;€{{$slot->price }}
                                        </div>
                                    </div>
                                    <!-- middle -->
                                    <!-- bottom -->
                                    @if($slot->pivot->charge_token)
                                        <div class="bottom">
                                            <a target="_blank"
                                               href="{{ route('invoice-charge', ['id' => $slot->pivot->charge_token]) }}">
                                                <button type="button" class="btn btn-success"><i class="fa fa-download"></i>Download
                                                    Invoice
                                                </button>
                                            </a>
                                        </div>
                                    @endif
                                    <!-- bottom -->
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
                <!-- item -->
            </div>
        </div>
    </div>
    <!-- post-resume-block -->

@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';
    </script>
@endsection
