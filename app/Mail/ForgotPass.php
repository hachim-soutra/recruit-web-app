<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPass extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public function __construct($user)
    {
        $this->user     = $user;
    }

    public function build()
    {
        $address = 'info@recruit.ie'; //'admin@nfcmate.com';
        $name    = 'Recruit.ie';
        $subject = 'Forgot Password';
        return $this->view('emails.forgotpass')   
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with(['user' => $this->user]);
    }
}
