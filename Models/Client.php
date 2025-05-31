<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $appends = ['client_logo'];
    
    protected $fillable = [
        'name', 'isfeatured', 'image', 'description', 'status', 'created_by', 'updated_by'
    ];

    public function getClientLogoAttribute()
    {
        $logo = $this->image;
        if ($logo != '') {
            return env('APP_URL')."/uploads/".$logo;
        } else {
            return env('APP_URL')."/uploads/company_logo/no_logo.jpeg";
        }
    }
}
