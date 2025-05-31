<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageContent extends Model
{
    use HasFactory;
    protected $append = ['banner_file_get'];
    protected $fillable = [
        'banner_heading', 'banner_file', 'recruitment_content_heading', 'recruitment_content_description',
        'glorious_years', 'job_filled', 'job_vacancy'
    ];

    public function getBannerFileGetAttribute(){
        return asset('uploads/cms/home/').'/'.$this->banner_file;        
    }
}
