<?php

namespace App\Services\PaymentProcessors;

use App\Enum\Payments\SubscriptionStatusEnum;
use App\Http\Requests\Site\Payment\PostSubscriptionRequest;
use App\Models\SubscriptionSlot;
use App\Models\User;

class PayWithStripe implements PayStrategy
{
    public function createSetupIntent()
    {
        return auth()->user()->createSetupIntent();
    }

    public function createSubscription(User $user, $planPackage, PostSubscriptionRequest $request)
    {
        $user->createOrGetStripeCustomer();
        if ($user->hasDefaultPaymentMethod()) {
            $user->updateDefaultPaymentMethodFromStripe($request->get('intent'));
        } else {
            $user->addPaymentMethod($request->get('intent'));
        }
        $result = $user->newSubscription($planPackage->plan->title, $request->get('stripe_plan'))
            ->create($request->paymentMethodId);
        $result->update([
            "subscription_id" => $result->stripe_id,
            "plan_package_id" => $planPackage->id,
            "stripe_id" => $request->paymentMethodId,
            "estimated_end_date" => now()->addMonths($planPackage->number_of_month),
            "reel_end_date" => now()->addMonths($planPackage->number_of_month),
            "status" => !$user?->valid_subscription?->balanceIsValid() ? SubscriptionStatusEnum::IN_USE->value : SubscriptionStatusEnum::PURCHASED->value
        ]);
    }
    public function createSlot($user, $slot, $request)
    {
        $user->createOrGetStripeCustomer();
        $user->addPaymentMethod($request->get('intent'));
        $charge = $user->charge($slot->price * 100, $request->paymentMethodId);
        $subscription = auth()->user()->subscriptions()
            ->where("status", SubscriptionStatusEnum::IN_USE->value)
            ->firstOrFail();

        $subscriptionSlot = new SubscriptionSlot();
        $subscriptionSlot->slot_id = $slot->id;
        $subscriptionSlot->subscription_id = $subscription->id;
        $subscriptionSlot->payment_method_token = $request->paymentMethodId;
        $subscriptionSlot->charge_token = $charge->charges->data[0]["id"];
        $subscriptionSlot->save();
    }
}
