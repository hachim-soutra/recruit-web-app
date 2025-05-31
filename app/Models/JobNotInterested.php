<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobNotInterested extends Model
{
    use HasFactory;

    protected $table = 'job_not_interesteds';

    protected $fillable = ['job_id', 'candidate_id', 'not_interest_for'];

    public function jobs()
    {
        return $this->belongsTo(JobPost::class, 'job_id', 'id')->select('id', 'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'city', 'state', 'country', 'zip', 'preferred_job_type', 'additinal_pay');

    }
}
