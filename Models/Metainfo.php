<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metainfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_name', 'meta_tags', 'meta_title', 'meta_description'
    ];
}
