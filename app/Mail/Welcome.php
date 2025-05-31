<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Welcome extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    public function __construct($user, $password)
    {
        $this->user     = $user;
        $this->password = $password;
    }

    public function build()
    {
        $address = 'info@recruit.ie'; //'admin@nfcmate.com';
        $name    = 'Recruit.ie';
        $subject = 'Welcome';
        return $this->view('emails.welcome')
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with(['user' => $this->user], ['password' => $this->password]);
    }
}
