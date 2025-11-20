<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{

    protected $table = 'laporans';


    // Mass assignable fields
    protected $fillable = [
        'nama',
        'no_telepon',
        'kecamatan',
        'alamat',
        'link_postingan',
        'ketinggian_air',
    ];

    public $timestamps = true;
}
