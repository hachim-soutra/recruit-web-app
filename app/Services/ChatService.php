<?php

namespace App\Services;

use App\Enum\Payments\ChatTypeEnum;
use App\Events\ChatCreated;
use App\Events\ChatSent;
use App\Events\MessageSent;
use App\Models\ChatRoom;

class ChatService
{
    public function seenMessage($chat)
    {
        $msg = [];
        foreach (json_decode($chat->messages, true) as $message) {
            if (!$message["seen"] && $message["user_id"] != auth()->user()->id) {
                $message["seen"] = now();
            }
            $msg = [...$msg, $message];
        }
        $chat->update([
            'messages' => json_encode($msg)
        ]);
    }

    public function sendMessage($request)
    {
        $chat = ChatRoom::where([
            'author_id' => $request["author_id"],
            'client_id' => $request["client_id"],
        ])->first();
        $message = [
            "message" => $request["message"],
            "created_at" => now(),
            "user_id" => auth()->user()->id,
            "seen" => null,
        ];
        if ($chat) {
            $chat->update([
                'is_sent_mail_chat' => false,
                'messages' => json_encode([...json_decode($chat->messages, true), $message])
            ]);
            if (auth()->user()->id == $request["author_id"]) {
                broadcast(new ChatSent($request["client_id"], $message));
            } else {
                broadcast(new ChatSent($request["author_id"], $message));
            }
        } else {
            $chat = new ChatRoom();
            $chat->client_id = $request["client_id"];
            $chat->messages  = json_encode([$message]);
            $chat->author_id = $request["author_id"];
            $chat->type      = ChatTypeEnum::COACH_JOBSEEKER;
            $chat->save();
            broadcast(new ChatCreated($request["client_id"]));
            broadcast(new ChatCreated($request["author_id"]));
        }
        $this->seenMessage($chat);
        broadcast(new MessageSent($chat));
        return $chat;
    }
}
