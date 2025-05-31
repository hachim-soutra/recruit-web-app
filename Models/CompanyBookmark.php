<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBookmark extends Model
{
    use HasFactory;
    protected $table = 'company_bookmarks';

    protected $fillable = ['employer_id', 'candidate_id'];

    public function companyes()
    {
        return $this->belongsTo(User::class, 'employer_id', 'id');

    }
}
