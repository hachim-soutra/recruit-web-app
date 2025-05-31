<?php

namespace App\Models;

use App\Enum\Jobs\AdvertiseStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAdvertise extends Model
{
    use HasFactory;


    protected $fillable = [
        'job_type', 'company_name', 'first_name', 'last_name', 'email', 'phone', 'status'
    ];

    protected $casts = [
        'status'            => AdvertiseStatusEnum::class,
    ];
}
