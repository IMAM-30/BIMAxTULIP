<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    public function run()
    {
        Section::create([
            'title' => 'Titik Rawan',
            'subtitle' => 'Di mana titik banjir sering terjadi?',
            'description' => 'Titik rawan banjir di Parepare biasanya berada di kawasan rendah dekat sungai...',
            'image' => 'images/titikrawan.png',
        ]);
        Section::create([
            'title' => 'Siaga',
            'subtitle' => 'Kapan kita harus siaga?',
            'description' => 'Warga diharap siaga saat curah hujan tinggi dan debit sungai meningkat...',
            'image' => 'images/siaga.png',
        ]);
    }
}

