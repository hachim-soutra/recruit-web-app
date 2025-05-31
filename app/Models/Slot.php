<?php

namespace App\Models;

use App\Enum\Payments\SlotGoodTypeStatusEnum;
use App\Enum\Payments\SlotStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'status',
        'good_type',
        'good_number',
        'status',
        'price',
        'stripe_plan',
        'stripe_product'
    ];

    protected $casts = [
        'status'            => SlotStatusEnum::class,
        'good_type'         => SlotGoodTypeStatusEnum::class,
    ];

    /**
     * The packages that belong to the slot.
     */
    public function packages()
    {
        return $this->belongsToMany(PlanPackage::class, 'plan_package_slot')
            ->withTimestamps();
    }

    /**
     * The subscriptions that belong to the slot.
     */
    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'subscription_slot');
    }

    public function getLabelAttribute()
    {
        return $this->good_number > 1 ? "{$this->good_number} slots" : "{$this->good_number} slot";
    }
}
