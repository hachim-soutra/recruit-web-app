<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Coach extends Model

{

    use HasFactory;



    protected $table = 'coaches';

    protected $fillable = [
        'user_id', 'address', 'city', 'state', 'country', 'zip',

        'total_experience_year', 'total_experience_month', 'alternate_mobile_number', 'highest_qualification', 'specialization', 'university_or_institute', 'year_of_graduation', 'education_type', 'date_of_birth', 'coach_type', 'preferred_job_type', 'gender', 'marital_status', 'resume', 'resume_title', 'linkedin_link', 'contact_link', 'bio', 'teaching_history', 'active_teaching_history', 'about_us', 'faq', 'how_we_help', 'coach_skill', 'skill_details', 'coach_banner', 'avatar',
        'facebook_link', 'instagram_link'
    ];

    protected $appends = ['coach_banner_path', 'coach_banner', 'experience'];

    protected $hidden = ['subscriptions'];

    public function user()

    {

        return $this->belongsTo(User::class);
    }



    public function coachbookmark()

    {

        return $this->hasMany(CoachBookmark::class, 'user_id', 'coach_id');
    }

    public function getExperienceAttribute()
    {
        $year = (int) ($this->total_experience_month / 12);
        $months = $this->total_experience_month % 12;
        $experience = '';
        if ($year > 0) {
            $experience .= $year == 1 ? "$year yr " : "$year years ";
        }
        if ($months > 0) {
            $experience .= $months == 1 ? "$months month " : "$months months ";
        }
        return $experience;
    }

    public function getCoachBannerPathAttribute()
    {
        if (array_key_exists('coach_banner', $this->attributes)) {

            return $this->attributes['coach_banner'];
        } else {

            return "";
        }
    }

    public function getCoachBannerAttribute($value)
    {
        if ($value == null) {
            return env('APP_URL') . "/uploads/coach_banner/no_banner.jpg";
        }
        if (file_exists(public_path('/uploads/coach_banner/' . $value))) {
            return asset('/uploads/coach_banner/' . $value);
        } else {
            return env('APP_URL') . "/uploads/coach_banner/no_banner.jpg";
        }

    }

}
