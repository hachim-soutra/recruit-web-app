<?php

namespace App\Services\Payment;

use App\Enum\Payments\PlanStatusEnum;
use App\Enum\Payments\PlanTypeStatusEnum;
use App\Enum\Payments\SubscriptionStatusEnum;
use App\Jobs\Plan\AssignEmployersToPlanJob;
use App\Models\Employer;
use App\Models\Plan;
use App\Models\PlanPackage;
use App\Models\Subscription;

class PlanService
{
    protected $planModel;

    protected $stripeService;

    public function __construct(Plan $plan, StripeService $stripeService)
    {
        $this->planModel = $plan;
        $this->stripeService = $stripeService;
    }

    public function find_all($keyword)
    {
        return $this->planModel::where("slug", "LIKE", "%$keyword%")
            ->orWhere("title", "LIKE", "%$keyword%")
            ->orWhere("description", "LIKE", "%$keyword%")
            ->orderBy('id', 'asc')->paginate(20);
    }

    public function find_by_id($id): ?Plan
    {
        return Plan::with(['packages'])->find($id);
    }

    public function store(array $planData): ?Plan
    {
        $product = $this->stripeService->create_product($planData);
        if ($product->id) {
            $plan = Plan::create([
                'title'               => $planData['title'],
                'slug'                => $planData['slug'],
                'description'         => $planData['description'],
                'plan_for'            => $planData['plan_for'],
                'plan_type'           => $planData['plan_type'],
                'job_number'          => $planData['job_number'],
                'status'              => PlanStatusEnum::ACTIVE,
            ]);
            $package = PlanPackage::create([
                'number_of_month'     => $planData['number_of_month'],
                'price'               => intval($planData['price']),
                'plan_id'             => $plan->id,
                'stripe_plan'         => $product->default_price,
                'stripe_product'      => $product->id,
                'slug'                => null
            ]);

            if ($plan->id && $package->id) {
                return $plan;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function update(array $planData, Plan $plan): bool
    {
        try {
            $plan->update([
                'title'               => $planData['title'],
                'slug'                => $planData['slug'],
                'description'         => $planData['description'],
                'status'              => $planData['status'],
                'plan_for'            => $planData['plan_for'],
                'plan_type'           => $planData['plan_type'],
                'job_number'          => $planData['job_number']
            ]);
            $package = $plan->packages[0];

            if ($package->price != intval($planData['price'])) {
                $planData['stripe_product'] = $package->stripe_product;
                $planData['number_of_month'] = $package->number_of_month;
                $planData['old_stripe_plan'] = $package->stripe_plan;
                $price = $this->stripeService->create_price($planData);
                if ($price->id) {
                    $package->update([
                        'price'               => $planData['price'],
                        'stripe_plan'         => $price->id,
                    ]);
                } else {
                    return false;
                }
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function update_status(Plan $plan): Plan
    {
        $plan->update([
            'status' =>
            $plan->status == PlanStatusEnum::ACTIVE ?
                PlanStatusEnum::INACTIVE : PlanStatusEnum::ACTIVE
        ]);
        return $plan;
    }

    /**
     * @throws \Throwable
     */
    public function delete(Plan $plan): bool
    {
        $package = PlanPackage::find($plan->packages[0]->id);
        $product = $this->stripeService->archive_product($package->stripe_product);
        if ($product->id && !$product->active) {
            $deleted = $package->deleteOrFail();
            if ($deleted) {
                return $plan->deleteOrFail();
            } else {
                return false;
            }
        }
        return false;
    }

    public function assignEmployersToPlan($plan, $employers)
    {
        if ($plan->plan_type === PlanTypeStatusEnum::FREE) {
            foreach ($employers as $employer) {
                $employer = Employer::with("user")->find($employer);
                $user = $employer?->user;
                if ($user) {
                    Subscription::create([
                        "user_id" => $user->id,
                        "subscription_id" => null,
                        "plan_package_id" => $plan->packages[0]->id,
                        "stripe_id" => null,
                        "estimated_end_date" => now()->addMonths($plan->packages[0]->number_of_month),
                        "reel_end_date" => now()->addMonths($plan->packages[0]->number_of_month),
                        "status" => !$user?->valid_subscription?->balanceIsValid() ? SubscriptionStatusEnum::IN_USE->value : SubscriptionStatusEnum::PURCHASED->value
                    ]);
                }
            }
        } else {
            AssignEmployersToPlanJob::dispatch($plan->packages[0]->stripe_plan, $plan->packages[0]->id, $employers);
        }
    }
}
