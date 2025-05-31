<?php

namespace App\Jobs\Plan;

use App\Enum\Payments\SubscriptionStatusEnum;
use App\Helpers\MyHelper;
use App\Models\Employer;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Payment\StripeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AssignEmployersToPlanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public string $priceToken, public int $planPackage, public array $employers)
    {
        $this->planPackage = $planPackage;
        $this->priceToken = $priceToken;
        $this->employers = $employers;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(StripeService $stripeService)
    {
        $data['price_token'] = $this->priceToken;
        foreach ($this->employers as $employer) {
            $employer = Employer::find($employer);
            $data['customer'] = $employer?->user?->createOrGetStripeCustomer()->id;
            $subscriptionModel = new Subscription();
            $subscriptionModel->user_id = $employer?->user->id;
            $subscriptionModel->status = SubscriptionStatusEnum::WAITING->value;
            $subscriptionModel->plan_package_id = $this->planPackage;
            $subscriptionModel->stripe_status = 'inactive';
            $subscriptionModel->save();
            $data['subscription_id'] = $subscriptionModel->id;
            $paymentLink = $stripeService->create_checkout_sessions($data);
            $subscriptionModel->payment_link = $paymentLink;
            $subscriptionModel->save();
        }
    }
}
