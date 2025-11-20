<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebsiteKontak;

class WebsiteKontakSeeder extends Seeder
{
    public function run()
    {
        WebsiteKontak::create([
            'name' => 'BMKG Signature',
            'image' => '', 
            'url' => 'https://bmkg.go.id',
            'order' => 1,
        ]);
        WebsiteKontak::create([
            'name' => 'SANTANU',
            'image' => '',
            'url' => 'https://santanu.example',
            'order' => 2,
        ]);
    }
}
