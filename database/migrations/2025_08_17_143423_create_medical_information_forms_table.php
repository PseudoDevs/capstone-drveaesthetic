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
        Schema::create('medical_information_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->string('patient_name');
            $table->date('date')->default(now());
            $table->text('address');
            $table->string('procedure');
            
            // Past Medical History checkboxes
            $table->boolean('allergy')->default(false);
            $table->boolean('diabetes')->default(false);
            $table->boolean('thyroid_diseases')->default(false);
            $table->boolean('stroke')->default(false);
            $table->boolean('heart_diseases')->default(false);
            $table->boolean('kidney_diseases')->default(false);
            $table->boolean('hypertension')->default(false);
            $table->boolean('asthma')->default(false);
            $table->text('medical_history_others')->nullable();
            
            // Additional medical status
            $table->boolean('pregnant')->default(false);
            $table->boolean('lactating')->default(false);
            $table->boolean('smoker')->default(false);
            $table->boolean('alcoholic_drinker')->default(false);
            
            // Maintenance Medications
            $table->text('maintenance_medications')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_information_forms');
    }
};
