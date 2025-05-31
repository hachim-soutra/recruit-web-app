<?php

namespace App\Models;

use App\Enum\Payments\PackageStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'plan_packages';

    protected $fillable = [
        'slug',
        'number_of_month',
        'status',
        'price',
        'stripe_plan',
        'stripe_product',
        'plan_id'
    ];

    protected $hidden = ['subscriptions'];

    protected $casts = [
        'status'            => PackageStatusEnum::class,
    ];

    /**
     * Get the plan that owns the package.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the packages for the plan.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * The slotes that belong to the plan package.
     */
    public function slots()
    {
        return $this->belongsToMany(Slot::class, 'plan_package_slot')->withTimestamps();
    }
}
