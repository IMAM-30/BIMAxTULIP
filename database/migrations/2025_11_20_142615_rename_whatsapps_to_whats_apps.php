<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('whatsapps') && ! Schema::hasTable('whats_apps')) {
            Schema::rename('whatsapps', 'whats_apps');
        }
    }

    public function down()
    {
        if (Schema::hasTable('whats_apps') && ! Schema::hasTable('whatsapps')) {
            Schema::rename('whats_apps', 'whatsapps');
        }
    }
};
