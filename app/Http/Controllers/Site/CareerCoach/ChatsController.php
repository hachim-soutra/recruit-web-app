<?php

namespace App\Http\Controllers\Site\CareerCoach;

use App\Enum\Payments\ChatTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    public function index()
    {
        $rooms =  auth()->user()->chatRooms;
        return view('site.layout.chats-list', compact('rooms'));
    }

    public function indexApi(ChatService $chatService)
    {
        $rooms = auth()->user()->chatRooms();
        $rooms->each(function ($item) use ($chatService) {
            $chatService->seenMessage($item);
        });
        return response([
            "message" => "message sent",
            "rooms" => $rooms->get()
        ], 200);
    }

    public function store(Request $request, ChatService $chatService)
    {
        $chat = $chatService->sendMessage($request->all());
        return response(["message" => "message sent", "chat" => $chat], 200);
    }

    public function seen(Request $request, ChatService $chatService)
    {
        $chat = ChatRoom::findOrFail($request->id);
        $chatService->seenMessage($chat);
        return response(["message" => "message seen", "chat" => $chat], 200);
    }

    public function get_client_message(Request $request)
    {
        $chat = ChatRoom::where($request->only(["client_id", "author_id"]))->first();
        $avatar = $chat->receiver->avatar;
        return response([
            "avatar" => $avatar ? $avatar : env('APP_URL') . '/' . 'uploads/noimg.jpg',
            "message" => "messages",
            "data" => $chat
        ], 200);
    }

    public function startChat($id)
    {
        $room = ChatRoom::where(["client_id" => $id, "author_id" => auth()->user()->id])->first();
        if (!$room) {
            $chat = new ChatRoom();
            $chat->client_id = $id;
            $chat->author_id = auth()->user()->id;
            $chat->type = ChatTypeEnum::COACH_JOBSEEKER->value;
            $chat->messages  = json_encode([]);
            $chat->save();
        }
        return redirect()->route('job-seeker.chat');
    }
}
