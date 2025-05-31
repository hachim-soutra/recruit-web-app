<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'sender_id', 'receiver_id', 'title', 'url', 'body',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id')
            ->select('id', 'name', 'avatar');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id')
            ->select('id', 'name', 'avatar');
    }

}
