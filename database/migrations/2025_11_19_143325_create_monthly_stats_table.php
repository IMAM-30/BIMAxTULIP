<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyStatsTable extends Migration
{
    public function up()
    {
        Schema::create('monthly_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->default((int)date('Y'));
            $table->unsignedTinyInteger('month'); // 1..12
            $table->unsignedInteger('value')->default(0); // jumlah laporan
            $table->string('label')->nullable(); // optional label
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['year','month']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('monthly_stats');
    }
}
