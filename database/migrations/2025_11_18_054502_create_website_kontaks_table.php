<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteKontaksTable extends Migration
{
    public function up()
    {
        Schema::create('website_kontaks', function (Blueprint $table) {
            $table->id();
            $table->string('name');              
            $table->string('image')->nullable();  
            $table->string('url')->nullable();    
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('website_kontaks');
    }
}
