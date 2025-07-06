<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrierJobUser extends Model
{
    use HasFactory;

    protected $table = 'carrier_job_user';

    protected $guarded = [];

    public function job()
    {
        return $this->belongsTo(JobPost::class);
    }
}
