<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegister extends Model
{

    use HasFactory;

    protected $table = 'event_registers';

    protected $fillable = ['candidate_id', 'events_id'];

    public function r_users()
    {
        return $this->belongsTo(User::class, 'candidate_id', 'id')->select('id', 'name', 'email', 'avatar');
    }
}
