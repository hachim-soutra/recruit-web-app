<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = ['name', 'email', 'phone_number', 'subject_name', 'sms_body', 'is_deleted', 'status', 'replymsg', 'created_by', 'replied_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function replydBy()
    {
        return $this->belongsTo(User::class, 'replied_by', 'id');

    }
}
