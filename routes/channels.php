<?php

use App\Models\ChatRoom;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat.{id}', function ($user, $id) {
    return ChatRoom::where('id', $id)->where(function ($q) use ($user) {
        $q->where('author_id', $user->id)->orWhere("client_id", $user->id);
    })->exists();
});

Broadcast::channel('create.chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('sent.chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
