<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobAppliedNotifyMail extends Mailable
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
        $address = 'info@recruit.ie'; //'admin@nfcmate.com'; admin@nfcmate.com.
        $name    = 'Recruit.ie';
        $subject = 'New Applicant For Your Job!';
        return $this->view('emails.job_applied_notify')
            ->from($address, $name)
        //  ->replyTo($this->data['client_email'], $this->data['client_name'])
            ->subject($subject)
            ->with(['user' => $this->user]);

    }
}
