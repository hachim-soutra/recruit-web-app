<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobUpadteStatusMail extends Mailable
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
        $address = 'info@recruit.ie';//'admin@nfcmate.com';
        $name    = 'Recruit.ie';
        $subject = 'Job Status Update !';

        return $this->view('emails.job_update')
            ->from($address, $name)
//   ->replyTo($this->data['client_email'], $this->data['client_name'])
            ->subject($subject)
            ->with(['user' => $this->user]);

    }
}
