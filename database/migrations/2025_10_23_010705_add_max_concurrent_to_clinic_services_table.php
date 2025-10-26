<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clinic_services', function (Blueprint $table) {
            $table->integer('max_concurrent_bookings')->default(2)->after('status');
            // Default: 2 appointments of same service at same time
            // Admin can adjust per service
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinic_services', function (Blueprint $table) {
            $table->dropColumn('max_concurrent_bookings');
        });
    }
};
