<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->string('no_telepon', 25)->change();
        });
    }

    public function down()
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->string('no_telepon', 13)->change(); // sesuaikan ke nilai semula jika perlu
        });
    }

};
