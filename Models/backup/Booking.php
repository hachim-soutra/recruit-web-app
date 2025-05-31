<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = ['user_id', 'tutor_id', 'day_id', 'slot_id', 'booking_status', 'is_aggreed_both', 'transaction_id', 'tutor_action', 'student_action'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id', 'id');
    }

    public function day()
    {
        return $this->belongsTo(TutorDate::class, 'day_id', 'id')->with('contact');
    }

    public function slot()
    {
        return $this->belongsTo(TutorDateSlot::class, 'slot_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

}
