<?php


namespace App\Models;


use App\Enum\Payments\SubscriptionStatusEnum;
use App\Models\Candidate;

use App\Models\Coach;

use App\Models\Employer;

use App\Models\News;

use App\Models\Team;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laratrust\Traits\LaratrustUserTrait;

use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;


class User extends Authenticatable

{

    use Billable;

    use LaratrustUserTrait;

    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'users';


    protected $appends = ['path', 'team_avatar', 'valid_subscription', 'full_name', 'waiting_subscription'];

    protected $guarded = [];

    // protected $fillable = [

    //     'first_name', 'last_name', 'name', 'user_key', 'email',

    //     'mobile', 'password', 'avatar', 'user_type', 'stripe_id', 'verify_token',

    //     'email_verified', 'verified', 'is_complete', 'mobile_verified', 'status',

    //     'rejecte_reason', 'fpm_token', 'access_token', 'provider_id', 'oauth_id', 'oauth_type'

    // ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
    ];

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

    public function skills()

    {

        return $this->belongsToMany(Skill::class);
    }


    public function qualifications()

    {

        return $this->belongsToMany(Qualification::class);
    }


    public function newscreator()
    {

        return $this->hasOne(News::class, 'created_by', 'id');
    }


    public function team()
    {
        return $this->hasOne(Team::class, 'user_id', 'id');
    }

    /**
     * Get the subscriptions for the user.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getAvatarAttribute($value)

    {

        if ($value) {

            if (str_contains($value, 'https://')) {

                return $value;
            } else {

                if ($this->user_type == 'team') {

                    return asset('/uploads/' . $value);
                } else {

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

    public function getValidSubscriptionAttribute(): ?Subscription
    {
        return $this->subscriptions()->where("status", SubscriptionStatusEnum::IN_USE->value)->first();
    }

    public function getWaitingSubscriptionAttribute(): ?Subscription
    {
        return $this->subscriptions()->where("status", SubscriptionStatusEnum::WAITING->value)
            ->orWhere("status", SubscriptionStatusEnum::PURCHASED->value)
            ->first();
    }


    /**
     * The tax rates that should apply to the customer's subscriptions.
     *
     * @return array<int, string>
     */
    public function taxRates(): array
    {
        return [config('app.stripe.txr')];
    }


    public function jobPosts(): HasMany
    {
        if ($this->user_type === "employer") {
            return $this->hasMany(JobPost::class, "employer_id");
        }
        return [];
    }

    public function getAvailableJobsNumberAttribute(): int
    {
        return $this->valid_subscription ? $this->valid_subscription->count_slot - $this->valid_subscription->jobPosts->count() : 0;
    }

    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'client_id')->orWhere('author_id', $this->id)->orderBy("updated_at", "desc");
    }

    public function getFullNameAttribute()
    {
        return "{$this['first_name']} {$this['last_name']}";
    }
}
