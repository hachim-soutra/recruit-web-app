<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\User;

class VerificationController extends Controller
{
    public function verifyUser($token)
    {
        $user = User::where('verify_token', $token)->first();
        if (isset($user)) {
            $user->email_verified    = 1;
            $user->email_verified_at = now();
            $user->save();
            return view('site.auth.verifyUserSuccess');
        } else {
            return view('site.auth.verifyUserFail');
        }
    }
}
