<?php

namespace App\Http\Controllers\Admin;

use App\Services\Payment\SubscriptionService;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use App\Enum\Payments\PlanStatusEnum;
use App\Enum\Payments\PlanForEnum;
use App\Enum\Payments\PlanTypeStatusEnum;
use App\Models\PlanPackage;
use App\Models\User;
use App\Services\Payment\StripeService;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use App\Enum\Payments\SubscriptionStatusEnum;

class SubscriptionController
{

    private $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function index(Request $request)
    {
        $keyword = request('keyword') ? request('keyword') : '';
        $data = $this->subscriptionService->find_all_waiting_subscription($keyword);
        return view('admin.subscription.list', compact('data', 'request'));
    }
    public function addManual(Request $request)
    {
        $plans = PlanPackage::whereHas('plan', function ($query) {
            $query->where([
                'status'    => PlanStatusEnum::ACTIVE,
                'plan_for'  => PlanForEnum::EMPLOYER,
                'plan_type' => PlanTypeStatusEnum::SITE,
            ]);
        })->with('plan')->get();

        // Filter out plans without Stripe plan IDs and add a warning if any are found
        $plansWithoutStripe = $plans->where('stripe_plan', null)->count();
        if ($plansWithoutStripe > 0) {
            $plans = $plans->where('stripe_plan', '!=', null);
            session()->flash('warning', "Warning: {$plansWithoutStripe} plan(s) were hidden because they don't have Stripe plan IDs configured.");
        }

        return view('admin.subscription.add', compact('plans'));
    }

    public function activateSubscription($id)
    {
        $data = $this->subscriptionService->activate_waiting_subscription($id);
        if ($data['result']) {
            return response()->json(['success' => $data]);
        } else {
            return response()->json(['error' => $data]);
        }
    }

    /**
     * Setup payment method for a user before creating subscription
     */
    public function setupPaymentMethod(Request $request, StripeService $stripeService)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where("email", $request->email)->first();

        try {
            // Create or get Stripe customer
            $stripeCustomer = $user->createOrGetStripeCustomer();

            // Create a setup intent for collecting payment method
            $setupIntent = $stripeService->createSetupIntent($stripeCustomer->id);

            return response()->json([
                'success' => true,
                'setup_intent' => [
                    'id' => $setupIntent->id,
                    'client_secret' => $setupIntent->client_secret,
                ],
                'customer_id' => $stripeCustomer->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to setup payment method: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Create a payment intent for users without payment methods
     */
    public function createPaymentIntent(Request $request, StripeService $stripeService)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        $user = User::where("email", $request->email)->first();

        try {
            // Create or get Stripe customer
            $stripeCustomer = $user->createOrGetStripeCustomer();

            // Create payment intent for bank transfer
            $paymentIntent = $stripeService->createBankTransferPaymentIntent(
                $stripeCustomer->id,
                (int)($request->amount * 100), // Convert to cents
                'eur',
                $request->description ?? 'Manual Payment'
            );

            return response()->json([
                'success' => true,
                'payment_intent' => [
                    'id' => $paymentIntent->id,
                    'status' => $paymentIntent->status,
                    'amount' => $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                ],
                'customer_id' => $stripeCustomer->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to create payment intent: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get customer's payment methods
     */
    public function getPaymentMethods(Request $request, StripeService $stripeService)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where("email", $request->email)->first();

        try {
            if (!$user->hasStripeId()) {
                return response()->json([
                    'success' => false,
                    'error' => 'User has no Stripe customer ID',
                ], 400);
            }

            $hasPaymentMethods = $stripeService->customerHasPaymentMethods($user->stripe_id);

            return response()->json([
                'success' => true,
                'has_payment_methods' => $hasPaymentMethods,
                'user_email' => $user->email,
                'stripe_customer_id' => $user->stripe_id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get payment methods: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Debug method to check plan package Stripe configurations
     */
    public function debugPlanPackages()
    {
        $planPackages = PlanPackage::with('plan')->get();

        $debug = [];
        foreach ($planPackages as $package) {
            $debug[] = [
                'id' => $package->id,
                'plan_title' => $package->plan->title ?? 'N/A',
                'price' => $package->price,
                'stripe_plan' => $package->stripe_plan,
                'stripe_product' => $package->stripe_product,
                'has_stripe_plan' => !empty($package->stripe_plan),
                'has_stripe_product' => !empty($package->stripe_product),
            ];
        }

        return response()->json([
            'success' => true,
            'plan_packages' => $debug,
            'total_packages' => count($debug),
            'packages_with_stripe' => collect($debug)->where('has_stripe_plan', true)->count(),
        ]);
    }

    public function create(Request $request, StripeService $stripeService)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'plan_id' => 'required|numeric|exists:plan_packages,id',
            'description' => 'nullable|string',
        ]);

        $user = User::where("email", $request->email)->first();
        $planPackage = PlanPackage::with('plan')->find($request->plan_id);

        if (!$planPackage || !$planPackage->stripe_plan) {
            return redirect()->back()->with('error', 'Selected plan package does not have a valid Stripe plan ID.');
        }
        try {
            Subscription::create([
                'status'                => !$user->valid_subscription ? SubscriptionStatusEnum::IN_USE->value : SubscriptionStatusEnum::WAITING->value,
                'payment_method_id'     => PaymentMethod::where('slug', 'bank_transfer')->first()?->id,
                'estimated_end_date'    => now()->addMonths($planPackage->number_of_month),
                'stripe_price'          => $planPackage->stripe_price,
                'name'                  => $planPackage->name,
                'plan_package_id'       => $planPackage->id,
                'user_id'               => $user->id,
                'stripe_status'         => "active",
                'start_date'            => now(),
                'quantity'              => 1,
            ]);
            return back()->with([
                'success' => 'Subscription created and marked as paid!',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Stripe Error: ' . $e->getMessage());
        }
    }
}
