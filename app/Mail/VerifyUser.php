<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {

        $address = 'info@recruit.ie'; //'admin@nfcmate.com'; 
        $name    = 'Recruit.ie';
        $subject = 'Email Verification';

        return $this->view('emails.verify_user')
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with(['user' => $this->user]);
    }

}
