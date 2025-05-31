<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = ['title', 'price', 'vat', 'vatprice', 'plan_key', 'plan_interval', 
        'number_of_job_post', 'package_for', 'details', 'status'];
}
