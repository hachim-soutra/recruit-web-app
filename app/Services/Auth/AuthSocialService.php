<?php

namespace App\Services\Auth;

use App\Enum\Payments\SubscriptionStatusEnum;
use App\Models\Candidate;
use App\Models\Coach;
use App\Models\Employer;
use App\Models\PlanPackage;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Payment\StripeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthSocialService
{
    public function handleSocialLogin($user, $user_type, $provider): array
    {
        $result = $this->findUserByProviderIdAndEmail($user->id, $user->email);
        if ($result) {
            return [
                'is_exist' => true,
                'user'     => $result
            ];
        } else {
            $result = $this->saveOrUpdateUser($user, $user_type, $provider);
            return [
                'is_exist' => false,
                'user'     => $result
            ];
        }
    }
    private function findUserByProviderIdAndEmail($provider_id, $email)
    {
        $user = User::where([
            'provider_id'       => $provider_id,
            'email'             => $email
        ])->first();
        if ($user) {
            return $user;
        }
        return null;
    }

    private function saveOrUpdateUser($user, $user_type, $provider)
    {
        $name_explode = explode(' ', $user->name);
        $first_name = $name_explode[0];
        $last_name = array_key_exists(1, $name_explode) ? $name_explode[1] : '';
        $result = User::updateOrCreate([
            'email' => $user->email,
        ], [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'name' => $user->name,
            'user_key' => 'JP' . rand(100, 999) . date("his"),
            'email' => $user->email,
            'password' => Hash::make('123456'),
            'avatar' => $user->avatar,
            'user_type' => $user_type,
            'email_verified_at' => date('Y-m-d H:i:s'),
            'provider_id' => $user->id,
            'provider' => $provider,
            'provider_token' => $user->token,
        ]);

        if($user_type == 'candidate') {
            $this->saveOrUpdateCandidate($result->id);
        }elseif ($user_type == 'employer') {
            $this->saveOrUpdateEmployer($result->id);
        }elseif ($user_type == 'coach') {
            $this->saveOrUpdateCoach($result->id);
        }

        return $result;
    }

    private function saveOrUpdateCandidate($user_id)
    {
        Candidate::create([
            'user_id' => $user_id
        ]);
    }

    private function saveOrUpdateEmployer($user_id)
    {
        Employer::create([
            'user_id' => $user_id
        ]);
    }

    private function saveOrUpdateCoach($user_id)
    {
        Coach::create([
            'user_id' => $user_id
        ]);
    }
}
