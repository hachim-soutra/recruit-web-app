<?php

namespace App\Services\Payment;

use App\Enum\Payments\PlanStatusEnum;
use App\Enum\Payments\SubscriptionStatusEnum;
use App\Models\Plan;
use App\Models\PlanPackage;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;

class StripeService
{
    protected $stripeModel;
    public function __construct()
    {
        $this->stripeModel = new \Stripe\StripeClient(config('app.stripe.secret'));;
    }

    public function create_product($planData): \Stripe\Product
    {
        return $this->stripeModel->products->create([
            'name'                => $planData['title'],
            'description'         => $planData['description'],
            'default_price_data'  => [
                'currency'              => 'EUR',
                'unit_amount_decimal'   => intval($planData['price']) * 100,
                'recurring'             => ['interval' => 'month', 'interval_count' => $planData['number_of_month']],
                'tax_behavior'          => 'exclusive'
            ],
        ]);
    }

    public function create_product_charge($slotData): \Stripe\Product
    {
        return $this->stripeModel->products->create([
            'name'                => $slotData['title'],
            'description'         => $slotData['description'],
            'default_price_data'  => [
                'currency'              => 'EUR',
                'unit_amount_decimal'   => intval($slotData['price']) * 100,
                'tax_behavior'          => 'exclusive'
            ],
        ]);
    }

    public function create_price($priceData): \Stripe\Price
    {
        $amount = intval($priceData['price']) * 100;
        $price = null;

        //CHECk PRODUCT EXISTING
        $prices = $this->find_prices_by_product($priceData['stripe_product']);
        foreach ($prices as $item) {
            if ($item->unit_amount == $amount) {
                $price = $item;
                break;
            }
        }

        //CREATE NEW PRICE
        if (!$price) {
            $price = $this->stripeModel->prices->create([
                'unit_amount' => $amount,
                'currency' => 'EUR',
                'recurring' => ['interval' => 'month', 'interval_count' => $priceData['number_of_month']],
                'tax_behavior' => 'exclusive',
                'active' => true,
                'product' => $priceData['stripe_product']
            ]);
        }

        //ACTIVATE PRICE
        $this->update_product_price([
            'id'                => $priceData['stripe_product'],
            'price'             => $price->id,
        ]);
        return $price;
    }

    public function create_price_charge($priceData): \Stripe\Price
    {
        $amount = intval($priceData['price']) * 100;
        $price = null;

        //CHECk PRODUCT EXISTING
        $prices = $this->find_prices_by_product($priceData['stripe_product']);
        foreach ($prices as $item) {
            if ($item->unit_amount == $amount) {
                $price = $item;
                break;
            }
        }

        //CREATE NEW PRICE
        if (!$price) {
            $price = $this->stripeModel->prices->create([
                'unit_amount' => $amount,
                'currency' => 'EUR',
                'tax_behavior' => 'exclusive',
                'active' => true,
                'product' => $priceData['stripe_product']
            ]);
        }

        //ACTIVATE PRICE
        $this->update_product_price([
            'id'                => $priceData['stripe_product'],
            'price'             => $price->id,
        ]);
        return $price;
    }

    public function archive_product(string $id): \Stripe\Product
    {
        return $this->stripeModel->products->update($id, ['active' => false]);
    }

    public function download_invoice($id)
    {
        $subscription = $this->find_subscription_by_token($id);
        return $this->stripeModel->invoices->retrieve($subscription->latest_invoice);
    }

    public function download_invoice_charge($id)
    {
        $charge = $this->find_charge_by_token($id);
        return $charge->receipt_url;
    }

    public function create_payment_link($data): string
    {
        $paymentLink = $this->stripeModel->paymentLinks->create([
            'currency' => 'eur',
            'payment_method_types' => [],
            'line_items' => [
                [
                    'price' => $data['price_token'],
                    'quantity' => 1,
                    'tax_rates' => [config('app.stripe.txr')],
                ],
            ],
            'after_completion' => [
                'type' => 'redirect',
                'redirect' => ['url' => route('welcome', ['token' => $data['user_token']])],
            ],
        ]);

        return $paymentLink->url;
    }

    public function create_checkout_sessions($data): string
    {
        $paymentLink = $this->stripeModel->checkout->sessions->create(
            [
                'currency' => 'eur',
                'payment_method_types' => [],
                'line_items' => [[
                    'price' => $data['price_token'],
                    'quantity' => 1,
                    'tax_rates' => [config('app.stripe.txr')],
                ]],
                'customer' => $data['customer'],
                'mode' => 'subscription',
                'success_url' => route('admin_stripe_payement') . '?session_id={CHECKOUT_SESSION_ID}&subscription_id=' . $data['subscription_id'],
                'cancel_url' => route('cancel-payement'),
            ]
        );

        return $paymentLink->url;
    }

    public function create_subscription($session_id)
    {
        $session = $this->stripeModel->checkout->sessions->retrieve($session_id);
        $subscription = $this->stripeModel->subscriptions->retrieve($session["subscription"]);
        $subscriptionModel = Subscription::find(request()->query('subscription_id'));
        $subscriptionModel->status = SubscriptionStatusEnum::PURCHASED->value;
        $subscriptionModel->name = $subscription["plan"]["object"];
        $subscriptionModel->subscription_id = $subscription["id"];
        $subscriptionModel->stripe_id = $subscription["default_payment_method"];
        $subscriptionModel->stripe_price = $subscription["plan"]["id"];
        $subscriptionModel->quantity = $subscription["plan"]["interval_count"];
        $subscriptionModel->save();
        return redirect()->route("welcome")->with('success', 'Thanks for payement!');
    }

    private function find_product_by_id($id): ?\Stripe\Product
    {
        return $this->stripeModel->products->retrieve($id);
    }

    private function find_prices_by_product($id)
    {
        $prices = $this->stripeModel->prices->all(['product' => $id]);
        return $prices->data;
    }

    private function update_product_price($product)
    {
        $prices = $this->find_prices_by_product($product['id']);

        try {
            // ACTIVE EXIST PRICE
            $this->stripeModel->prices->update($product['price'], [
                'active' => true,
            ]);

            // MAKE EXIST PRICE AS DEFAULT
            $this->stripeModel->products->update(
                $product['id'],
                ['default_price' => $product['price']]
            );

            foreach ($prices as $price) {
                if ($price->id !== $product['price']) {
                    $this->stripeModel->prices->update($price->id, [
                        'active' => false,
                    ]);
                }
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function find_subscription_by_token(string $token)
    {
        return $this->stripeModel->subscriptions->retrieve($token);
    }

    private function find_charge_by_token(string $token)
    {
        return $this->stripeModel->charges->retrieve($token);
    }
}
