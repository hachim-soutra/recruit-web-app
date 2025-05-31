<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $c_data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($c_data)
    {
        $this->c_data = $c_data;

    }
    public function build()
    {
        $address = 'info@recruit.ie';//'admin@nfcmate.com';
        $name    = 'Recruit.ie';
        $subject = 'Canceled Subscription';

        return $this->view('emails.cancel_subscription')
            ->from($address, $name)
            ->replyTo('info@recruit.ie', 'Recruit Admin')
            ->subject($subject)
            ->with(['data' => $this->c_data]);
    }
}
