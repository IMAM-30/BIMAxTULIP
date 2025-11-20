<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('whats_apps', function (Blueprint $table) {
            $table->string('image')->nullable()->after('label')->comment('Path image logo WA (storage)'); 
        });
    }

    public function down()
    {
        Schema::table('whats_apps', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
