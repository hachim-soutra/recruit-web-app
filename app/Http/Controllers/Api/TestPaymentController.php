<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\MyHelper;
use Stripe;
use Validator;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Package;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;


class TestPaymentController extends Controller
{
    // NetPay get Token
    public function getToken(Request $request)
    {     
        $merchant = array(
            'merchant_id'    => "MRNPI01438",
            'operation_type' => "CREATE_TOKEN",
            'operation_mode' => '2',
        );

        $transaction = array(
            'source'         => 'MOTO'
        );
        
        $payment_source = array(
            "token_mode" => "TEMPORARY",
        );
        
        $data = array(
            'merchant'                 => $merchant,
            'transaction'              => $transaction,
            'payment_source'           => $payment_source
        );

        $credentials   = base64_encode('mrnpi01438.001' . ':' . 'f8a49e3d33294394da95f2d69aafef6f0a3e50526eadc3fb6e3514d2f0f626dbd19407402d97ad47f40d96b9a566eb3d');
        $array_to_json = json_encode($data);
      
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, getcwd() . '\pemfile.pem');

        curl_setopt($ch, CURLOPT_URL, "https://integrationtest.revolution.netpay.co.uk/v1/gateway/token");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . $credentials,
        ));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSH_PRIVATE_KEYFILE, '/home/dev8ivantechnolo/ssl/certs/dev8_ivantechnology_in_a5ea2_c155d_1652399999_dbae6fb1de9f8e8aeb8bed3cf105d32c.crt');
        curl_setopt($ch, CURLOPT_SSLCERT, '/home/dev8ivantechnolo/ssl/keys/a5ea2_c155d_a0d0f72b6623369d09d433af5eff8c01.key');
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, "");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $array_to_json);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        //curl_setopt($ch, CURLOPT_REFERER, "https://adfapi.adftest.rightmove.com/v1/property/sendpropertydetails");
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_VERBOSE , 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $ch_result = curl_exec($ch);
        print curl_errno($ch);
        print curl_error($ch);
        echo "Result = ".$ch_result;
        curl_close($ch);
        

        /* curl_setopt_array($curl, array(
            //$curl          = curl_init();
          //  CURLOPT_URL            => 'https://integration.revolution.netpay.co.uk/v1/gateway/token',
          // live url
            CURLOPT_URL            => 'https://integrationtest.revolution.netpay.co.uk/v1/gateway/token',                                     
            // test url
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            //CURLOPT_SSL_VERIFYHOST => 1,
            //CURLOPT_SSL_VERIFYPEER => TRUE,
            //CURLOPT_SSLCERTPASSWD  => "",
            //CURLOPT_SSH_PRIVATE_KEYFILE => '/home/r587765/ssl/certs/nfcmate_com_e4fb9_a81a3_1650585599_ae567cbda15ff3706001e43caf34f8c9.crt',
            //CURLOPT_SSLCERT => '/home/r587765/ssl/keys/e4fb9_a81a3_63a7dea5e8ce25d360f562970ab6dc2c.key',
            CURLOPT_SSLCERTPASSWD  => "",
            CURLOPT_SSH_PRIVATE_KEYFILE => public_path('ssl/nfcmate_com_e4fb9_a81a3_1650585599_ae567cbda15ff3706001e43caf34f8c9.crt'),
            CURLOPT_SSLCERT => public_path('ssl/p_key.pem'),
            CURLOPT_CUSTOMREQUEST  => 'PUT',
            CURLOPT_POSTFIELDS     => "$array_to_json",
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/json',
                'Authorization: Basic ' . $credentials,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response; */
        // return $array_to_json;
        //echo "hhh";
        // //return $response;
        // 'ssl_certificate_path' => '/home/r587765/ssl/certs/nfcmate_com_e4fb9_a81a3_1650585599_ae567cbda15ff3706001e43caf34f8c9.crt', 
        // 'ssl_key_path' => '/home/r587765/ssl/keys/e4fb9_a81a3_63a7dea5e8ce25d360f562970ab6dc2c.key',
        // 'ssl_key_password' => 'YOUR_PASSWORD' // if no password set empty

        // curl_setopt($ch, CURLOPT_SSH_PRIVATE_KEYFILE, getcwd() . '\myjks.jks');
        // curl_setopt($ch, CURLOPT_SSLCERT, getcwd() . '\pemfile.pem');
        // curl_setopt($ch, CURLOPT_SSLCERTPASSWD, "thesslpassword");
    }
    // NetPay Payment
    public function testPayment(Request $request)
    {
        $token = $request->input("token");

        $token = $token?$token:'94102587410258741025874QWE';

        $merchant = array(
            'merchant_id'    => "MRNPI01438",
            'operation_type' => "CREATE_SCHEDULED_PAYMENT",
            'operation_mode' => '2',
        );

        $transaction = array(
            'amount'         => 5.0,
            'currency'       => "EUR",
            'source'         => 'MOTO',
            'description'    => "Test Card Payment By Developer",
            'Optional'       => "IVAN8017829054"
        );
            $holder = array(
                "title"      => "Platinum",
                "firstname"  => "Rintu",
                "middlename" => "Kumar",
                "lastname"   => "Mondal",
                "fullname"   => "Rintu Kumar Mondal",
            );  
            $card = array(
                "card_type"     => "VISA",
                "number"        => "4917480000000008",
                "expiry_month"  => "04",
                "expiry_year"   => "23",
                "holder"        => $holder,
            );

                
        
        $payment_source = array(
            "type"   => "CARD",
            "card"   => $card,
            "token"  => $token,
        );

        $billing = array(
            'bill_to_company'   => 'Ivan',
            'bill_to_address'   => 'EN-35 Secter v',
            'bill_to_town_city' => 'Kolkata',
            'bill_to_county'    => 'West Bengal',
            'bill_to_postcode'  => '700059',
            'bill_to_country'   => 'India',
        );

            $interval = array(
                'frequency'        => '1',
                'unit'             => 'MONTH',
                'total_occurrence' => "1",
                'start_date'       => "2022-03-17",
            );

        $scheduled_payment = array(
            'scheduled_payment_id'  => 'JOBPAY1234567894102587410258741025874QWE',
            'interval'              => $interval,
        );        
        
        $customer = array(
            'customer_email'  => 'shibsankarjana2@gmail.com',
            'customer_phone'  => '8017829054',
            'customer_fax'    => "",
        );        

        $data = array(
            'merchant'                 => $merchant,
            'transaction'              => $transaction,
            'payment_source'           => $payment_source,
            'billing'                  => $billing,
            'scheduled_payment'        => $scheduled_payment,
            'customer'                 => $customer,
        );

        $credentials   = base64_encode('mrnpi01438.001' . ':' . 'f8a49e3d33294394da95f2d69aafef6f0a3e50526eadc3fb6e3514d2f0f626dbd19407402d97ad47f40d96b9a566eb3d');

        $array_to_json = json_encode($data);
        $curl          = curl_init();

        curl_setopt_array($curl, array(
           // CURLOPT_URL            => 'https://integrationtest.revolution.netpay.co.uk/v1/',
            CURLOPT_URL            => 'https://integrationtest.revolution.netpay.co.uk/v1/gateway/scheduled_payment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'PUT',
            CURLOPT_POSTFIELDS     => "$array_to_json",
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/json',
                'Authorization: Basic ' . $credentials,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        //print_r($response);
        // return $array_to_json;
        //echo "hhh";
        return $response;  
    
    }  

    public function test(Request $request)
    {
       return MyHelper::checkFreeAccess();
    }

    public function paymentSubscription(Request $request)
    {
       // return Auth::user()->name;
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                //'stripe_token'       => 'required',
                'amount'             => 'required',
                'card_brand'         => 'nullable',
                'card_last_four'     => 'nullable',
                'card_no'            => 'nullable',
                'exp_month'          => 'nullable',
                'exp_year'           => 'nullable',
                'cvv'                => 'nullable',
                'currency'           => 'required',
                'plan_interval'      => 'required',
                'plan_name'          => 'required',

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
            try {   
                $customer = \Stripe\Customer::create([ 
                    'name'  => Auth::user()->name,  
                    'email' => Auth::user()->email, 
                ]);  
            }catch(Exception $e) {   
                $api_error = $e->getMessage();
                return response()->json([
                    "status"     => false,
                    "message"    => 'Opps error! Customer Create Failed',
                    "error"      => $e->getMessage(),
                    "error_type" => 2,
                ], 200);

            } 
            
            if(empty($api_error) && $customer){ 
                try { 
                    // Create price with subscription info and interval 
                    $price = \Stripe\Price::create([ 
                        'unit_amount'  => round($request->input('amount')*100), 
                        'currency'     => $request->input('currency'), 
                        'recurring'    => ['interval' => $request->input('plan_interval')], 
                        'product_data' => ['name' => $request->input('plan_name')], 
                    ]); 
                } catch (Exception $e) {  
                    return response()->json([
                        "status"     => false,
                        "message"    => 'Opps error! Payment Price Create Failed',
                        "error"      => $e->getMessage(),
                        "error_type" => 2,
                    ], 200);
                } 
                
                if(empty($api_error) && $price){ 
                    // Create a new subscription 
                    try { 
                        $subscription = \Stripe\Subscription::create([ 
                            'customer' => $customer->id, 
                            'items' => [[ 
                                'price' => $price->id, 
                            ]], 
                            'payment_behavior' => 'default_incomplete', 
                            'expand' => ['latest_invoice.payment_intent'], 
                        ]); 
                    }catch(Exception $e) { 
                        return response()->json([
                            "status"     => false,
                            "message"    => 'Opps error! Payment Subscription Failed',
                            "error"      => $e->getMessage(),
                            "error_type" => 2,
                        ], 200);
                    } 
                    
                    if(empty($api_error) && $subscription){ 
                        $output = [ 
                            'subscriptionId' => $subscription->id, 
                            'clientSecret' => $subscription->latest_invoice->payment_intent->client_secret, 
                            'customerId' => $customer->id 
                        ]; 
                    
                        //echo json_encode($output); 
                        return response()->json([
                            "status"  => true,
                            "message" => "Subscription has been complete successfully",
                            "output"  => $output,
                        ], 200);
                    }else{ 
                        return response()->json([
                            "status"     => false,
                            "message"    => 'Opps error! Payment Subscription Failed',
                            "error"      => $e->getMessage(),
                            "error_type" => 2,
                        ], 200); 
                    } 
                }else{ 
                    return response()->json([
                        "status"     => false,
                        "message"    => 'Opps error! Payment Subscription Failed',
                        "error"      => $e->getMessage(),
                        "error_type" => 2,
                    ], 200);
                } 
            }else{ 
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

    public function paymentInsert(Request $request)
    { 
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                //'stripe_token'       => 'required',
                'amount'             => 'required',
                'subscription_id'    => 'required',
                'payment_intent'     => 'required',
                'customer_id'        => 'nullable',
                'plan_interval'      => 'required',

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

            $payment_intent  = $request->input('payment_intent'); 
            $subscription_id = $request->input('subscription_id'); 
            $customer_id     = $request->input('customer_id'); 
            $subscr_plan_id  = $request->input('subscr_plan_id'); 
            $plan_interval   = $request->input('plan_interval'); 
            
            // Retrieve customer info 
            try {   
                $customer = \Stripe\Customer::retrieve($customer_id);  
            }catch(Exception $e) {   
                return response()->json([
                    "status"     => false,
                    "message"    => "System error please try after sometime",
                    "error_type" => 2,
                    "error"      => $e->getMessage(),
                ], 200);  
            } 
            
            // Check whether the charge was successful 
            //if(!empty($payment_intent) && $payment_intent->status == 'succeeded'){ 
                
                // Retrieve subscription info 
                try {   
                    $subscription_data = \Stripe\Subscription::retrieve($subscription_id);  
                }catch(Exception $e) {   
                    return response()->json([
                        "status"     => false,
                        "message"    => "System error please try after sometime",
                        "error_type" => 2,
                        "error"      => $e->getMessage(),
                    ], 200);   
                } 

                return $subscription_data;
        
               // $payment_intent_id = $payment_intent->id; 
                $payment_intent_id = $subscription_id; 
                $paid_amount       = $payment_intent->amount; 
                $paid_amount       = ($paid_amount/100); 
                $paid_currency     = $payment_intent->currency; 
                $payment_status    = $payment_intent->status; 
                
                $created = date("Y-m-d H:i:s", $payment_intent->created); 
                $current_period_start = $current_period_end = ''; 
                if(!empty($subscription_data)){ 
                    $created = date("Y-m-d H:i:s", $subscription_data->created); 
                    $current_period_start = date("Y-m-d H:i:s", $subscription_data->current_period_start); 
                    $current_period_end = date("Y-m-d H:i:s", $subscription_data->current_period_end); 
                } 
                
                $name = $email = ''; 
                if(!empty($customer)){ 
                    $name = !empty($customer->name)?$customer->name:''; 
                    $email = !empty($customer->email)?$customer->email:''; 
                } 
                
                // Check if any transaction data exists already with the same TXN ID 
                $prev_row = Subscription::where('stripe_payment_intent_id', $payment_intent_id)->first();
                
                $payment_id = 0; 
                if(!empty($prev_row)){ 
                    $payment_id = $prev_row->id; 
                }else{ 
                    // Insert transaction data into the database                                  


                    $subscription                                 = new Subscription;
                    $subscription->user_id                        = Auth::user()->id;
                    $subscription->plan_id                        = $subscr_plan_id;
                    $subscription->stripe_subscription_id         = $subscription_id;
                    $subscription->stripe_customer_id             = $customer_id;
                    $subscription->stripe_payment_intent_id       = $payment_intent_id;
                    $subscription->paid_amount                    = $paid_amount;
                    $subscription->paid_amount_currency           = $paid_currency;
                    $subscription->plan_interval                  = $plan_interval;
                    $subscription->payer_email                    = $email;
                    $subscription->created                        = $created;
                    $subscription->plan_period_start              = $current_period_start;
                    $subscription->plan_period_end                = $current_period_end;
                    $subscription->status                         = $payment_status;
                    $insert = $subscription->save();
                    
                    if($insert){ 
                        $payment_id = $subscription->id; 
                        
                        // Update subscription ID in users table 
                       
                        User::where('id', Auth::user()->id)
                            ->update([
                            'subscription_id' => $subscription->id
                            ]);
                    } 
                } 
                
                $output = [ 
                    'payment_id' => base64_encode($payment_id) 
                ]; 
                return response()->json([
                    "status"  => true,
                    "message" => "Transaction has been complete successfully.",
                    "output"  => $output,
                ], 200);
            // }else{ 
            //     return response()->json([
            //         "status"     => false,
            //         "message"    => "Transaction has been failed!",
            //         "error_type" => 2,
            //         "error"      => $e->getMessage(),
            //     ], 200); 
            // } 
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
                'stripe_token'       => 'required',
                'amount'             => 'required',
                'card_brand'         => 'nullable',
                'card_last_four'     => 'nullable',
                'card_no'            => 'nullable',
                'exp_month'          => 'nullable',
                'exp_year'           => 'nullable',
                'cvv'                => 'nullable',
                'currency'           => 'required',
                'plan_id'            => 'required',

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
            $user = User::where('id', Auth::user()->id)->first();
            $stripe = new StripeClient($data->secret_key);
            if ($user->stripe_id) {
                try {
                  return  $customer = $stripe->customers->retrieve($user->stripe_id, []);
                } catch (Exception $e) {
                    return response()->json([
                        "status"     => false,
                        "message"    => 'Opps error! Customer Create Failed',
                        "error"      => $e->getMessage(),
                        "error_type" => 2,
                    ], 200);
                }
            } else {
                try {   
                    $customer = $stripe->customers->create([ 
                        'name'  => Auth::user()->name,  
                        'email' => Auth::user()->email, 
                        'source'  => $request->input('stripe_token'), 
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
                }catch(Exception $e) {   
                    $api_error = $e->getMessage();
                    return response()->json([
                        "status"     => false,
                        "message"    => 'Opps error! Customer Create Failed',
                        "error"      => $e->getMessage(),
                        "error_type" => 2,
                    ], 200);

                } 
            }
            User::where('id', Auth::user()->id)->update(['stripe_id' => $customer->id]);
            $plan = Package::where('id', $request->input('plan_id'))->first();
            // Add customer to stripe 

            try {   
                $plan = \Stripe\Plan::create([
                    'amount'         => $plan->price * 100,
                    'currency'       => 'eur',
                    'interval'       => 'day',                   
                    //'interval'       => $plan->plan_interval,                   
                    "product" => [ 
                        "name" => $plan->title 
                    ],
                    'interval_count' => 1
                  ]);
            }catch(Exception $e) {   
                $api_error = $e->getMessage();
                return response()->json([
                    "status"     => false,
                    "message"    => 'Opps error! Customer Plan Create Failed',
                    "error"      => $e->getMessage(),
                    "error_type" => 2,
                ], 200);

            } 
           
            if(empty($api_error) && $customer){ 
               
                
                if(empty($api_error) && $plan){ 
                    // Create a new subscription 
                    try { 
                        $subscription = \Stripe\Subscription::create([ 
                            'customer' => $customer->id, 
                            'items' => [[ 
                                'price' => $plan->id, 
                            ]], 
                            'coupon' => 'CAHYAKY9CH',

                           
                        ]); 
                    }catch(Exception $e) { 
                        return response()->json([
                            "status"     => false,
                            "message"    => 'Opps error! Payment Subscription Failed',
                            "error"      => $e->getMessage(),
                            "error_type" => 2,
                        ], 200);
                    } 
                    
                    if(empty($api_error) && $subscription){                         
                       
                        $output = json_encode($subscription); 
                        return response()->json([
                            "status"  => true,
                            "message" => "Subscription has been complete successfully",
                            "output"  => $output,
                        ], 200);
                    }else{ 
                        return response()->json([
                            "status"     => false,
                            "message"    => 'Opps error! Payment Subscription Failed',
                            "error"      => $e->getMessage(),
                            "error_type" => 2,
                        ], 200); 
                    } 
                }else{ 
                    return response()->json([
                        "status"     => false,
                        "message"    => 'Opps error! Payment Subscription Failed',
                        "error"      => $e->getMessage(),
                        "error_type" => 2,
                    ], 200);
                } 
            }else{ 
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
                'subscription_id'       => 'required'
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
           
                $user = User::where('id', Auth::user()->id)->first();

                $stripe = new StripeClient($data->secret_key);
           
                try {   
                    $cancel_subscriptions = $stripe->subscriptions->cancel(
                        $request->input('subscription_id'),
                        []
                      );

                }catch(Exception $e) {   
                    $api_error = $e->getMessage();
                    return response()->json([
                        "status"     => false,
                        "message"    => 'Opps error! Cancelation Failed',
                        "error"      => $e->getMessage(),
                        "error_type" => 2,
                    ], 200);

                } 
                if(empty($api_error) && $cancel_subscriptions){                         
                       
                    $output = json_encode($cancel_subscriptions); 
                    return response()->json([
                        "status"  => true,
                        "message" => "Subscription has been canceled successfully",
                        "output"  => $output,
                    ], 200);
                }
        }
    }
}