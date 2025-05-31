<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'facebook_link',
        'google_link',
        'site_email',
        'contact_email',
        'currency',
        'new_join_one_month_free',
        'twitter_link',
        'instagram_link',
        'pinterest_link',
        'mobile_no',
        'alt_mobaile_no',
        'addres_one',
        'address_two',
        'about_us',
        'help',
        'privacy_policy',
        'term_of_use',
        'admin_pic',
        'logo',
        'banner_image',
        'payment_gateway',
        'secret_key',
        'published_key',
        'status',
        'copyright_content'
    ];
}
