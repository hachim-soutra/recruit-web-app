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
        .price-val { font-size: 1.6rem; font-weight: 900; color: #111827; line-height: 1; }
        .price-sub { color: #64748b; font-size: 0.8rem; font-weight: 500; }
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

        /* Container Wrapper */
        .pricing-container-wrapper {
            position: relative;
            max-width: 100%;
            margin: 0 auto;
        }

        /* Plans Container */
        .pricing-container {
            display: flex;
            gap: 1.5rem;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 0 3rem;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
        }

        .pricing-container::-webkit-scrollbar {
            display: none; /* Chrome/Safari */
        }

        /* Individual Plan Item */
        .pricing-item {
            flex: 0 0 auto;
            width: 350px;
            max-width: 350px;
        }

        /* Scroll Buttons */
        .scroll-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 2px solid #ddd;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .scroll-btn:hover {
            background: #f8f9fa;
            border-color: #999;
            transform: translateY(-50%) scale(1.1);
        }

        .scroll-btn:active {
            transform: translateY(-50%) scale(0.95);
        }

        .scroll-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }

        .scroll-btn-left {
            left: 0;
        }

        .scroll-btn-right {
            right: 0;
        }

        /* Scroll Indicators */
        .scroll-indicators {
            display: none;
            justify-content: center;
            gap: 8px;
            margin-top: 1rem;
        }

        .scroll-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .scroll-indicator.active {
            background: #dc3545;
            width: 24px;
            border-radius: 4px;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .pricing-container {
                padding: 0 3rem;
                gap: 1rem;
                scroll-snap-type: x mandatory;
            }

            .pricing-item {
                scroll-snap-align: center;
                width: calc(100% - 2rem);
                max-width: calc(100% - 2rem);
            }

            .scroll-btn {
                width: 36px;
                height: 36px;
            }

            .scroll-btn-left {
                left: 0.25rem;
            }

            .scroll-btn-right {
                right: 0.25rem;
            }

            /* Show indicators on mobile */
            .scroll-indicators {
                display: flex;
            }
        }

        @media (max-width: 480px) {
            .pricing-item {
                width: calc(100% - 1rem);
                max-width: calc(100% - 1rem);
            }
        }
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
            <div class="pricing-container-wrapper position-relative p-3">
                <!-- Scroll Button - Left -->
                <button class="scroll-btn scroll-btn-left" id="scrollLeft" aria-label="Scroll left">
                    <i class="fa fa-chevron-left"></i>
                </button>

                <!-- Plans Container -->
                <div class="pricing-container" id="pricingContainer">
                    @foreach ($sortedPlans as $plan)
                        @include('site.pages.partial.subscription.subscription-item')
                    @endforeach
                </div>

                <!-- Scroll Button - Right -->
                <button class="scroll-btn scroll-btn-right" id="scrollRight" aria-label="Scroll right">
                    <i class="fa fa-chevron-right"></i>
                </button>

                <!-- Optional: Scroll Indicators (dots) for mobile -->
                <div class="scroll-indicators" id="scrollIndicators"></div>
            </div>
        @endif
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('pricingContainer');
        const scrollLeftBtn = document.getElementById('scrollLeft');
        const scrollRightBtn = document.getElementById('scrollRight');
        const indicatorsContainer = document.getElementById('scrollIndicators');

        // Calculate scroll amount (one card width + gap)
        function getScrollAmount() {
            const isMobile = window.innerWidth <= 768;
            if (isMobile) {
                return container.offsetWidth; // Scroll one full card on mobile
            } else {
                return 380; // Card width + gap for desktop
            }
        }

        // Scroll left
        scrollLeftBtn.addEventListener('click', function() {
            container.scrollBy({
                left: -getScrollAmount(),
                behavior: 'smooth'
            });
        });

        // Scroll right
        scrollRightBtn.addEventListener('click', function() {
            container.scrollBy({
                left: getScrollAmount(),
                behavior: 'smooth'
            });
        });

        // Update button states
        function updateButtonStates() {
            const scrollLeft = container.scrollLeft;
            const maxScroll = container.scrollWidth - container.clientWidth;

            scrollLeftBtn.disabled = scrollLeft <= 0;
            scrollRightBtn.disabled = scrollLeft >= maxScroll - 1;
        }

        // Create scroll indicators for mobile
        function createIndicators() {
            const items = container.querySelectorAll('.pricing-item');
            indicatorsContainer.innerHTML = '';

            items.forEach((item, index) => {
                const indicator = document.createElement('span');
                indicator.className = 'scroll-indicator';
                indicator.addEventListener('click', () => {
                    item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                });
                indicatorsContainer.appendChild(indicator);
            });
        }

        // Update active indicator
        function updateIndicators() {
            const indicators = indicatorsContainer.querySelectorAll('.scroll-indicator');
            const items = container.querySelectorAll('.pricing-item');

            let activeIndex = 0;
            const containerCenter = container.scrollLeft + container.clientWidth / 2;

            items.forEach((item, index) => {
                const itemCenter = item.offsetLeft + item.offsetWidth / 2;
                if (Math.abs(containerCenter - itemCenter) < item.offsetWidth / 2) {
                    activeIndex = index;
                }
            });

            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === activeIndex);
            });
        }

        // Initialize
        updateButtonStates();
        if (window.innerWidth <= 768) {
            createIndicators();
            updateIndicators();
        }

        // Listen to scroll events
        container.addEventListener('scroll', function() {
            updateButtonStates();
            if (window.innerWidth <= 768) {
                updateIndicators();
            }
        });

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                updateButtonStates();
                if (window.innerWidth <= 768) {
                    createIndicators();
                    updateIndicators();
                } else {
                    indicatorsContainer.innerHTML = '';
                }
            }, 250);
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                scrollLeftBtn.click();
            } else if (e.key === 'ArrowRight') {
                scrollRightBtn.click();
            }
        });
    });
</script>
