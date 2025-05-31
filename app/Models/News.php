<?php

namespace App\Models;

use App\Enum\NewsStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class News extends Model
{
    use HasFactory;
    protected $appends = ['enc_id'];
    protected $fillable = ['news_category_id', 'title', 'slug', 'image', 'newsdetail', 'status', 'created_by', 'updated_by', 'expire_date'];
    protected $casts = ['status'            => NewsStatusEnum::class];
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
    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id', 'id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
