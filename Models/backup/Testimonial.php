<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    protected $appends = ['testimonial_logo'];
    
    protected $fillable = [
        'name', 'subject', 'image', 'email',
        'designation', 'rating', 'status', 
        'created_by', 'updated_by'
    ];

    public function getTestimonialLogoAttribute()
    {
        $logo = $this->image;
        if ($logo != '') {
            return env('APP_URL')."/uploads/".$logo;
        } else {
            return env('APP_URL')."/uploads/noimg.jpg";
        }
    }
}
