<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'recipient' => 'json',
        'jobs' => 'json',
        'date_envoi' => 'datetime'
    ];

    public function getJobsModelsAttribute()
    {
        return JobPost::whereIn("id", json_decode($this->jobs))->get();
    }
    public function getMailAttribute()
    {
        $jobs = $this->jobs_models;
        $title = $this->title;
        $email = "test@gmail.com";
        $description = $this->description;
        return view('emails.alert', compact('jobs', 'title', 'description', 'email'))->render();
    }
}
