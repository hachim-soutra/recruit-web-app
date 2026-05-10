@extends('site.layout.app')
@section('title', 'Subscription')
@section('mystyle')
    <style>
        .error {
            text-align: center;
            margin-left: 25%;
        }
        
        body { background-color: #fcfcfd; color: #1a202c; font-family: \'Inter\', -apple-system, sans-serif; }
        .pricing-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 25px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .pricing-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 30px 60px -12px rgba(0,0,0,0.1);
            border-color: #ef4444;
        }
        .featured-card {
            border: 2px solid #ef4444;
            box-shadow: 0 20px 40px -10px rgba(239, 68, 68, 0.15);
        }
        .hot-badge {
            position: absolute;
            top: -15px;
            right: 30px;
            background: #ef4444;
            color: white;
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        .feature-list { flex-grow: 1; }
        .price-val { font-size: 2rem; font-weight: 900; color: #111827; line-height: 1; }
        .price-sub { color: #64748b; font-size: 1rem; font-weight: 500; }
        .feature-item { margin-bottom: 16px; display: flex; align-items: flex-start; }
        .feature-item i { color: #10b981; margin-right: 12px; font-size: 1.25rem; margin-top: -2px; }
        .feature-text b { color: #111827; }
        .feature-text span { display: block; font-size: 0.85rem; color: #64748b; margin-top: 2px; }
        .btn-action {
            padding: 18px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 1.1rem;
            text-transform: none;
            transition: all 0.3s;
            margin-top: 30px;
        }
        .btn-red { background: #ef4444; color: white; border: none; }
        .btn-red:hover { background: #dc2626; color: white; box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4); }
        .trust-badge { font-size: 0.8rem; color: #94a3b8; margin-top: 15px; }
        .max-width-700 { max-width: 700px; }

        /* Custom classes to replicate Bootstrap 5 utilities in Bootstrap 3 */
        .bg-danger-subtle { background-color: #f2dede; }
        .text-danger { color: #a94442; }
        .px-3 { padding-left: 15px; padding-right: 15px; }
        .py-2 { padding-top: 10px; padding-bottom: 10px; }
        .rounded-pill { border-radius: 500px; }
        .mb-3 { margin-bottom: 15px; }
        .fw-bold { font-weight: 700; }
        .display-4 { font-size: 2.5rem; font-weight: 300; line-height: 1.2; }
        .fw-black { font-weight: 900; }
        .lead { font-size: 21px; font-weight: 200; }
        .text-secondary { color: #777; }
        .mb-5 { margin-bottom: 25px; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .g-4 { margin-left: -15px; margin-right: -15px; }
        .g-4 > [class*=\'col-\\'\] { padding-left: 15px; padding-right: 15px; }
        .h-100 { height: 100%; }
        .mb-2 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 20px; }
        .w-100 { width: 100%; }
        .btn-action.w-100 { display: block; width: 100%; }

        /* Bootstrap 3 specific adjustments */
        .container { width: 970px; }
        .text-center { text-align: center; }
        .lead { font-size: 21px; }
        .mb-3 { margin-bottom: 15px; }
        .mb-4 { margin-bottom: 20px; }
        .mb-5 { margin-bottom: 25px; }
        .pb-5 { padding-bottom: 25px; }
        .px-3 { padding-left: 15px; padding-right: 15px; }
        .py-2 { padding-top: 10px; padding-bottom: 10px; }
        .rounded-pill { border-radius: 500px; }
        .fw-bold { font-weight: 700; }
        .fw-black { font-weight: 900; }
        .text-danger { color: #a94442; }
        .bg-danger-subtle { background-color: #f2dede; }
        .text-secondary { color: #777; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .g-4 { margin-left: -15px; margin-right: -15px; }
        .g-4 > [class*=\'col-\'] { padding-left: 15px; padding-right: 15px; }
        .small { font-size: 85%; }
        .text-muted { color: #777; }
        a:hover { text-decoration: none; color: white !important; }
        .btn-outline-dark { color: #333; background-color: transparent; border-color: #ccc; }
        .btn-outline-dark:hover { color: #333; background-color: #e6e6e6; border-color: #adadad; }
    </style>
@endsection
@section('content')
        @if ($subscriptionActive && $subscriptionActive->plan_package)
        <div class="container">
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
        </div>
        @else
            <div class="hero-section text-center">
                <div class="container">
                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mb-3 fw-bold">INVEST IN YOUR TEAM</span>
                    <h1 class="display-4 fw-black">Stop Searching. Start Hiring.</h1>
                        <p class="lead text-secondary mb-5 mx-auto max-width-700">
                        Every day your position stays open is a day of lost productivity. Choose the plan that puts your job in front of the <b>top 1% of talent</b> immediately.
                    </p>
                </div>
            </div>
            <div class="row justify-content-center px-5">
                @foreach ($sortedPlans as $plan)
                    @include('site.pages.partial.subscription.subscription-item')
                @endforeach
            </div>
        @endif
@endsection
