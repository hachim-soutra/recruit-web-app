<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = ['coupon_title', 'code', 'coupon_type', 'coupon_for', 'coupon_amount', 'posted_job_ammount', 'total_usage', 'coupon_limit', 'coupon_start_date', 'coupon_expiry_date', 'description', 'status', 'created_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select('id', 'name', 'email', 'avatar');
    }

    public function coupon_user()
    {
        return $this->hasMany(CouponUser::class)
        ->orderBy('created_at', 'DESC')
        ->with(['user' => function ($q) {
            $q->select('id', 'name', 'email');
        }]);
    }



}
