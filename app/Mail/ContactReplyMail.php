<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $replydata;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->replydata = $data;

    }

    /**

     * Build the message.

     *

     * @return $this

     */

    public function build()
    {
        $address = 'info@recruit.ie'; //'admin@nfcmate.com';
        $name    = 'Recruit.ie';
        $subject = 'Contact Reply';


        return $this->view('emails.contactreply')
            ->from($address, $name)
        //   ->replyTo($this->data['client_email'], $this->data['client_name'])
            ->subject($subject)
            ->with(['replydata' => $this->replydata]);

    }
}
