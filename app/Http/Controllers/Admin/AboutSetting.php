<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'heading', 'left_detail', 'right_detail', 'banner_file', 'banner_description', 'aboutus_video', 
        'glorious_year', 'happy_client', 'talented_candidate', 'jobs_expo', 'counter_image'
    ];
}
