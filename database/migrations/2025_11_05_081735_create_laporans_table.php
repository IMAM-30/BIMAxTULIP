<?php
// database/migrations/xxxx_xx_xx_create_laporans_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporansTable extends Migration
{
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 70)->nullable();
            $table->string('no_telepon', 15);
            $table->enum('kecamatan', ['Bacukiki', 'Bacukiki Barat', 'Ujung', 'Soreang']);
            $table->string('alamat', 100);
            $table->string('link_postingan', 150);
            $table->string('ketinggian_air', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporans'); // gunakan dropIfExists
    }
}
