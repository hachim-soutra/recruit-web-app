<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'heading', 'detail' ,'aboutus_image'
    ];
}
