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
        Schema::create('consent_waiver_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->string('patient_name');
            $table->integer('age');
            $table->enum('civil_status', ['single', 'married']);
            $table->string('residence');
            
            // Agreement checkboxes
            $table->boolean('interviewed_advised_counselled')->default(false);
            $table->text('services_availed')->nullable();
            $table->boolean('hold_clinic_free_from_liabilities')->default(false);
            $table->boolean('read_understood_consent')->default(false);
            $table->boolean('acknowledge_right_to_record')->default(false);
            
            // Signature section
            $table->date('signature_date');
            $table->string('signature_location');
            $table->text('signature_data')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consent_waiver_forms');
    }
};
