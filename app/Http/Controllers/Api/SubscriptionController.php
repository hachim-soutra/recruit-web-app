<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CancelSubscriptionMail;
use App\Mail\SubscriptionMail;
use App\Models\Balance;
use App\Models\Coupon;
use App\Models\Package;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Transuction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe;
use Stripe\StripeClient;
use Validator;

class SubscriptionController extends Controller
{
    public function applyCoupon(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'coupon_code' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = implode(',', $validator->errors()->all());
                $error  = explode(',', $errors);
                return response()->json([
                    "status"     => false,
                    "message"    => 'validation error',
                    "error"      => $error,
                    "error_type" => 1,
                ], 200);

            }
            $user_type = ucfirst(Auth::user()->user_type); //employer
            $coupon    = Coupon::where('code', $request->input('coupon_code'))
                ->where('coupon_for', $user_type)
                ->where('status', 1)
                ->first();
            if ($coupon) {

                return response()->json([
                    "status"  => true,
                    "message" => "Coupon applied successfully.",
                    "coupon"  => $coupon,
                ], 200);

            } else {
                return response()->json([
                    "status"     => false,
                    "message"    => "Invalid Coupon.",
                    "error_type" => 2,
                ], 200);
            }

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "Authentication Failed.",
                "error_type" => 2,
            ], 200);
        }
    }

    public function subscription(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'stripe_token'   => 'required',
                'plan_key'       => 'required',
                'coupon_code'    => 'nullable',
                'card_brand'     => 'nullable',
                'card_last_four' => 'nullable',
                'card_no'        => 'nullable',
                'exp_month'      => 'nullable',
                'exp_year'       => 'nullable',
                'cvv'            => 'nullable',
                'payer_email'    => 'nullable',
            ]);

            if ($validator->fails()) {
                $errors = implode(',', $validator->errors()->all());
                $error  = explode(',', $errors);
                return response()->json([
                    "status"     => false,
                    "message"    => 'validation error',
                    "error"      => $error,
                    "error_type" => 1,
                ], 200);

            }

            $data = Setting::where('id', 1)->first();

            Stripe\Stripe::setApiKey($data->secret_key);
            // Add customer to stripe
            $user   = User::where('id', Auth::user()->id)->first();
            $stripe = new StripeClient($data->secret_key);

            try {
                $customer = $stripe->customers->create([
                    'name'        => Auth::user()->name,
                    'email'       => Auth::user()->email,
                    'source'      => $request->input('stripe_token'),
                    'description' => 'Plan Subscription',
                    'address'     => [
                        'line1'       => '21 Woodlands Cl',
                        'postal_code' => '737854',
                        'city'        => 'Primz Bizhub',
                        'state'       => 'Woodlands',
                        'country'     => 'Singapore',
                    ],
                    'shipping'    => [
                        'name'    => Auth::user()->name,
                        'address' => [
                            'line1'       => '21 Woodlands Cl',
                            'postal_code' => '737854',
                            'city'        => 'Primz Bizhub',
                            'state'       => 'Woodlands',
                            'country'     => 'Singapore',
                        ],
                    ],
                ]);
            } catch (Exception $e) {
                $api_error = $e->getMessage();
                return response()->json([
                    "status"     => false,
                    "message"    => 'Oops error! Customer creation was unsuccessful.',
                    "error"      => $e->getMessage(),
                    "error_type" => 2,
                ], 200);

            }

            if (empty($api_error) && $customer) {
                User::where('id', Auth::user()->id)->update(['stripe_id' => $customer->id], ['subscription_id' => null]);
            } else {
                return response()->json([
                    "status"     => false,
                    "message"    => 'Opps error! Customer creation was unsuccessful.',
                    "error_type" => 2,
                ], 200);
            }

            $plan = Package::where('plan_key', $request->input('plan_key'))
                ->first();
            $coupon_data = Coupon::where('code', $request->input('coupon_code'))
                ->first();
            // retrive plan from stripe
            if ($plan) {
                try {
                    $r_plan = $stripe->plans->retrieve(
                        $plan->plan_key,
                        []
                    );
                } catch (Exception $e) {
                    $api_error = $e->getMessage();
                    return response()->json([
                        "status"     => false,
                        "message"    => 'Oops error! Failed to create customer plan.',
                        "error"      => $e->getMessage(),
                        "error_type" => 2,
                    ], 200);

                }
            } else {
                return response()->json([
                    "status"     => false,
                    "message"    => 'Plan Not Found or Invalid Plan',
                    "error_type" => 2,
                ], 200);
            }

            if (empty($api_error) && $customer) {

                if (empty($api_error) && $r_plan) {
                    // Create a new subscription
                    try {
                        $subscription = \Stripe\Subscription::create([
                            'customer' => $customer->id,
                            'items'    => [[
                                'price' => $r_plan->id,
                            ]],
                            'coupon'   => $request->input('coupon_code'),
                        ]);
                    } catch (Exception $e) {
                        return response()->json([
                            "status"     => false,
                            "message"    => 'Oops error! Payment subscription failed.',
                            "error"      => $e->getMessage(),
                            "error_type" => 2,
                        ], 200);
                    }

                    if (empty($api_error) && $subscription) {
                        User::where('id', Auth::user()->id)
                            ->update([
                                'subscription_id' => $subscription->id,
                                'stripe_id'       => $request->input('stripe_token'),
                            ]);

                        // Create Transaction Data
                        $transaction                 = new Transuction;
                        $transaction->user_id        = Auth::user()->id;
                        $transaction->job_id         = $plan->id;
                        $transaction->transaction_id = "Trns - " . date('YmdHis') . Auth::user()->user_key . rand(10000, 99999);
                        $transaction->cart_key       = date('YmdHis') . Auth::user()->user_key . rand(0, 1000);
                        if ($request->input('coupon_code')) {
                            $transaction->amount = ($plan->vatprice - $coupon_data->coupon_amount);
                        } else {
                            $transaction->amount = $plan->vatprice;
                        }

                        $transaction->currency       = $data->currency ? $data->currency : 'eur';
                        $transaction->total_amount   = $plan->vatprice;
                        $transaction->card_brand     = $request->input('card_brand');
                        $transaction->card_last_four = $request->input('card_last_four');
                        $transaction->tax_percentage = 0.00;
                        $transaction->status         = "complete";
                        $transaction->pay_by         = ucfirst(Auth::user()->user_type);
                        $transaction->save();

                        // subscription Data Insert
                        $sub                         = new Subscription;
                        $sub->stripe_subscription_id = $subscription->id;
                        $sub->stripe_customer_id     = $customer->id;
                        $sub->user_id                = Auth::user()->id;
                        $sub->transaction_id         = $transaction->id;
                        $sub->plan_id                = $plan->id;
                        $sub->payment_method         = $subscription->default_payment_method;
                        $sub->paid_amount            = $transaction->amount;
                        $sub->paid_amount_currency   = 'eur';
                        $sub->plan_interval          = $plan->plan_interval;
                        $sub->plan_interval_count    = 1;
                        $sub->payer_email            = $request->input('payer_email');
                        $sub->created                = Carbon::now()->format('Y-m-d H:i:s');
                        $sub->plan_period_start      = Carbon::now()->format('Y-m-d H:i:s');
                        $sub->status                 = "Running";
                        $sub->save();
                        if (Auth::user()->user_type == 'employer') {
                            // update or Insert Balance Data
                            $balance                        = new Balance;
                            $balance->user_id               = Auth::user()->id;
                            if($plan->number_of_job_post == "-1"){
                                $balance->current_month_balance = -1;
                            }else{
                                $balance->current_month_balance = $plan->number_of_job_post;
                            }                            
                            $balance->save();
                        }

                        // Update coupon table
                        if ($coupon_data) {
                            Coupon::where('id', $coupon_data->id)->increment('total_usage');
                        }

                        //$output = json_encode($subscription);
                        $data                  = array();
                        $data['name']          = Auth::user()->name;
                        $data['plan_name']     = $plan->title;
                        $data['plan_interval'] = $plan->plan_interval;
                        $data['amount']        = "€".$plan->price;
                        $data['vat']        = "€".$plan->vat;
                        $data['vatamount']        = "€".$plan->vatprice;
                        $data['transaction_id'] = $transaction->transaction_id;

                        Mail::to(Auth::user()->email)->send(new SubscriptionMail($data));

                        return response()->json([
                            "status"  => true,
                            "message" => "Subscription has been created successfully.",
                            "output"  => $subscription,
                        ], 200);
                    } else {
                        return response()->json([
                            "status"     => false,
                            "message"    => 'Opps error! Payment Subscription Failed',
                            "error"      => $e->getMessage(),
                            "error_type" => 2,
                        ], 200);
                    }
                } else {
                    return response()->json([
                        "status"     => false,
                        "message"    => 'Opps error! Payment Subscription Failed',
                        "error"      => $e->getMessage(),
                        "error_type" => 2,
                    ], 200);
                }
            } else {
                return response()->json([
                    "status"     => false,
                    "message"    => 'Opps error! Payment Subscription Failed',
                    "error"      => $e->getMessage(),
                    "error_type" => 2,
                ], 200);
            }
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "Authentication Failed.",
                "error_type" => 2,
            ], 200);
        }

    }

    public function cancelSubscription(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'subscription_id' => 'required',
                'cancel_reason'   => 'required',
            ]);

            if ($validator->fails()) {
                $errors = implode(',', $validator->errors()->all());
                $error  = explode(',', $errors);
                return response()->json([
                    "status"     => false,
                    "message"    => 'validation error',
                    "error"      => $error,
                    "error_type" => 1,
                ], 200);
            }

            $data = Setting::where('id', 1)->first();

            Stripe\Stripe::setApiKey($data->secret_key);

            $user   = User::where('id', Auth::user()->id)->first();
            $stripe = new StripeClient($data->secret_key);
            try {
                $cancel_subscriptions = $stripe->subscriptions->cancel(
                    $request->input('subscription_id'),
                    []
                );

            } catch (Exception $e) {
                $api_error = $e->getMessage();
                return response()->json([
                    "status"     => false,
                    "message"    => 'Opps error! Cancelation Failed',
                    "error"      => $e->getMessage(),
                    "error_type" => 2,
                ], 200);

            }
            if (empty($api_error) && $cancel_subscriptions) {
                Subscription::where('stripe_subscription_id', $request->input('subscription_id'))
                    ->update([
                        'cancele_reason'   => $request->input('cancel_reason'),
                        'canceletion_date' => Carbon::now()->format('Y-m-d'),
                        'status'           => 'Canceled',
                    ]);
                User::where('id', Auth::user()->id)
                    ->update([
                        'stripe_id'       => null,
                        'subscription_id' => null,
                    ]);
                if (Auth::user()->user_type == 'employer') {
                    Balance::where('user_id', Auth::user()->id)->delete();
                }
                Mail::to(Auth::user()->email)->send(new CancelSubscriptionMail($user));

                //$output = json_encode($cancel_subscriptions);
                return response()->json([
                    "status"  => true,
                    "message" => "Subscription has been Cancelled successfully",
                    "output"  => $cancel_subscriptions,
                ], 200);
            } else {
                return response()->json([
                    "status"     => false,
                    "message"    => 'Opps error! Payment Subscription Cancelation Failed',
                    "error"      => $e->getMessage(),
                    "error_type" => 2,
                ], 200);
            }

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "Authentication Failed.",
                "error_type" => 2,
            ], 200);
        }
    }
    // public function applyCoupon(Request $request)
    // {
    //     if (Auth::check()) {
    //         $validator = Validator::make($request->all(), [
    //             'coupon_code' => 'required',
    //         ]);

    //         if ($validator->fails()) {
    //             $errors = implode(',', $validator->errors()->all());
    //             $error  = explode(',', $errors);
    //             return response()->json([
    //                 "status"     => false,
    //                 "message"    => 'validation error',
    //                 "error"      => $error,
    //                 "error_type" => 1,
    //             ], 200);

    //         }
    //         $user_type = ucfirst(Auth::user()->user_type); //employer
    //         $coupon    = Coupon::where('code', $request->input('coupon_code'))
    //             ->where('coupon_for', $user_type)
    //             ->where('status', 1)
    //             ->first();
    //         if ($coupon) {
    //             return response()->json([
    //                 "status"  => true,
    //                 "message" => "Coupon applied successfully.",
    //                 "coupon"  => $coupon,
    //             ], 200);

    //         } else {
    //             return response()->json([
    //                 "status"     => false,
    //                 "message"    => "Invalid Coupon.",
    //                 "error_type" => 2,
    //             ], 200);
    //         }

    //     } else {
    //         return response()->json([
    //             "status"     => false,
    //             "message"    => "Authentication Failed.",
    //             "error_type" => 2,
    //         ], 200);
    //     }
    // }

    // public function subscription(Request $request)
    // {
    //     if (Auth::check()) {
    //         $validator = Validator::make($request->all(), [
    //             'stripe_token'   => 'required',
    //             'plan_key'       => 'required',
    //             'coupon_code'    => 'nullable',
    //             'card_brand'     => 'nullable',
    //             'card_last_four' => 'nullable',
    //             'card_no'        => 'nullable',
    //             'exp_month'      => 'nullable',
    //             'exp_year'       => 'nullable',
    //             'cvv'            => 'nullable',
    //             'payer_email'    => 'nullable',
    //         ]);

    //         if ($validator->fails()) {
    //             $errors = implode(',', $validator->errors()->all());
    //             $error  = explode(',', $errors);
    //             return response()->json([
    //                 "status"     => false,
    //                 "message"    => 'validation error',
    //                 "error"      => $error,
    //                 "error_type" => 1,
    //             ], 200);

    //         }

    //         $data = Setting::where('id', 1)->first();

    //         Stripe\Stripe::setApiKey($data->secret_key);
    //         // Add customer to stripe
    //         $user   = User::where('id', Auth::user()->id)->first();
    //         $stripe = new StripeClient($data->secret_key);
    //         if ($user->stripe_id) {
    //             try {
    //                 $customer = $stripe->customers->retrieve($user->stripe_id, []);
    //             } catch (Exception $e) {
    //                 return response()->json([
    //                     "status"     => false,
    //                     "message"    => 'Opps error! No such token',
    //                     "error"      => $e->getMessage(),
    //                     "error_type" => 2,
    //                 ], 200);
    //             }
    //         } else {
    //             try {
    //                 $customer = $stripe->customers->create([
    //                     'name'        => Auth::user()->name,
    //                     'email'       => Auth::user()->email,
    //                     'source'      => $request->input('stripe_token'),
    //                     'description' => 'Plan Subscription',
    //                     'address'     => [
    //                         'line1'       => '21 Woodlands Cl',
    //                         'postal_code' => '737854',
    //                         'city'        => 'Primz Bizhub',
    //                         'state'       => 'Woodlands',
    //                         'country'     => 'Singapore',
    //                     ],
    //                     'shipping'    => [
    //                         'name'    => Auth::user()->name,
    //                         'address' => [
    //                             'line1'       => '21 Woodlands Cl',
    //                             'postal_code' => '737854',
    //                             'city'        => 'Primz Bizhub',
    //                             'state'       => 'Woodlands',
    //                             'country'     => 'Singapore',
    //                         ],
    //                     ],
    //                 ]);
    //             } catch (Exception $e) {
    //                 $api_error = $e->getMessage();
    //                 return response()->json([
    //                     "status"     => false,
    //                     "message"    => 'Opps error! Customer Create Failed',
    //                     "error"      => $e->getMessage(),
    //                     "error_type" => 2,
    //                 ], 200);

    //             }
    //         }
    //         User::where('id', Auth::user()->id)->update(['stripe_id' => $customer->id]);
    //         $plan = Package::where('plan_key', $request->input('plan_key'))
    //             ->first();
    //         $coupon_data = Coupon::where('code', $request->input('coupon_code'))
    //             ->first();
    //         // retrive plan from stripe
    //         if ($plan) {
    //             try {
    //                 $r_plan = $stripe->plans->retrieve(
    //                     $plan->plan_key,
    //                     []
    //                 );
    //             } catch (Exception $e) {
    //                 $api_error = $e->getMessage();
    //                 return response()->json([
    //                     "status"     => false,
    //                     "message"    => 'Opps error! Customer Plan Create Failed',
    //                     "error"      => $e->getMessage(),
    //                     "error_type" => 2,
    //                 ], 200);

    //             }
    //         } else {
    //             return response()->json([
    //                 "status"     => false,
    //                 "message"    => 'Plan Not Found! or Invalid Plan',
    //                 "error_type" => 2,
    //             ], 200);
    //         }

    //         if (empty($api_error) && $customer) {

    //             if (empty($api_error) && $r_plan) {
    //                 // Create a new subscription
    //                 try {
    //                     $subscription = \Stripe\Subscription::create([
    //                         'customer' => $customer->id,
    //                         'items'    => [[
    //                             'price' => $r_plan->id,
    //                         ]],
    //                         'coupon'   => $request->input('coupon_code'),
    //                     ]);
    //                 } catch (Exception $e) {
    //                     return response()->json([
    //                         "status"     => false,
    //                         "message"    => 'Opps error! Payment Subscription Failed',
    //                         "error"      => $e->getMessage(),
    //                         "error_type" => 2,
    //                     ], 200);
    //                 }

    //                 if (empty($api_error) && $subscription) {
    //                     User::where('id', Auth::user()->id)
    //                         ->update([
    //                             'subscription_id' => $subscription->id,
    //                             'stripe_id'       => $request->input('stripe_token'),
    //                         ]);
    //                     // Create Transaction Data
    //                     $transaction                 = new Transuction;
    //                     $transaction->user_id        = Auth::user()->id;
    //                     $transaction->job_id         = $plan->id;
    //                     $transaction->transaction_id = "Trns - " . date('YmdHis') . Auth::user()->user_key . rand(10000, 99999);
    //                     $transaction->cart_key       = date('YmdHis') . Auth::user()->user_key . rand(0, 1000);
    //                     if ($request->input('coupon_code')) {
    //                         $transaction->amount = ($plan->price - $coupon_data->coupon_amount);
    //                     } else {
    //                         $transaction->amount = $plan->price;
    //                     }

    //                     $transaction->currency       = $data->currency ? $data->currency : 'eur';
    //                     $transaction->total_amount   = $plan->price;
    //                     $transaction->card_brand     = $request->input('card_brand');
    //                     $transaction->card_last_four = $request->input('card_last_four');
    //                     $transaction->tax_percentage = 0.00;
    //                     $transaction->status         = "complete";
    //                     $transaction->pay_by         = ucfirst(Auth::user()->user_type);
    //                     $transaction->save();

    //                     // subscription Data Insert
    //                     $sub                         = new Subscription;
    //                     $sub->stripe_subscription_id = $subscription->id;
    //                     $sub->stripe_customer_id     = $customer->id;
    //                     $sub->user_id                = Auth::user()->id;
    //                     $sub->transaction_id         = $transaction->id;
    //                     $sub->plan_id                = $plan->id;
    //                     $sub->payment_method         = $subscription->default_payment_method;
    //                     $sub->paid_amount            = $transaction->amount;
    //                     $sub->paid_amount_currency   = 'eur';
    //                     $sub->plan_interval          = $plan->plan_interval;
    //                     $sub->plan_interval_count    = 1;
    //                     $sub->payer_email            = $request->input('payer_email');
    //                     $sub->created                = Carbon::now()->format('Y-m-d H:i:s');
    //                     $sub->plan_period_start      = Carbon::now()->format('Y-m-d H:i:s');
    //                     $sub->status                 = "Running";
    //                     $sub->save();

    //                     // update or Insert Balance Data
    //                     $balance                        = new Balance;
    //                     $balance->user_id               = Auth::user()->id;
    //                     $balance->current_month_balance = $plan->number_of_job_post;
    //                     $balance->save();

    //                     // Update coupon table
    //                     if ($coupon_data) {
    //                         Coupon::where('id', $coupon_data->id)->increment('total_usage');
    //                     }

    //                     //$output = json_encode($subscription);
    //                     $data                  = array();
    //                     $data['name']          = Auth::user()->name;
    //                     $data['plan_name']     = $plan->title;
    //                     $data['plan_interval'] = $plan->plan_interval;
    //                     $data['amount']        = $plan->price;

    //                     Mail::to(Auth::user()->email)->send(new SubscriptionMail($data));

    //                     return response()->json([
    //                         "status"  => true,
    //                         "message" => "Subscription has been complete successfully",
    //                         "output"  => $subscription,
    //                     ], 200);
    //                 } else {
    //                     return response()->json([
    //                         "status"     => false,
    //                         "message"    => 'Opps error! Payment Subscription Failed',
    //                         "error"      => $e->getMessage(),
    //                         "error_type" => 2,
    //                     ], 200);
    //                 }
    //             } else {
    //                 return response()->json([
    //                     "status"     => false,
    //                     "message"    => 'Opps error! Payment Subscription Failed',
    //                     "error"      => $e->getMessage(),
    //                     "error_type" => 2,
    //                 ], 200);
    //             }
    //         } else {
    //             return response()->json([
    //                 "status"     => false,
    //                 "message"    => 'Opps error! Payment Subscription Failed',
    //                 "error"      => $e->getMessage(),
    //                 "error_type" => 2,
    //             ], 200);
    //         }
    //     } else {
    //         return response()->json([
    //             "status"     => false,
    //             "message"    => "Authentication Failed.",
    //             "error_type" => 2,
    //         ], 200);
    //     }

    // }

    // public function cancelSubscription(Request $request)
    // {
    //     if (Auth::check()) {
    //         $validator = Validator::make($request->all(), [
    //             'subscription_id' => 'required',
    //             'cancel_reason'   => 'required',
    //         ]);

    //         if ($validator->fails()) {
    //             $errors = implode(',', $validator->errors()->all());
    //             $error  = explode(',', $errors);
    //             return response()->json([
    //                 "status"     => false,
    //                 "message"    => 'validation error',
    //                 "error"      => $error,
    //                 "error_type" => 1,
    //             ], 200);
    //         }

    //         $data = Setting::where('id', 1)->first();

    //         Stripe\Stripe::setApiKey($data->secret_key);

    //         $user   = User::where('id', Auth::user()->id)->first();
    //         $stripe = new StripeClient($data->secret_key);
    //         try {
    //             $cancel_subscriptions = $stripe->subscriptions->cancel(
    //                 $request->input('subscription_id'),
    //                 []
    //             );

    //         } catch (Exception $e) {
    //             $api_error = $e->getMessage();
    //             return response()->json([
    //                 "status"     => false,
    //                 "message"    => 'Opps error! Cancelation Failed',
    //                 "error"      => $e->getMessage(),
    //                 "error_type" => 2,
    //             ], 200);

    //         }
    //         if (empty($api_error) && $cancel_subscriptions) {
    //             Subscription::where('stripe_subscription_id', $request->input('subscription_id'))
    //                 ->update([
    //                     'cancele_reason'   => $request->input('cancel_reason'),
    //                     'canceletion_date' => Carbon::now()->format('Y-m-d'),
    //                     'status'           => 'Canceled',
    //                 ]);
    //             User::where('id', Auth::user()->id)
    //                 ->update([
    //                     'stripe_id'       => null,
    //                     'subscription_id' => null,
    //                 ]);
    //             Balance::where('user_id', Auth::user()->id)->delete();

    //             Mail::to(Auth::user()->email)->send(new CancelSubscriptionMail($user));

    //             $output = json_encode($cancel_subscriptions);
    //             return response()->json([
    //                 "status"  => true,
    //                 "message" => "Subscription has been canceled successfully",
    //                 "output"  => $output,
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 "status"     => false,
    //                 "message"    => 'Opps error! Payment Subscription Cancelation Failed',
    //                 "error"      => $e->getMessage(),
    //                 "error_type" => 2,
    //             ], 200);
    //         }

    //     } else {
    //         return response()->json([
    //             "status"     => false,
    //             "message"    => "Authentication Failed.",
    //             "error_type" => 2,
    //         ], 200);
    //     }
    // }

}
