<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactVerification extends Model
{
    use HasFactory;

    protected $table = 'contact_verifications';

    protected $fillable = [
        'email', 'mobile', 'code', 'resend', 'type',
    ];
}
