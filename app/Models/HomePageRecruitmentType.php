<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageRecruitmentType extends Model
{
    use HasFactory;
    protected $append = ['type_file'];
    protected $fillable = [
        'recruitment_type', 'recruitment_type_file'
    ];

    public function getTypeFileAttribute(){
        return asset('uploads/cms/home/').'/'.$this->recruitment_type_file;   
    }

}
