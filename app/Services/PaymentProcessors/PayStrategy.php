<?php

namespace App\Services\PaymentProcessors;

use App\Http\Requests\Site\Payment\PostSubscriptionRequest;
use App\Models\User;

interface PayStrategy
{
    public function createSetupIntent();
    public function createSubscription(User $user, $planPackage, PostSubscriptionRequest $request);
    public function createSlot(User $user, $planPackage, PostSubscriptionRequest $request);
}
