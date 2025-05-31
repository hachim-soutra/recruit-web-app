<div class="col-md-4 mx-auto mb-4">
    <!--PRICE CONTENT START-->
    <div class="generic_content clearfix h-100">
        <!--HEAD PRICE DETAIL START-->
        <div class="generic_head_price clearfix bg-white">
            <!--HEAD CONTENT START-->
            <div class="generic_head_content clearfix">
                <!--HEAD START-->
                <div class="head_bg"></div>
                <div class="head">
                    <span>{{ $slot->title }}</span>
                </div>
                <!--//HEAD END-->

            </div>
            <!--//HEAD CONTENT END-->
            <div class="row h-100">
                <div class="col-12 row m-0 p-0">
                    <div class="col-12 d-flex flex-column justify-content-between">
                        <div class="generic_price_btn clearfix">
                            <a href="{{ route('choose-slot', ['id' => $slot->stripe_plan]) }}">
                                Buy {{ $slot->good_number }} slot /{{ $slot->price }} €
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--//PRICE CONTENT END-->

</div>
