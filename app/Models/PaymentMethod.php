<?php

namespace App\Models;

use App\Enum\Payments\PaymentMethodStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'status'
    ];

    protected $casts = [
        'status'            => PaymentMethodStatusEnum::class,
    ];

    /**
     * Get the subscriptions for the payment.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
