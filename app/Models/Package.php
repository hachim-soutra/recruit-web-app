<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Package extends Model

{

    use HasFactory;



    protected $table = 'packages';



    protected $fillable = [
        'title', 'price', 'vat', 'vatprice', 'plan_key', 'plan_interval', "number_of_month",

        'number_of_job_post', 'package_for', 'details', 'status'
    ];

    public function getPeriodAttribute()
    {
        return "xcc";
        return $this->number_of_month === 12 ? 'YEAR' : ($this->number_of_month === 6 ? '6 MONTHS' : 'MONTH');
    }
}
