<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\ChatRoom;
use App\Models\JobPost;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatNotificationMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(public ChatRoom $chat)
    {
        $this->chat     = $chat;
    }

    public function build()
    {
        return $this->view('emails.chat-notification', [
            "chat" => $this->chat
        ])->subject("You have a new message in recruit.ie");
    }
}
