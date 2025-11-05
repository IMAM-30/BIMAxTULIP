<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'question', 'answer', 'image1', 'image2'];

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }
}
