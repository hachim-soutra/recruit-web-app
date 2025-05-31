<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdvertiseFinishRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $advertise;

    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($advertise, $user)
    {
        $this->advertise = $advertise;
        $this->user = $user;

    }
    public function build()
    {
        $address = 'info@recruit.ie';
        $name    = 'Recruit.ie';
        $subject = 'Finish Registration Mail';

        return $this->view('emails.advertise-job-registration')
            ->from($address, $name)
            ->subject($subject)
            ->with(['advertise' => $this->advertise, 'user' => $this->user]);

    }
}
