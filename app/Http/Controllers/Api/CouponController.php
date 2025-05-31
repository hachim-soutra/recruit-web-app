<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function listing()
    {
        if (Auth::check()) {
            $user_type = Auth::user()->user_type;
           $coupon     = Coupon::select("id", 'coupon_title', 'code', 'coupon_type', 'coupon_for', 'coupon_amount', 'coupon_limit', 'description', 'status')
                ->where('coupon_for', $user_type)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->get();
              
            if ($coupon) {
                return response()->json([
                    "status" => true,
                    'coupon'  =>$coupon,
                ], 200);

            } else {
                return response()->json([
                    "status"     => false,
                    'message'    => "Something went to wrong.",
                    "error_type" => 2,
                ], 200);
            }

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function details(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'coupon_id' => 'required',
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

            } else {

                $coupon = Coupon::select('id', 'coupon_title', 'code', 'coupon_type', 'coupon_for', 'coupon_amount', 'coupon_limit', 'description', 'status')
                    ->where('id', $request->input('coupon_id'))
                    ->first();

                if ($coupon) {
                    return response()->json([
                        "status" => true,
                        'coupon'  => $coupon,

                    ], 200);

                } else {
                    return response()->json([
                        "status"     => false,
                        'message'    => "Something went to wrong.",
                        "error_type" => 2,
                    ], 200);
                }
            }

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }
}
