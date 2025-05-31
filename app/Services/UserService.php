<?php

namespace App\Services;

use App\Mail\VerifyUser;
use App\Mail\Welcome;
use App\Models\Candidate;
use App\Models\Coach;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserService
{
    public function register(array $data): User
    {
        $user                 = new User();
        $user->name           = $data['first_name'] . " " . $data['last_name'];
        $user->first_name     = $data['first_name'];
        $user->last_name      = $data['last_name'];
        $user->email          = $data['email'];
        $user->user_type      = $data['usertype'];
        $user->source         = $data['source'];
        $user->mobile         = $data['phone_number'] ?? null;
        $user->user_key       = 'JP' . rand(100, 999) . date("his");
        $user->password       = Hash::make($data['password']);
        $user->remember_token = Str::random(10);
        $user->verify_token   = Str::random(12) . rand(10000, 99999);
        $user->save();
        if ($user->user_type === "employer") {
            $user->syncRoles([2]);
            $user_profile          = new Employer();
            $user_profile->user_id = $user->id;
            $user_profile->save();
        }
        if ($user->user_type === "candidate") {
            $user->syncRoles([3]);
            $user_profile          = new Candidate();
            $user_profile->user_id = $user->id;
            $user_profile->save();
        }
        if ($user->user_type === "coach") {
            $user->syncRoles([4]);
            $user_profile          = new Coach();
            $user_profile->user_id = $user->id;
            $user_profile->save();
        }
        Mail::to($user->email)->send(new VerifyUser($user));
        Mail::to($user->email)->send(new Welcome($user, $data['password']));
        return $user;
    }
}
