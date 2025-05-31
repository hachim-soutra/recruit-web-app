<?php

namespace App\Services\Payment;

use App\Enum\Payments\PlanTypeStatusEnum;
use App\Enum\Payments\SubscriptionStatusEnum;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionService
{

    public function find_all_waiting_subscription($keyword)
    {
        return Subscription::Where('stripe_status', 'inactive')
            ->with('plan_package', 'user', 'plan_package.plan', 'user.employer')
            ->where(function ($q) use ($keyword) {
                $q->orWhereRelation('user', 'name', "LIKE", "%$keyword%");
                $q->orWhereRelation('user', 'email', "LIKE", "%$keyword%");
                $q->orWhereRelation('user.employer', 'company_name', "LIKE", "%$keyword%");
            })
            ->orderBy('id', 'desc')->paginate(20);
    }

    public function activate_waiting_subscription($id): array
    {
        $subscription = Subscription::with('plan_package', 'plan_package.plan')->findOrFail($id);

        // CHECK IF SUBSCRIPTION NOT PAYED YET
        if ($subscription->stripe_id == null) {
            return [ 'result' => false, 'message' => 'Subscription not payed yet.' ];
        }
        $user = $subscription->user()->with('employer')->first();
        // CHECK IF USER HAS VALID SUBSCRIPTION
        if ($user->valid_subscription != null) {
            $validSubscription = $user->subscriptions()->with('plan_package', 'plan_package.plan')
                                    ->where('status', SubscriptionStatusEnum::IN_USE)->first();
            // CHECK IF VQLID SUBSCRIPTION ID FREE
            if (
                $validSubscription->plan_package &&
                $validSubscription->plan_package->plan &&
                $validSubscription->plan_package->plan->plan_type == PlanTypeStatusEnum::FREE
            ) {
                $this->deactivate_subscription($validSubscription);
                $this->activate_subscription($subscription);
                return [ 'result' => true, 'message' => 'Subscription activated successfully.' ];
            } else {
                return [ 'result' => false, 'message' => 'Employer have a subscription in use.' ];
            }
        } else {
            $this->activate_subscription($subscription);
            return [ 'result' => true, 'message' => 'Subscription activated successfully.' ];
        }
    }

    private function deactivate_subscription($subscription)
    {
//        $this->archive_subscription_jobs($subscription);
        $subscription->update([
            'status'            => SubscriptionStatusEnum::FINISHED,
            'reel_end_date'     => Carbon::now(),
        ]);
    }

    private function activate_subscription($subscription)
    {
        $start_date = Carbon::now();
        $subscription->update([
            'start_date'            => $start_date,
            'estimated_end_date'    => $start_date->addMonths($subscription->plan_package->number_of_month),
            'reel_end_date'         => $start_date->addMonths($subscription->plan_package->number_of_month),
            'status'                => SubscriptionStatusEnum::IN_USE,
            'stripe_status'         => 'active'
        ]);
    }

    private function archive_subscription_jobs($subscription)
    {
        $subscription->jobPosts()->update([
            'job_status'        => 'Save as Draft',
            'status'            => 0,
            'show_in_home'      => 0,
            'job_expiry_date'   => Carbon::now()
        ]);
    }

}
