<?php

namespace App\Models;

use App\Enum\Payments\SubscriptionStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $guarded = [];

    protected $casts = [
        'status'            => SubscriptionStatusEnum::class,
    ];

    protected $dates = [
        "estimated_end_date",
        "reel_end_date"
    ];

    public function balanceIsValid()
    {
        return count($this->jobPosts) < $this->count_slot;
    }

    public function checkIfExpired()
    {
        return $this->estimated_end_date->lt(now());
    }

    /**
     * Get the plan_package that owns the subscription.
     */
    public function plan_package(): BelongsTo
    {
        return $this->belongsTo(PlanPackage::class);
    }

    /**
     * Get the user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get the user that owns the subscription.
     */
    public function jobPosts(): HasMany
    {
        return $this->hasMany(JobPost::class);
    }

    /**
     * The slots that belong to the subscription.
     */
    public function slots()
    {
        return $this->belongsToMany(Slot::class, 'subscription_slot')
            ->withTimestamps()
            ->withPivot(['created_at', 'charge_token']);
    }

    /**
     * Get the payment method for the subscription.
     */
    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getAmountAttribute()
    {
        return $this->slots()->sum('price') + $this->plan_package->price;
    }

    public function getCountSlotAttribute()
    {
        return $this->slots()->sum("good_number") + $this->plan_package->plan->job_number;
    }

    public function getSlotWithPeriodAttribute()
    {
        $endDate = Carbon::parse($this->estimated_end_date)->format('jS M Y');
        return $endDate;
//        $months = Carbon::parse($this->estimated_end_date)->diffInMonths(Carbon::parse($this->start_date));
//        if ($months == 1) {
//            return 'one month';
//        }
//        else if ($months < 12) {
//            return $months.' months';
//        } else {
//            $year = intval($months / 12);
//            $month = $months % 12;
//            $text = $year.($year > 1 ? ' years' : ' year').($month > 0 ? ' and '.$month. ($month > 1 ? ' months' : ' month') : '');
//            return $text;
//        }
    }
}
