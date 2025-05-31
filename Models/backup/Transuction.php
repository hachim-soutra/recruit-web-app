<?php

namespace App\Models;

use App\Models\JobPost;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transuction extends Model
{
    use HasFactory;

    protected $table = 'transuctions';

    protected $appends = ['expiry_status', 'invoice_url'];

    protected $fillable = ['user_id', 'job_id', 'transaction_id', 'cart_key', 'amount', 'currency', 'total_amount', 'card_brand', 'card_last_four', 'tax_percentage', 'pay_by', 'status', 'expiry_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function job()
    {
        return $this->belongsTo(JobPost::class, 'job_id', 'id')->select('id', 'job_title');
    }

    public function getExpiryStatusAttribute()
    {
        if (array_key_exists('created_at', $this->attributes)) {
            $created = new Carbon($this->attributes['created_at']);
            $now     = Carbon::now();

            if ($created->diff($now)->days < 365) {
                return "Plan Active";
            }
            // $newDate = Carbon::createFromFormat('m/d/Y', $myDate2);

        } else {
            return "Plan Expired";
        }
    }

    public function getInvoiceUrlAttribute()
    {
        if (array_key_exists('id', $this->attributes)) {

            if (array_key_exists('id', $this->attributes)) {
                return env('APP_URL') . '/' . base64_encode($this->attributes['id']) . "/get-invoice";
            } else {
                return env('APP_URL');
            }

        } else {
            return env('APP_URL');
        }
    }

}
