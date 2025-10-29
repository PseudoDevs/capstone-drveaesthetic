<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip if column already exists
        if (Schema::hasColumn('clinic_services', 'allows_staggered_payment')) {
            return;
        }

        // Determine where to place the first column
        $afterColumn = Schema::hasColumn('clinic_services', 'max_concurrent_bookings') 
            ? 'max_concurrent_bookings' 
            : 'status';
        
        // Use DB::statement for safer execution
        DB::statement("
            ALTER TABLE clinic_services 
            ADD COLUMN allows_staggered_payment BOOLEAN DEFAULT FALSE AFTER `{$afterColumn}`,
            ADD COLUMN min_installments INTEGER NULL AFTER allows_staggered_payment,
            ADD COLUMN max_installments INTEGER NULL AFTER min_installments,
            ADD COLUMN down_payment_percentage DECIMAL(5,2) DEFAULT 30.00 AFTER max_installments
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinic_services', function (Blueprint $table) {
            // Only drop columns if they exist
            if (Schema::hasColumn('clinic_services', 'down_payment_percentage')) {
                $table->dropColumn('down_payment_percentage');
            }
            if (Schema::hasColumn('clinic_services', 'max_installments')) {
                $table->dropColumn('max_installments');
            }
            if (Schema::hasColumn('clinic_services', 'min_installments')) {
                $table->dropColumn('min_installments');
            }
            if (Schema::hasColumn('clinic_services', 'allows_staggered_payment')) {
                $table->dropColumn('allows_staggered_payment');
            }
        });
    }
};
