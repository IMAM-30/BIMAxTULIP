<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsApp extends Model
{
    protected $table = 'whatsapps'; // jika kamu pakai ini
    protected $fillable = ['phone', 'message', 'active', 'label', 'image'];

    protected $casts = [
        'active' => 'boolean',
    ];
}
