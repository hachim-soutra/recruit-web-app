<?php

namespace App\Http\Controllers\Site;

use App\Enum\Payments\PlanTypeStatusEnum;
use App\Http\Controllers\Controller;
use App\Enum\Payments\SubscriptionStatusEnum;
use App\Enum\Payments\PlanStatusEnum;
use App\Enum\Payments\PlanForEnum;
use App\Models\PlanPackage;
use App\Models\Plan;
use App\Models\Slot;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = Plan::where([
            'status'        => PlanStatusEnum::ACTIVE,
            "plan_for"      => PlanForEnum::EMPLOYER,
            "plan_type"     => PlanTypeStatusEnum::SITE
        ])
        ->with(['packages'])->orderBy('id', 'asc')->get();
        $subscriptionActive = auth()->user()->subscriptions()
            ->where("status", SubscriptionStatusEnum::IN_USE->value)
            ->with(["slots"])
            ->first();
        $sortedPlans = $plans->sortBy(fn($plan) => $plan->packages->first()->price)->values();
        $biggestPlan = $sortedPlans->where('best_value', true)->first();
        if (!$biggestPlan) {
            $biggestPlan = $sortedPlans->last();
        }
        $sortedPlans = $sortedPlans->reject(fn($plan) => $plan->id === $biggestPlan->id)->values();
        $middleIndex = ceil($sortedPlans->count() / 2);

        $sortedPlans->splice($middleIndex, 0, [$biggestPlan]);
        return view('site.pages.subscription', compact('plans', 'subscriptionActive', 'sortedPlans', 'biggestPlan'));
    }

    public function chooseSubscription(string $id)
    {
        $planPackage = PlanPackage::where("stripe_plan", $id)->first();
        $paymentIntents = auth()->user()->stripe()->checkout->sessions->create(
            [
                'currency' => 'eur',
                'payment_method_types' => [],
                'line_items' => [[
                    'price' => $planPackage->stripe_plan,
                    'quantity' => 1,
                    'tax_rates' => [config('app.stripe.txr')],

                ]],
                'mode' => 'subscription',
                'success_url' => route('stripe_sub_test') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel-payement'),
            ]
        );
        return redirect()->away($paymentIntents->url);
    }

    public function chooseSlot(string $id)
    {
        $slot = Slot::where("stripe_plan", $id)->first();
        $paymentIntents = auth()->user()->stripe()->checkout->sessions->create(
            [
                'currency' => 'eur',
                'payment_method_types' => [],
                'line_items' => [
                    [
                        'price_data' => [
                            'unit_amount' => $slot->price * 100,
                            'product_data' => ['name' =>  $slot->title],
                            'currency' => 'eur',
                        ],
                        'tax_rates' => [config('app.stripe.txr')],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('stripe_payement_test') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel-payement'),
            ]
        );
        return redirect()->away($paymentIntents->url);
    }
}
