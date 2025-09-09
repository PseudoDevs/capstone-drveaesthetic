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
        Schema::table('appointments', function (Blueprint $table) {
            // Make staff_id nullable since it can be assigned later by admin
            $table->integer('staff_id')->nullable()->change();
            
            // Add default values for required boolean fields
            $table->boolean('is_rescheduled')->default(false)->change();
            $table->boolean('is_paid')->default(false)->change();
            
            // Update status enum to use lowercase values
            $table->enum('status', [
                'pending', 'scheduled', 'on-going', 'completed', 'cancelled', 'declined', 'rescheduled'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Revert changes
            $table->integer('staff_id')->nullable(false)->change();
            $table->boolean('is_rescheduled')->default(null)->change();
            $table->boolean('is_paid')->default(null)->change();
            $table->enum('status', ['PENDING', 'SCHEDULED', 'ON-GOING', 'CANCELLED', 'DECLINED', 'RESCHEDULED', 'COMPLETED'])->change();
        });
    }
};