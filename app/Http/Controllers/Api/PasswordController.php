<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetForgetPassword;
use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;

class PasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $rules = [
            'email' => 'required',
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
        $email = $request->input('email');
        $otp = rand(100000, 999999);
        User::where('email', $email)->update([
            'otp' => $otp
        ]);
        $user  = User::where('email', $email)->first();
        if ($user) {
            Mail::to($user->email)->send(new ResetPassword($user));
            return response()->json([
                "email"   => $email,
                "status"  => true,
                "message" => "An OTP has been sent to your registered email id to reset your password.",
            ], 200);
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "This email does not register with us.",
                "error_type" => 2,
            ], 200);
        }

    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email',
            'otp'                   => 'required|numeric|digits:6',
            'password'              => 'required|string|confirmed|min:6|max:50',
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
            $otp = $request->input('otp');
            if($user->otp == $otp){
                $user->password = bcrypt($request->password);
                $user->save();
				
                Mail::to($request->input('email'))->send(new ResetForgetPassword($user));    
                
				return response()->json([
                    'data' => [
                        "status"  => 'success',
                        "message" => "Password Successfully Updated",
                    ],
                ], 200);
            }else{
                return response()->json([
                    'data' => [
                        "status"  => 'error',
                        "message" => "Your given otp does not match.",
                    ],
                ], 200);
            }            
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "Email or token you have passed is expired",
                ],
            ], 200);
        }

    }

    public function changePassword(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'oldpassword'      => 'required|min:6|max:50',
                'newpassword'      => 'required|min:6|max:50',
                'confirm_password' => 'required|same:newpassword',
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

            $hashedPassword = Auth::user()->password;

            if (\Hash::check($request->oldpassword, $hashedPassword)) {

                if (!\Hash::check($request->newpassword, $hashedPassword)) {

                    $users           = User::find(Auth::user()->id);
                    $users->password = bcrypt($request->newpassword);
                    User::where('id', Auth::user()->id)->update(array('password' => $users->password));

                    return response()->json([
                        "status"  => true,
                        "message" => "password updated successfully.",
                    ], 200);
                } else {
                    return response()->json([
                        "status"     => false,
                        "message"    => "new password can not be the old password!",
                        "error_type" => 2,
                    ], 200);

                }

            } else {

                return response()->json([
                    "status"     => false,
                    "message"    => "Old password doesn't match! ",
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
}
