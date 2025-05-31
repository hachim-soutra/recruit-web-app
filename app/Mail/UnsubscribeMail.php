<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\JobPost;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnsubscribeMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(public string $motif, public string $email)
    {
        $this->motif     = $motif;
        $this->email     = $email;
    }

    public function build()
    {
        return $this->view('emails.unsubscribe')->subject("Unsubscription Notification: $this->email from our Mailing List");
    }
}
