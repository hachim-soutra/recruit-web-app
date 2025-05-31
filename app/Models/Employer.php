<?php



namespace App\Models;



use App\Enum\Payments\SubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;


class Employer extends Model

{

    use HasFactory;



    protected $table = 'employers';



    protected $appends = ['path'];

    protected $hidden = ['subscriptions'];



    protected $fillable = [
        'user_id', 'address', 'city', 'state', 'country', 'zip',

        'industry_id', 'company_ceo', 'number_of_employees', 'established_in', 'fax', 'facebook_address', 'twitter', 'ownership_type', 'phone_number', 'company_details', 'linkedin_link', 'website_link', 'company_logo', 'company_name', 'tag_line',

    ];



    public function user()

    {

        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getCompanyLogoAttribute($value)

    {
        if ($value == null) {
            return env('APP_URL') . "/uploads/no_company.jpg";
        }
        if (file_exists(public_path('/uploads/company_logo/' . $value))) {
            return asset('/uploads/company_logo/' . $value);
        }
        return env('APP_URL') . "/uploads/no_company.jpg";
    }



    public function getPathAttribute()

    {

        if (array_key_exists('company_logo', $this->attributes)) {

            return $this->attributes['company_logo'];
        } else {

            return "";
        }
    }
}
