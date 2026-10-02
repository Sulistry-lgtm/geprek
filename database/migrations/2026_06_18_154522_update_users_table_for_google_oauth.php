<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('email');
            $table->string('status')->default('active')->after('role');
            $table->string('otp_code')->nullable()->after('status');
            $table->dateTime('otp_expires_at')->nullable()->after('otp_code');
            $table->integer('otp_attempts')->default(0)->after('otp_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'status', 'otp_code', 'otp_expires_at', 'otp_attempts']);
        });
    }
};
