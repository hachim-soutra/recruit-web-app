<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdvertiseGuestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $advertise;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($advertise)
    {
        $this->advertise = $advertise;

    }
    public function build()
    {
        $address = 'info@recruit.ie';
        $name    = 'Recruit.ie';
        $subject = 'Advertise Job Mail';

        return $this->view('emails.advertise-job-thankyou')
            ->from($address, $name)
            ->subject($subject)
            ->with(['advertise' => $this->advertise]);

    }
}
