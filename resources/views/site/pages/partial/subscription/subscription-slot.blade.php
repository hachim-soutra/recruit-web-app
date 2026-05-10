
    {{-- <!--PRICE CONTENT START-->
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
    <!--//PRICE CONTENT END--> --}}


<div class="col-lg-3 mt-3">
    <div class="pricing-card h-100">
     {{-- @dd($slot) --}}
   
        <h3 class="fw-bold mb-2 text-uppercase">{{ $slot->title}}</h3>
        <p class="text-muted small mb-4">
            {{ $slot->description }}
        </p>
        <div class="mb-4">
            <span class="price-val ">€{{ $slot->price }}</span>
        </div>
        <div class="feature-list flex-1">
            <div class="feature-item">
                <i class="fa fa-check-circle"></i>
                <div class="feature-text"><b>{{ $slot->good_number }} slot {{ $slot->good_number > 1 ? 'available' : 'available' }}</b></div>
            </div>
        </div>
        <a class="btn btn-action w-100 btn-outline-dark" href="{{ route('choose-slot', ['id' => $slot->stripe_plan])  }}">
            Buy now
        </a>
    </div>
</div>