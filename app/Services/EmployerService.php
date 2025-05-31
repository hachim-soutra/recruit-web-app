<?php

namespace App\Services;

use App\Enum\Payments\PlanStatusEnum;
use App\Enum\Payments\PlanTypeStatusEnum;
use App\Enum\Payments\SubscriptionStatusEnum;
use App\Models\Employer;

class EmployerService
{
    /*
     * INFO: Exclude employers with subscription IN_USE and not free subscription employer + subscription WAITING or PURCHASED)
    */
    public function getEmployersUnsubscribed()
    {
        return Employer::whereHas("user", function ($q) {
            $q->where('status', 1);
        })
            ->where(function ($qer) {
                $qer->whereDoesntHave("user.subscriptions", function ($q) {
                    $q->where("status", SubscriptionStatusEnum::IN_USE->value)->whereHas('plan_package.plan', function ($q) {
                        $q->where("plan_type", "!=", PlanTypeStatusEnum::FREE);
                    })->orWhereIn("status", [SubscriptionStatusEnum::WAITING->value, SubscriptionStatusEnum::PURCHASED->value]);
                });
            })
            ->with("user")
            ->get()
            ->sortBy("user.name");
    }
}
