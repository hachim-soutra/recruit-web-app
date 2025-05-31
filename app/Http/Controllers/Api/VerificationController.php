<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\ContactVerification;
use App\Models\User;
use BulkGate\Message\Connection;
use BulkGate\Sms\Message;
use BulkGate\Sms\Sender;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;

class VerificationController extends Controller
{

    public function emailOtp(Request $request)
    {
        $rules = [
            'mobile_no' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = implode(',', $validator->errors()->all());
            $error  = explode(',', $errors);
            return response()->json([
                "status"  => false,
                "message" => 'validation error',
                "error"   => $error,
            ], 200);

        }
        $type = "mobile";
        $code = mt_rand(1000, 9999);

        $user  = User::where('mobile', $request->input('mobile_no'))->first();
        $email = $user->email;
        $otp   = ContactVerification::firstOrNew([
            'type'   => $type,
            'mobile' => $request->input('mobile_no'),
        ]);
        if ($otp->resend <= 2) {
            $otp->code   = $code;
            $otp->resend = ($otp->resend + 1);
            $otp->save();
            Mail::to($email)->send(new OtpMail($otp));
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Otp send please check your email.",
                ],
            ], 200);
        } else {
            $otp->delete();
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "Max request limit reched please try after some time.",
                ],
            ], 200);
        }
    }

    public function verifyEmailOtp(Request $request)
    {
        $rules = [
            'code'      => 'required',
            'mobile_no' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "Please enter your code. For support contact info@recruit.ie",
                ],
            ], 200);
        }
        $code   = $request->input('code');
        $verify = ContactVerification::where('mobile', $request->input('mobile_no'))
            ->where('updated_at', '>', Carbon::now()->subMinutes(10))
            ->where('code', $code)
            ->where('type', 'mobile')
            ->first();
        if ($verify) {
            $verify->delete();
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Your mobile was successfully verified.",
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "You have inserted the wrong OTP. For support contact info@recruit.ie",
                ],
            ], 200);
        }
    }

    public function mobileOtp(Request $request)
    {
        $rules = [
            'mobile_no' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = implode(',', $validator->errors()->all());
            $error  = explode(',', $errors);
            return response()->json([
                "status"  => false,
                "message" => 'validation error',
                "error"   => $error,
            ], 200);

        }
        $type   = "mobile";
        $code   = mt_rand(1000, 9999);
        $mobile = $request->input('mobile_no');
        $otp    = ContactVerification::firstOrNew([
            'type'   => $type,
            'mobile' => $mobile,
        ]);
        if ($otp->resend <= 2) {
            $otp->code   = $code;
            $otp->resend = ($otp->resend + 1);
            $otp->save();
            //Send Otp To the Mobile
            // try {
            //     $endpoint = "http://2factor.in/API/V1/9d9ece88-7ffa-11eb-a9bc-0200cd936042/SMS/" . $mobile . "/" . $otp->code;
            //     $client   = new Client();
            //     $client->get($endpoint);
            // } catch (\Throwable $th) {
            //     return response()->json([
            //         "status"  => false,
            //         "message" => "OTP Sender Not working.",
            //     ], 200);
            // }

            $connection = new Connection('24836', 'mXzgZWJ8Xz5w36WsD0DQzyZPfRgZDfbtLMgHKmIV66fm0jyx1O');

            $sender = new Sender($connection);

            $message = new Message($mobile, "Your OTP " . $code);

            $sender->send($message);

            return response()->json([
                "status"  => true,
                "message" => "Otp send, please check your mobile.",
                "otp"     => $otp,
            ], 200);
        } else {
            $otp->delete();
            return response()->json([
                "status"  => false,
                "message" => "Max request limit reached, please try after some time.",
            ], 200);
        }
    }

    public function verifyMobileOtp(Request $request)
    {
        $rules = [
            'code'      => 'required',
            'mobile_no' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
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
        $code   = $request->input('code');
        $verify = ContactVerification::where('mobile', $request->input('mobile_no'))
            ->where('updated_at', '>', Carbon::now()->subMinutes(10))
            ->where('code', $code)
            ->where('type', 'mobile')
            ->first();
        if ($verify) {
            $verify->delete();
            User::where('mobile', $request->input('mobile_no'))
                ->update([
                    'mobile_verified' => 1,
                ]);

            return response()->json([
                "status"  => true,
                "message" => "Your mobile successfully verified.",
            ], 200);
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "You have inserted wrong OTP.",
                "error_type" => 2,
            ], 200);
        }
    }

}
