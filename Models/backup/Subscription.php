<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'stripe_subscription_id', 'stripe_customer_id', 'user_id', 'transaction_id', 'plan_id', 'payment_method', 'paid_amount', 'paid_amount_currency', 'plan_interval', 'plan_interval_count', 'payer_email', 'created', 'plan_period_start', 'plan_period_end', 'status', 'cancele_reason', 'canceletion_date', 'upgrade_subscription_date',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Package::class, 'plan_id', 'id');
    }

}
