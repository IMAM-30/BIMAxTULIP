<?php
// database/migrations/2025_11_06_create_otp_requests_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('otp_requests', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20);
            $table->string('otp_hash');
            $table->string('session_id')->nullable();
            $table->integer('attempts')->default(0);
            $table->integer('resend_count')->default(0);
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('otp_requests');
    }
}
