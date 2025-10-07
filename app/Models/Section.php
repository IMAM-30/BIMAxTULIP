<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    // Sesuaikan dengan kolom di database
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
    ];
}
