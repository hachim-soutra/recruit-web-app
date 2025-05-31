<?php

namespace App\Models;

use App\Models\JobNotInterested;
use App\Models\JobPost;
use App\Models\JobReport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JobPost extends Model
{
    use HasFactory;

    protected $table = 'job_posts';

    protected $appends = ['job_reported', 'not_interested_job', 'company_logo', 'company_name'];
    // 'not_interested_job',

    protected $fillable = ['employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'qualifications', 'city', 'state', 'country', 'zip', 'job_skills', 'functional_area', 'preferred_job_type', 'experience', 'total_hire', 'job_details', 'status', 'payment_status', 'job_status', 'additinal_pay', 'created_by', 'updated_by',
    ];
   

    public function applicatons()
    {
        return $this->hasMany(JobApply::class, 'job_id', 'id');

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

    public function getCompanyNameAttribute()
    {
        $reported = Employer::where('user_id', $this->attributes['employer_id'])
            ->first();
        if ($reported) {
            return $reported->company_name;
        } else {
            return "";
        }

    }

    public function getCompanyLogoAttribute()
    {
        
        $logo = Employer::where('user_id', $this->attributes['employer_id'])
            ->first();

        if ($logo) {
            return $logo->company_logo;
        } else {
            return env('APP_URL')."/uploads/company_logo/no_logo.jpeg";
        }

    }

    public function getJobReportedAttribute()
    {
        $reported = JobReport::where('job_id', $this->attributes['id'])
            ->where('candidate_id', Auth::user()->id)
            ->first();
        if ($reported) {
            return 1;
        } else {
            return 0;
        }

    }

    public function getNotInterestedJobAttribute()
    {
        $intested = JobNotInterested::where('job_id', $this->attributes['id'])
            ->where('candidate_id', Auth::user()->id)
            ->first();
        if ($intested) {
            return 1;
        } else {
            return 0;
        }

    }
}
