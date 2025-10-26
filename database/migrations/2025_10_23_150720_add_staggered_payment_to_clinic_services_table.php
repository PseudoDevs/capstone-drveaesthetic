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
            $table->boolean('allows_staggered_payment')->default(false)->after('max_concurrent_bookings');
            $table->integer('min_installments')->nullable()->after('allows_staggered_payment');
            $table->integer('max_installments')->nullable()->after('min_installments');
            $table->decimal('down_payment_percentage', 5, 2)->default(30.00)->after('max_installments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinic_services', function (Blueprint $table) {
            $table->dropColumn([
                'allows_staggered_payment',
                'min_installments',
                'max_installments',
                'down_payment_percentage',
            ]);
        });
    }
};