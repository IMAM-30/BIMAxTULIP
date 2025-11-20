<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'title','slug','excerpt','body','image','published_at','order'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];


    // set slug automatically when setting title (optional)
    public static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->slug) && !empty($model->title)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }
}
