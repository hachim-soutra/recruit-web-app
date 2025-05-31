<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class PackageController extends Controller
{
    public function listing()
    {
        if (Auth::check()) {
            $user_type = Auth::user()->user_type;
			
            $plans     = Package::select("id", 'plan_key', 'title', 'price', 'vat', 'vatprice', 'plan_interval', 'number_of_job_post', 'package_for', 'details', 'status')
                ->where('package_for', $user_type)
                ->where('status', 1)
                ->orderBy('id', 'ASC')
                ->get();
			
			/*
			$plans     = Package::where('package_for', $user_type)
                ->where('status', 1)
                ->orderBy('id', 'ASC')
                ->get();
			*/

            if ($plans) {
				
				@mail('ayan.lakhanidev@gmail.com', 'API: PackageController.listing', 'count = '.count($plans));
				
                return response()->json([
                    "status" => true,
                    'plans'  => $plans,
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
                'plan_id' => 'required',
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

                $plan = Package::select("id", 'plan_key', 'title', 'price', 'vat', 'vatprice', 'plan_interval', 'number_of_job_post', 'package_for', 'details', 'status')
                    ->where('id', $request->input('plan_id'))
                    ->first();

                if ($plan) {
                    return response()->json([
                        "status" => true,
                        'plans'  => $plan,

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
