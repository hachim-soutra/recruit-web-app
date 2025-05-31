@extends('site.layout.app')
@section('title', 'Subscription')
@section('mystyle')
    <style>
        #card-button {
            background-color: #ed1c24 !important;
            border-color: #ed1c24 !important;
            color: white;
            border-radius: 4px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            padding: 0.3rem 3rem;
        }

        .ElementsApp p {
            font-size: 14px !important;
        }

        .my-input {
            padding: 10px;
            border: 1px solid #ccc;
        }
    </style>
@endsection
@section('content')
    <!-- banner-block -->
    <?php
    $price = floatval(@$planPackage->price);
    $vat = floatval((@$planPackage->price * 23) / 100);
    $totalprice = floatval(@$planPackage->price + $vat);
    ?>
    <!-- post-resume-block -->
    <div class="post-resume-one ">
        <div class="container my-5">
            <div class="text-center">

                <h2>Checkout form</h2>
                <p class="lead">Congratulations on your choice to elevate your experience with our subscription service!
                    You're just a step away from unlocking a world of exclusive benefits and content. To complete your
                    subscription and start enjoying all the perks, simply proceed to our secure checkout.</p>
            </div>
            <div class="bd-block">
                <div class="row bg-white py-3 p-md-5">
                    <div class="col-md-4 order-md-2 mb-4">
                        <h4 class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Your cart</span>
                        </h4>
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Product name</h6>
                                    @if (Route::is('choose-subscription'))
                                        <small class="text-muted"> {{ $planPackage->plan->title }}</small>
                                    @else
                                        <small class="text-muted"> {{ $planPackage->title }}</small>
                                    @endif
                                </div>
                                <span class="text-muted">{{ @$settings->currency }}
                                    {{ number_format((float) @$planPackage->price, 2, '.', '') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>VTA 23% ({{ @$settings->currency }})</span>
                                <strong>{{ number_format((float) $vat, '2', '.', '') }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total ({{ @$settings->currency }})</span>
                                <strong>{{ number_format((float) $vat + $planPackage->price, '2', '.', '') }}</strong>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-8 order-md-1">
                        <h4 class="mb-3">Billing address</h4>
                        <div class="needs-validation">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName">First name</label>
                                    <input type="text" class="form-control" id="firstName" placeholder=""
                                        value="{{ auth()->user()->first_name }}" required="" readonly>
                                    <div class="invalid-feedback">
                                        Valid first name is required.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName">Last name</label>
                                    <input type="text" class="form-control" id="lastName" placeholder=""
                                        value="{{ auth()->user()->last_name }}" required="" readonly>
                                    <div class="invalid-feedback">
                                        Valid last name is required.
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="you@example.com"
                                    value="{{ auth()->user()->email }}" readonly>
                                <div class="invalid-feedback">
                                    Please enter a valid email address for shipping updates.
                                </div>
                            </div>

                            <hr class="mb-4">

                            <h4 class="mb-3">Payment</h4>

                            <div class="d-block my-3 ml-3">
                                <label class="custom-control-label" style="font-size: 1rem" for="credit">
                                    <i class="fa fa-brands fa-cc-stripe fa-lg"></i> Stripe</label>

                            </div>

                            <div id="card-element" class="my-input"></div>
                            <hr class="mb-4">
                            <button id="card-button" data-secret="{{ $intent->client_secret }}"
                                class="btn btn-primary btn-lg btn-block" type="button"> <i id="loading"
                                    class="fa fa-spinner fa-spin d-none"></i> Continue to checkout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- post-resume-block -->
    @endsection

    @section('myscript')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
            integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
            integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer" />

        <script src="https://js.stripe.com/v3/"></script>

        <script>
            const stripe = Stripe('{{ config('services.stripe.key') }}', {
                locale: 'en',
                theme: 'flat',
                variables: {
                    colorPrimaryText: '#262626'
                }
            });
            const amountString = {{ $vat + $planPackage->price }} * 100;
            const elements = stripe.elements({
                loader: 'auto',
                mode: 'payment',
                currency: 'eur',
                amount: amountString,
            });
            const cardElement = elements.create('card', {
                hidePostalCode: true,
                style: {
                    base: {
                        // iconColor: 'red',
                        color: '#000',
                        fontWeight: '500',
                        fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                        fontSize: window.matchMedia('(max-width: 600px)').matches ? '17px' : '20px',

                        '::placeholder': {
                            color: 'gray',
                            fontWeight: '300',

                        },
                    },
                    invalid: {
                        iconColor: 'red',
                        color: 'red',
                    },
                },
                layout: {
                    type: 'accordion',
                    defaultCollapsed: false,
                    radios: true,
                    spacedAccordionItems: false
                }
            });
            cardElement.mount('#card-element');
            const cardHolderName = document.getElementById('card-holder-name');
            const cardButton = document.getElementById('card-button');
            const loading = document.getElementById('loading');
            const clientSecret = cardButton.dataset.secret;
            cardButton.addEventListener('click', async (e) => {
                loading.classList.remove("d-none");
                cardButton.disabled = true;
                const {
                    setupIntent,
                    error
                } = await stripe.confirmCardSetup(
                    clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: "{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}",
                            }
                        }
                    }
                );
                if (error) {
                    toastr.error(error.message);
                    loading.classList.add("d-none");
                    cardButton.disabled = false;
                    cardElement.clear();
                } else {
                    $.ajax({
                        url: "{{ Route::is('choose-subscription') ? route('subscription.create') : route('slot.create') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            stripe_plan: "{{ $planPackage->stripe_plan }}",
                            intent: setupIntent.payment_method,
                            paymentMethodId: setupIntent.payment_method
                        },
                        success: function(res) {
                            toastr.success(res.msg);
                            setTimeout(() => {
                                window.location = res.url;
                            }, 1000);
                            loading.classList.add("d-none");
                            cardElement.clear();
                        },
                        error: function(err) {
                            console.log(err);
                            loading.classList.add("d-none");
                            cardButton.disabled = false;
                            cardElement.clear();
                        }
                    });
                }
            });
        </script>


    @endsection
