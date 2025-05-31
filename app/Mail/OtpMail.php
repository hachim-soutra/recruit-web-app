<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        $address = 'info@recruit.ie';//'admin@nfcmate.com';
        $name    = 'Recruit.ie';
        $subject = 'Email Verification Otp';
        return $this->view('emails.otp')
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with(['otp' => $this->otp]);
    }
}
