<?php

namespace App\Services\Payment;

use App\Enum\Payments\SubscriptionStatusEnum;
use App\Models\Plan;
use App\Models\PlanPackage;
use App\Models\Subscription;
use Carbon\Carbon;

class PaymentService
{
    private $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function create_or_get_stripe_customer($user)
    {
        $user->createOrGetStripeCustomer();
    }

    public function update_default_payment_method($user, $intent)
    {
        $user->updateDefaultPaymentMethod($intent);
    }

    public function store(array $subscriptionData): Subscription
    {
        $plan_package = PlanPackage::find($subscriptionData['plan_package_id']);
        $start_date = $this->store_start_date($subscriptionData['user_id']);
        $estimated_end_time = clone $start_date;
        $estimated_end_time->addMonths($plan_package->number_of_month);
        return Subscription::create([
            'plan_package_id'       => $plan_package->id,
            'user_id'               => $subscriptionData['user_id'],
            'start_date'            => $start_date,
            'estimated_end_time'    => $estimated_end_time,
            'status'                => $this->store_subscription($subscriptionData['user_id']),
            'payment_token'         => $subscriptionData['payment_token']
        ]);
    }

    private function store_subscription($user_id): string
    {
        $in_use_subscription =  Subscription::where([
            'user_id'       => $user_id,
            'status'        => SubscriptionStatusEnum::IN_USE->value,
        ])->first();

        return $in_use_subscription->id ? SubscriptionStatusEnum::PURCHASED->value : SubscriptionStatusEnum::IN_USE->value;
    }

    private function store_start_date($user_id): Carbon
    {
        $purchased_subscription =  Subscription::where([
            'user_id'       => $user_id,
            'status'        => SubscriptionStatusEnum::PURCHASED->value,
        ])->count();

        $in_use_subscription =  Subscription::where([
            'user_id'       => $user_id,
            'status'        => SubscriptionStatusEnum::IN_USE->value,
        ])->first();

        if ($purchased_subscription == 0 && $in_use_subscription->id) {
            return Carbon::parse($in_use_subscription->estimated_end_date)->addSeconds(5);
        }

        return Carbon::now();
    }

    public function checkSubscription($user)
    {
        if (!$user?->valid_subscription?->balanceIsValid()) {
            if ($subscriptionPurchased = checkUserHasPurchasedSubscription($user)) {
                $subscriptionPurchased->update([
                    "status" => SubscriptionStatusEnum::IN_USE->value,
                    "estimated_end_date" => now()->addMonths($subscriptionPurchased->plan_package->number_of_month),
                    "reel_end_date" => now()->addMonths($subscriptionPurchased->plan_package->number_of_month),
                ]);
            }
        }
    }

    public function find_invoices_by_user(int $id)
    {
        return Subscription::where('user_id', $id)->with(['plan_package', 'plan_package.plan', 'slots'])->get();
    }

    public function download_invoice($id)
    {
        return $this->stripeService->download_invoice($id);
    }

    public function download_invoice_charge($id)
    {
        return $this->stripeService->download_invoice_charge($id);
    }
}
