<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontaksTable extends Migration
{
    public function up()
    {
        Schema::create('kontaks', function (Blueprint $table) {
            $table->id();
            $table->string('title');                 
            $table->text('description')->nullable(); 
            $table->string('url')->nullable();       
            $table->integer('order')->default(0);    
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kontaks');
    }
}
