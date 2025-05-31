<?php

namespace App\Models;

use App\Enum\AdviceStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advice extends Model
{
    use HasFactory;

    protected $appends = ['enc_id'];

    protected $table = "advices";

    protected $fillable = [
        'title', 'slug', 'image', 'details', 'status', 'created_by', 'updated_by', 'expire_date'
    ];

    protected $casts = [
        'status'            => AdviceStatusEnum::class
    ];

    public function getEncIdAttribute()
    {
        return encrypt($this->id);
    }

    public function getImageAttribute()
    {
        if ((array_key_exists('image', $this->attributes)) && !empty($this->attributes['image'])) {
            return asset('uploads') . '/' . $this->attributes['image'];
        } else {
            return asset('uploads/event/noimg.png');
        }
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
