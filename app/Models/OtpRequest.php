<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpRequest extends Model
{
    protected $table = 'otp_requests';

    protected $fillable = [
        'phone',
        'otp_hash',
        'session_id',
        'expires_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function isExpired()
    {
        // Jika expires_at null -> anggap expired untuk keselamatan
        if (empty($this->expires_at)) {
            return true;
        }

        try {
            return Carbon::now()->gt($this->expires_at);
        } catch (\Throwable $e) {
            try {
                $exp = Carbon::parse($this->expires_at);
                return Carbon::now()->gt($exp);
            } catch (\Throwable $e2) {
                return true;
            }
        }
    }
}
