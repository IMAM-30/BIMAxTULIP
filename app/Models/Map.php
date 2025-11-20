<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    protected $table = 'maps';

    protected $fillable = [
        'nama_lokasi',
        'tanggal',
        'alamat',
        'ketinggian_air',
        'rumah_terdampak',
        'jumlah_korban',
        'luas_cakupan',
        'latitude',
        'longitude',
        'gambar',
    ];
}
