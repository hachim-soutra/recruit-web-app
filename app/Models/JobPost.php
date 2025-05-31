<?php



namespace App\Models;



use App\Models\JobNotInterested;

use App\Models\JobReport;

use App\Models\Bookmark;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;



class JobPost extends Model

{

    use HasFactory;



    protected $table = 'job_posts';



    protected $appends = ['slug', 'job_reported', 'not_interested_job', 'company_logo', 'company_name', 'company_id', 'job_skills_array', 'qualifications_array', 'additinal_pay_array'];



    protected $fillable = [
        'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_mode', 'job_title', 'job_location', 'qualifications', 'city', 'state', 'country', 'zip', 'job_skills', 'functional_area', 'preferred_job_type', 'experience', 'total_hire', 'job_details', 'status', 'payment_status', 'job_status', 'additinal_pay', 'created_by', 'updated_by', 'subscription_id', 'show_in_home'

    ];

    public function applicatons()

    {

        return $this->hasMany(JobApply::class, 'job_id', 'id');
    }



    public function bookmark()
    {

        return $this->hasOne(Bookmark::class, 'job_id', 'id');
    }



    public function employer()

    {

        return $this->belongsTo(User::class, 'employer_id', 'id')->select('id', 'name', 'email', 'avatar');
    }

    public function createdBy()

    {

        return $this->belongsTo(User::class, 'created_by', 'id')->select('id', 'name', 'email', 'avatar');
    }

    public function updaedBy()

    {

        return $this->belongsTo(User::class, 'updated_by', 'id')->select('id', 'name', 'email', 'avatar');
    }

    /**
     * Get the subscriptions for the user.
     */
    public function subscriptions(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function getJobSkillsArrayAttribute()
    {
        return json_decode($this->attributes['job_skills'], true);
    }

    public function getQualificationsArrayAttribute()
    {
        return json_decode($this->attributes['qualifications'], true);
    }

    public function getAdditinalPayArrayAttribute()
    {
        return json_decode($this->attributes['additinal_pay'], true);
    }

    public function company()
    {
        return $this->belongsTo(Employer::class, "employer_id", "user_id");
    }


    public function getCompanyNameAttribute()
    {
        return $this->company->company_name ?? "";
    }


    public function getCompanyLogoAttribute()
    {
        $logo = $this->company?->company_logo;
        if (!$logo) {
            return env('APP_URL') . "/uploads/no_company.jpg";
        }
        $explodeUrl = explode("/", $logo);
        if (file_exists(public_path('uploads/company_logo/' . $explodeUrl[count($explodeUrl) - 1]))) {
            return $logo;
        }
        return env('APP_URL') . "/uploads/no_company.jpg";
    }

    public function getCompanyIdAttribute()

    {
        return $this->company->id ?? "";
    }

    public function getJobReportedAttribute()
    {
        return 0;
    }



    public function getNotInterestedJobAttribute()
    {
        return 0;
    }

    public function getSlugAttribute()
    {
        $company_name = str_replace(' ', '-', $this->company_name);
        $location = str_replace(' ', '-', $this->attributes['job_location']);
        $location = str_replace(',', '', $location);
        return strtolower($location . '-' . $company_name . '-job' . $this->attributes['id']);
    }
}
