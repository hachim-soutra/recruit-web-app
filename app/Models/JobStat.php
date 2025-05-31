<?php

namespace App\Models;

use App\Enum\Jobs\JobStatsTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStat extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'guest_ip', 'user_id', 'type'];

    protected $casts = [
        'type'            => JobStatsTypeEnum::class
    ];
}
