<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = ['filename', 'mimetype', 'filepath', 'filesize'];

    protected $appends = ['file'];

    public function getFileAttribute()
    {
        if((array_key_exists('mimetype', $this->attributes)) && !empty($this->attributes['mimetype'])){
            return asset('uploads').'/'.$this->attributes['filepath'];
        }else{
            return null;
        }
    }

}
