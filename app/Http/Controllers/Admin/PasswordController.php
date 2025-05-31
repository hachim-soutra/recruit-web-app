<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class PasswordController extends Controller
{
    public function showResetForm(Request $request, $token)
    {
        $user = User::where('verify_token', $token)
            ->first();
        // $time    = base64_decode($token);
        // $minutes = (strtotime(gmdate('Y-m-d H:i:s')) - strtotime($time)) / 60;
        if ($user) {
            return view('admin.pages.reset', compact('user'));

        } else {

            return view('admin.pages.wrong_token');
        }

    }

    public function passwordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email',
            'password'              => 'required|string|confirmed',
            'password_confirmation' => 'sometimes|required_with:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            
			//$user->password = bcrypt($request->password);
			$user->password = Hash::make($request->password);
			
            $user->save();
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Password Successfully Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "Email or token you have passed is expired",
                ],
            ], 200);
        }

    }

    public function passwordSuccess()
    {
        return view('admin.pages.success');

    }
}
