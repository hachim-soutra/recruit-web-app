<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SettingController extends Controller
{

    public function deleteAccount(Request $request)
    {
        if (Auth::check()) {
            $data = User::where('id', Auth::user()->id)->first();

            User::where('id', $data->id)
                ->update([
                    'user_key'        => Str::random(5)."d0000" . $data->user_key,
                    'email'           => Str::random(5)."d0000" . $data->email,
                    'mobile'          => Str::random(5)."0000" . $data->mobile,
                    'verified'        => 0,
                    'email_verified'  => 0,
                    'mobile_verified' => 0,
                    'is_complete'     => 0,
                    'status'          => 0,
                ]);
            return response()->json([
                "status"  => true,
                "message" => "The account has been deleted successfully.",
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime.",
                "error_type" => 2,
            ], 200);
        }

    }
}
