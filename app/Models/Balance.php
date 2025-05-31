<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;
    
    protected $table = 'balances';

    protected $fillable = ['user_id', 'current_month_balance'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
