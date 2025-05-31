<?php

use App\Enum\Payments\SubscriptionStatusEnum;
use App\Models\JobPost;
use App\Models\User;
use Carbon\Carbon;

if (!function_exists('check_subscription_date_is_valid')) {
    function check_subscription_date_is_valid($user_id): bool
    {
        $user = User::find($user_id);
        $estimated_end_date = Carbon::parse($user->valid_subscription->estimated_end_date);
        if (Carbon::now()->gt($estimated_end_date)) {
            return false;
        }
        return true;
    }
}

if (!function_exists('checkSubscriptionBalanceIsValid')) {
    function checkSubscriptionBalanceIsValid($user_id)
    {
        $user = User::with('subscriptions')->find($user_id);
        return $user?->valid_subscription?->balanceIsValid();
    }
}

if (!function_exists('checkUserHasPurchasedSubscription')) {
    function checkUserHasPurchasedSubscription($user)
    {
        return $user->subscriptions()->where("status", SubscriptionStatusEnum::PURCHASED->value)->first();
    }
}
