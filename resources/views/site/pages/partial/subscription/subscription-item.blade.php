<div class="col-lg-3 mt-3">
    <div class="pricing-card h-100 {{ $plan->best_value ? 'featured-card' : '' }}">
        @if ($plan->best_value)
             <div class="hot-badge">BEST VALUE</div>
        @endif
        <h3 class="fw-bold mb-2 text-uppercase {{ $plan->best_value ? 'text-danger' : '' }}">{{ $plan->title }}</h3>
        <p class="text-muted small mb-4">
            {{ $plan->description }}
        </p>
        <div class="mb-4">
            @foreach ($plan->packages as $package)
                <span class="price-val {{ $plan->best_value ? 'text-danger' : '' }}">€{{ $package->price }}</span><span class="price-sub text-uppercase {{ $plan->best_value ? 'text-danger' : '' }}">/{{ $package->price_sub }}</span>
            @endforeach
            <div class="price-sub small">{{ $plan->job_number }} {{ $plan->title }} job {{ $plan->job_number > 1 ? 'slots' : 'slot' }} available.</div>
        </div>
        <div class="feature-list flex-1">
            @foreach ($plan->features as $feature)
                <div class="feature-item">
                    <i class="fa fa-check-circle"></i>
                    <div class="feature-text"><b>{{ $feature }}</b></div>
                </div>
            @endforeach
        </div>
        <a class="btn btn-action w-100 {{ $plan->best_value ? 'btn-red' : 'btn-outline-dark' }}" href="{{ route('choose-subscription', ['plan_packages' => $package]) }}">
            Buy {{ strtolower($plan->slug) }} plan
        </a>
        <div class="text-center trust-badge">{{ $plan->badge_text }}</div>
    </div>
</div>