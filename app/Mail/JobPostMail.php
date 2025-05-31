<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobPostMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;

    }
    public function build()
    {
        $address = 'info@recruit.ie';
        $name    = 'Recruit.ie';
        $subject = 'Job Posting Mail';

        return $this->view('emails.job_post')
            ->from($address, $name)
//   ->replyTo($this->data['client_email'], $this->data['client_name'])
            ->subject($subject)
            ->with(['user' => $this->user]);

    }
}
