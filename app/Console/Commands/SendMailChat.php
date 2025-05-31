<?php

namespace App\Console\Commands;

use App\Mail\ChatNotificationMail;
use App\Models\ChatRoom;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mail-chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $chats = ChatRoom::where("is_sent_mail_chat", false)->get();
        foreach ($chats as $chat) {
            Log::alert($chat->updated_at->diffInMinutes(now(), true));
            if (
                $chat->last_message &&
                $chat->updated_at->diffInMinutes(now(), true) > 10
            ) {
                Log::alert($chat->user_last_message);
                if ($chat->user_last_message->id == $chat->author_id) {
                    Log::alert("client");
                    Log::alert($chat->client->email);
                    Mail::to($chat->client->email)->send(new ChatNotificationMail($chat));
                } else {
                    Log::alert("author");
                    Log::alert($chat->author->email);
                    Mail::to($chat->author->email)->send(new ChatNotificationMail($chat));
                }

                $chat->is_sent_mail_chat = true;
                $chat->save();
            }
        }
    }
}
