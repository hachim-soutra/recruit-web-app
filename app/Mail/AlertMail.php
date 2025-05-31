<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\JobPost;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(public Campaign $campaign, public string $email)
    {
        $this->campaign     = $campaign;
        $this->email     = $email;
    }

    public function build()
    {
        return $this->view('emails.alert', [
            "title" => $this->campaign->title,
            "email" => $this->email,
            "description" => $this->campaign->description,
            "jobs" => JobPost::whereIn("id", json_decode($this->campaign->jobs))->get(),
        ])->subject($this->campaign->object);
    }
}
