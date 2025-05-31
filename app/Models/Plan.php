<?php

namespace App\Models;

use App\Enum\Payments\PlanForEnum;
use App\Enum\Payments\PlanStatusEnum;
use App\Enum\Payments\PlanTypeStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'plans';

    protected $fillable = [
        'title',
        'description',
        'slug',
        'status',
        'plan_for',
        'job_number',
        'status',
        'plan_type'
    ];

    protected $casts = [
        'status'            => PlanStatusEnum::class,
        'plan_for'          => PlanForEnum::class,
        'plan_type'          => PlanTypeStatusEnum::class
    ];

    /**
     * Get the packages for the plan.
     */
    public function packages(): HasMany
    {
        return $this->hasMany(PlanPackage::class);
    }

    public function getSlotDescriptionAttribute()
    {
        return $this->job_number.' job '.($this->job_number > 1 ? ' slots' : ' slot');
    }
}
