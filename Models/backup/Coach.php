<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $table = 'coaches';

    protected $appends = ['coach_banner_path', 'coach_banner'];

    protected $fillable = ['user_id', 'address', 'city', 'state', 'country', 'zip',
        'total_experience_year', 'total_experience_month', 'alternate_mobile_number', 'highest_qualification', 'specialization', 'university_or_institute', 'year_of_graduation', 'education_type', 'date_of_birth', 'coach_type', 'preferred_job_type', 'gender', 'marital_status', 'resume', 'resume_title', 'linkedin_link', 'contact_link', 'bio', 'teaching_history', 'active_teaching_history', 'about_us', 'faq', 'how_we_help', 'coach_skill', 'skill_details', 'coach_banner'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function coachbookmark()
    {
        return $this->hasMany(CoachBookmark::class, 'user_id', 'coach_id');
    }

    public function getCoachBannerAttribute()
    {
        if ($this->attributes['coach_banner'] != '') {  
            return asset('/uploads/coach_banner/' . $this->attributes['coach_banner']);          
        } else {
            return asset('/uploads/coach_banner/nobanner.png');
        }
    }

    public function getCoachBannerPathAttribute()
    {
        if (array_key_exists('coach_banner', $this->attributes)) {
            return $this->attributes['coach_banner'];
        } else {
            return "";
        }
    }
}
