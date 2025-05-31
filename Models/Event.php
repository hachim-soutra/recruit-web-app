<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $appends = ['image', 'path'];
    protected $fillable = ['title', 'slug', 'image', 'details', 'event_date', 'time',
        'start_date', 'end_date', 'registration_link', 'created_by', 'updated_by',
    ];

    /*public function getImageAttribute($value)
    {
        if ($value) {
            return asset('/uploads/event/' . $value);
        } else {
            return $value;
        }
    }*/

    public function getImageAttribute(){
        if((array_key_exists('image', $this->attributes)) && !empty($this->attributes['image'])){
            return asset('uploads/event').'/'.$this->attributes['image'];
        }else{
            return asset('uploads/event/noimg.png');
        } 
    }

    public function getPathAttribute()
    {
        if (array_key_exists('image', $this->attributes)) {
            return $this->attributes['image'];
        } else {
            return "";
        }
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select('id', 'name', 'email', 'avatar');
    }

    public function register_user()
    {
        return $this->belongsTo(EventRegister::class, 'created_by', 'id')->select('id', 'name', 'email', 'avatar');
    }
}
