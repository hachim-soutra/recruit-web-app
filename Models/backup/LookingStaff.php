<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookingStaff extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_type', 'content'
    ];
}
