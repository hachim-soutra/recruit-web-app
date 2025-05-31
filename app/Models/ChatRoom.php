<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        // 'messages' => 'array',
    ];

    protected $with = ["author", "client"];

    protected $appends = ["last_message", "receiver"];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function getLastMessageAttribute()
    {
        return count(json_decode($this->messages, true)) > 0 ? json_decode($this->messages, true)[count(json_decode($this->messages, true)) - 1] : "";
    }

    public function getUserLastMessageAttribute()
    {
        return User::find($this->last_message["user_id"]);
    }

    public function handelUser($id)
    {
        return User::find($id == $this->author_id ? $this->client_id : $this->author_id);
    }


    public function getReceiverAttribute()
    {
        return auth()->user() ? auth()->user()->id === $this->author_id  ?  $this->client : $this->author : null;
    }
}
