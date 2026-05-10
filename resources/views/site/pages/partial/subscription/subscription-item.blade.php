<style>
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

<div class="pricing-item {{ $plan->best_value ? 'mt-4' : 'mt-3' }}">
    <div class="pricing-card h-100 {{ $plan->best_value ? 'featured-card' : '' }}">
        @if ($plan->best_value)
            <div class="hot-badge">BEST VALUE</div>
        @endif
        <h5 class="fw-bold mb-2 text-uppercase {{ $plan->best_value ? 'text-danger' : '' }}">
            {{ $plan->title }}
        </h5>
        <p class="text-muted medium mb-4">
            {{ $plan->description }}
        </p>
        <div class="mb-4">
            @foreach ($plan->packages as $package)
                <span class="price-val {{ $plan->best_value ? 'text-danger' : '' }}">€{{ $package->price }}</span>
                <span class="price-sub text-uppercase {{ $plan->best_value ? 'text-danger' : '' }}">/{{ $package->price_sub }}</span>
            @endforeach
            <div class="price-sub small">
                {{ $plan->job_number }} {{ $plan->title }} job {{ $plan->job_number > 1 ? 'slots' : 'slot' }} available.
            </div>
        </div>
        <div class="feature-list flex-1">
            @foreach ($plan->features ?? [] as $feature)
                <div class="feature-item">
                    <i class="fa fa-check-circle"></i>
                    <div class="feature-text"><b>{{ $feature }}</b></div>
                </div>
            @endforeach
        </div>
        <a class="btn btn-action w-100 {{ $plan->best_value ? 'btn-red' : 'btn-outline-dark' }}"
           href="{{ route('choose-subscription', ['plan_packages' => $package]) }}">
            Buy plan
        </a>
        <div class="text-center trust-badge">{{ $plan->badge_text }}</div>
    </div>
</div>
