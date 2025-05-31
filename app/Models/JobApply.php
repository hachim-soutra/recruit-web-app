<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApply extends Model
{
    use HasFactory;

    protected $table = 'job_applies';

    protected $fillable = ['job_id', 'candidate_id', 'status'];

    public function jobs()
    {
        return $this->belongsTo(JobPost::class, 'job_id', 'id')->select('id', 'job_details', 'qualifications', 'job_skills', 'experience', 'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'city', 'state', 'country', 'zip', 'preferred_job_type', 'additinal_pay');

    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id', 'id')->select('id', 'name', 'user_key', 'email', 'mobile', 'avatar');

    }
}
