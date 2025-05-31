<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachBookmark extends Model
{
    use HasFactory;

    protected $table = 'coach_bookmarks';

    protected $fillable = ['coach_id', 'candidate_id'];

    public function coaches()
    {
        return $this->belongsTo(User::class, 'coach_id', 'id');
    }

    public function coachdetail(){
        return $this->belongsTo(Coach::class, 'coach_id', 'id');
    }
}
