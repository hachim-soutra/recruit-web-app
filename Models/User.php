<?php

namespace App\Models;

use App\Models\Candidate;
use App\Models\Coach;
use App\Models\Employer;
use App\Models\News;
use App\Models\Team;
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

    protected $appends = ['path', 'team_avatar'];
    protected $guarded = [];
    // protected $fillable = [
    //     'subscription_id', 'first_name', 'last_name', 'name', 'user_key', 'email', 
    //     'mobile', 'password', 'avatar', 'user_type', 'stripe_id', 'verify_token', 
    //     'email_verified', 'verified', 'is_complete', 'mobile_verified', 'status', 
    //     'rejecte_reason', 'fpm_token', 'access_token', 'provider_id', 'oauth_id', 'oauth_type'
    // ];

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
            if(str_contains($value, 'https://')){
                return $value;
            }else{
                if($this->user_type == 'team'){
                    return asset('/uploads/' . $value);
                }else{
                    return asset('/uploads/users/' . $value);
                }                
            }            
        } else {
            return $value;
        }
    }

    public function getTeamAvatarAttribute($value)
    {
        if ($value) {
            return asset('/uploads/' . $value);
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

    public function newscreator(){
        return $this->hasOne(News::class, 'created_by', 'id');
    }

    public function team(){
        return $this->hasOne(Team::class, 'user_id', 'id');
    }
}
