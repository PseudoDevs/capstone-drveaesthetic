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
        Schema::table('medical_certificates', function (Blueprint $table) {
            // Add new columns
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('certificate_number')->unique()->nullable();
            $table->string('certificate_type')->default('medical_clearance');
            $table->text('recommendations')->nullable();
            $table->text('restrictions')->nullable();
            $table->text('additional_notes')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('status')->default('active');
            
            // Rename existing column
            $table->renameColumn('staff_id', 'issued_by');
            
            // Remove old columns that are not needed
            $table->dropColumn(['amount', 'is_issued']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_certificates', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'appointment_id',
                'certificate_number',
                'certificate_type',
                'recommendations',
                'restrictions',
                'additional_notes',
                'valid_from',
                'valid_until',
                'status'
            ]);
            
            // Rename back
            $table->renameColumn('issued_by', 'staff_id');
            
            // Add back old columns
            $table->decimal('amount', 10, 2)->nullable();
            $table->boolean('is_issued')->default(false);
        });
    }
};