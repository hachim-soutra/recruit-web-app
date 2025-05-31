<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    use HasFactory;

    protected $table = 'user_education';

    protected $fillable = ['qualification_id', 'candidate_id'];

    public function qualification()
    {
        return $this->belongsTo(Qualification::class, 'qualification_id', 'id');

    }
}
