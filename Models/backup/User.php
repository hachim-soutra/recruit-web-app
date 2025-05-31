<?php

namespace App\Models;

use App\Models\Candidate;
use App\Models\Coach;
use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $appends = ['path'];

    protected $fillable = [
        'subscription_id', 'first_name', 'last_name', 'name', 'user_key', 'email', 'mobile', 'password', 'avatar', 'user_type', 'stripe_id', 'verify_token', 'email_verified', 'verified', 'is_complete', 'mobile_verified', 'status', 'rejecte_reason', 'fpm_token', 'access_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'mobile_verified_at' => 'datetime',
    ];

    public function getAvatarAttribute($value)
    {
        if ($value) {
            return asset('/uploads/users/' . $value);
        } else {
            return $value;
        }
    }

    public function getPathAttribute()
    {
        if (array_key_exists('avatar', $this->attributes)) {
            return $this->attributes['avatar'];
        } else {
            return "";
        }
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'user_id', 'id');
    }

    public function coach()
    {
        return $this->hasOne(Coach::class, 'user_id', 'id');
    }

    public function employer()
    {
        return $this->hasOne(Employer::class, 'user_id', 'id');
    }

    public function isProfileCompleted()
    {
        if ($this->user_type === 'tutor') {
            $data = Tutor::where('user_id', $this->id)
                ->count();
            if ($data > 0 && $this->avatar) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function qualifications()
    {
        return $this->belongsToMany(Qualification::class);
    }

}
