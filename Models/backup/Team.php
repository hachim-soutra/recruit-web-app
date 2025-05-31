<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'designation', 'fblink', 'twlink', 'status', 'created_by', 'updated_by'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
