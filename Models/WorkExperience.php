<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $table = 'work_experiences';

    protected $fillable = ['candidate_id', 'job_title', 'company', 'address', 'currently_work_here', 'from_date', 'end_date', 'details', 'status'];

}
