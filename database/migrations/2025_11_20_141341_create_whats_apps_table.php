<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('whatsapps', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->comment('Nomor telepon lengkap, contoh: +62812...'); 
            $table->string('message')->nullable()->comment('Pesan default ketika membuka WA');
            $table->boolean('active')->default(false)->comment('Tandai nomor yg aktif untuk frontend');
            $table->string('label')->nullable()->comment('Label tampil (opsional) seperti "Hubungi kami"');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapps');
    }
};
