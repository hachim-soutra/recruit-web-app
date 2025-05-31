<div class="col-md-4 mb-5">

    <!--PRICE CONTENT START-->
    <div class="generic_content clearfix h-100">
        <!--HEAD PRICE DETAIL START-->
        <div class="generic_head_price clearfix bg-white">
            <!--HEAD CONTENT START-->
            <div class="generic_head_content clearfix">
                <!--HEAD START-->
                <div class="head_bg"></div>
                <div class="head">
                    <span>{{ $plan->title }}</span>
                </div>
                <!--//HEAD END-->

            </div>
            <!--//HEAD CONTENT END-->
            <div class="row h-100">
                <div class="col-12">
                    <div class="generic_feature_list mb-2">
                        <p class="h6 mb-3 border-b">{{ '' }}</p>
                        <hr>
                        <ul>
                            <li>
                                @if($plan->slug == 'Standard')
                                    <span>
                                        {{ $plan->job_number }} job slot in one month
                                    </span>
                                @elseif($plan->slug == 'Professional')
                                    <span>{{ $plan->job_number }} job slots in six months</span>
                                @elseif($plan->slug  == 'Premium')
                                    <span>{{ $plan->job_number }} job slots in one year</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 row m-0 p-0">
                    @foreach ($plan->packages as $package)
                        <div class="col-12 d-flex flex-column justify-content-between">
                            <!--PRICE START-->
                            <div class="generic_price_tag clearfix">
                                <span class="price">
                                    <span class="sign">€</span>
                                    <span class="currency">{{ $package->price }}</span>
                                </span>
                            </div>
                            <!--//PRICE END-->
                            <div class="generic_price_btn clearfix">
                                <a href="{{ route('choose-subscription', ['plan_packages' => $package]) }}">
                                    Buy {{ strtolower($plan->slug) }} plan
                                </a>
                            </div>
                            <div class="generic_price_tag clearfix">
                                <p class="price mt-3 px-2">
                                    {{ $plan->description }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


        </div>
        <!--//HEAD PRICE DETAIL END-->

        <!--FEATURE LIST START-->

        <!--//FEATURE LIST END-->

        <!--BUTTON START-->

        <!--//BUTTON END-->

    </div>
    <!--//PRICE CONTENT END-->

</div>
