@extends('site.layout.app')
@section('title', 'Subscription')
@section('mystyle')
    <style>
        .h4 {
            text-align: center;
        }

        .form-row > .card {
            background-color: transparent !important
        }

        #coupon_section {
            display: none;
        }

        .card {
            border: none !important;
        }

        .confirmbtn {
            background-color: #ed1c24 !important;
            border-color: #ed1c24 !important;
        }
    </style>
@endsection
@section('content')
    <!-- post-resume-block -->
    <div class="post-resume-one">
        <div class="container">
            <div class="bd-block">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h4>Subscription Payment For {{ $detail->title }}</h4>
                    </div>
                </div>
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-warning" role="alert">
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
                <div class="container">
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class="item-col">
                                {{-- <form accept-charset="UTF-8" action="{{ route('start-subscription2') }}"
                                      class="require-validation"
                                      data-cc-on-file="false"
                                      id="payment-form" method="post">
                                    @csrf
                                    <div class='form-row'>

                                        <input type="hidden" name="plan_key" value="{{ $detail->plan_key }}">

                                        <div class='col-md-12 form-group card coupondiv' onclick="openCouponSection()">
                                            <label class='control-label applylabel'>Apply Coupon Code <i
                                                    class="fa fa-caret-down"></i></label>
                                            <div class='form-row' id="coupon_section">
                                                <div class='col-md-8 form-group'>
                                                    <input name="coupon_code" id="coupon_code" class='form-control mb-0'
                                                           type='text' placeholder="Enter Coupon Code">
                                                </div>
                                                <div class='col-md-4 form-group'>
                                                    <button type="button" disabled onclick="couponSubmit()"
                                                            class="btn btn-submit couponbtn"
                                                            style="background-color:#eb1829;color:white;border-radius: 5px; height: 52px; width: 100%;">
                                                        Apply
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        $price = floatval(@$detail->price);
                                        $vat = floatval((@$detail->price * @$detail->vat) / 100);
                                        $totalprice = floatval(@$detail->price + $vat);
                                        ?>

                                        <div class="col-md-12 form-group">
                                            <div class="row">
                                                <div class="col-md-6"><p><strong>Package
                                                            Price:</strong>&nbsp;&nbsp;{{ (@$settings->currency) }} {{ number_format((float)@$detail->price, 2, '.', '') }}
                                                    </p></div>
                                                <div class="col-md-6"><p><strong>VAT
                                                            (@23%):</strong>&nbsp;&nbsp;{{ (@$settings->currency) }} {{ number_format((float)$vat, '2', '.', '') }}
                                                    </p></div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group" style="margin-top: -25px;">
                                            <p><strong>Total
                                                    Price:</strong>&nbsp;&nbsp;{{ (@$settings->currency) }} {{ number_format((float)@$totalprice, '2', '.', '') }}
                                            </p>
                                        </div>


                                        <div class='col-md-12 form-group mb-0'>
                                            <button class='form-control mb-0 btn btn-primary submit-button confirmbtn'
                                                    type='submit' style="margin-top: 10px;">Confirm
                                                ({{ (@$settings->currency) }} {{ number_format((float)@$totalprice, '2', '.', '') }}
                                                )
                                            </button>
                                        </div>


                                        <div class='col-md-12 error mt-3 form-group' style="display:none">
                                            <div class='alert-danger alert'></div>
                                        </div>
                                    </div>
                                </form> --}}
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- post-resume-block -->

@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';
        setTimeout(() => {
            $('.alert').hide();
        }, 2500);
        $(document).ready(function () {
            $('.applylabel').html('Apply Coupon Code <i class="fa fa-caret-down"></i>');
            $('.couponbtn').prop('disabled', true);
            $('#coupon_code').val('');
            $('#coupon_section').hide();
        });

        function openCouponSection() {
            $('.applylabel').html('Apply Coupon Code <i class="fa fa-caret-up"></i>');
            $('#coupon_code').val('');
            $('#coupon_section').css('display', 'flex');
            // $('.coupondiv').removeAttr('onclick', 'openCouponSection()');
            // $('.coupondiv').attr('onclick', 'closeCouponSection()');
        }

        function closeCouponSection() {
            $('.applylabel').html('Apply Coupon Code <i class="fa fa-caret-down"></i>');
            $('.couponbtn').prop('disabled', true);
            $('#coupon_code').val('');
            $('#coupon_section').hide();
            $('.coupondiv').removeAttr('onclick', 'closeCouponSection()');
            $('.coupondiv').attr('onclick', 'openCouponSection()');
        }

        $('#coupon_code').on('keyup', function () {
            $('.couponbtn').prop('disabled', false);
        });

        function couponSubmit() {
            let coupon_code = $('#coupon_code').val();
            if (coupon_code != '') {
                $.ajax({
                    url: "{{ route('apply-coupon') }}",
                    type: 'POST',
                    data: {_token: '{{ csrf_token() }}', coupon_code: coupon_code},
                    success: function (res) {
                        if (res.code == '200') {
                            $('#coupon_code').val(coupon_code);
                            toastr.success(res.msg);
                        }
                        ;
                        if (res.code == '500') {
                            toastr.error(res.msg);
                        }
                        ;
                        if (res.code == '403') {
                            let err = res.msg;
                            if (err.hasOwnProperty('coupon_code')) {
                                for (let j = 0; j < err.coupon_code.length; j++) {
                                    toastr.error(err.coupon_code[j]);
                                }
                            };
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
                $('.couponbtn').prop('disabled', true);
            } else {
                toastr.error("Coupon Code Required.");
                return;
            }
        }
    </script>

@endsection
