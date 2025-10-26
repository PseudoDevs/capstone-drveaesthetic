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
        Schema::table('users', function (Blueprint $table) {
            // Personal Information
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('nationality')->nullable();
            $table->string('civil_status')->nullable();
            
            // Contact Information
            $table->string('secondary_phone')->nullable();
            $table->string('secondary_email')->nullable();
            $table->text('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            // Medical Information
            $table->text('medical_history')->nullable();
            $table->text('allergies')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('chronic_conditions')->nullable();
            $table->string('blood_type')->nullable();
            $table->decimal('height', 5, 2)->nullable(); // in cm
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            
            // Insurance Information
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->string('insurance_group_number')->nullable();
            
            // Preferences
            $table->text('preferred_language')->nullable();
            $table->text('communication_preferences')->nullable();
            $table->boolean('sms_notifications')->default(true);
            $table->boolean('email_notifications')->default(true);
            $table->boolean('marketing_communications')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'middle_name',
                'suffix',
                'gender',
                'nationality',
                'civil_status',
                'secondary_phone',
                'secondary_email',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
                'medical_history',
                'allergies',
                'current_medications',
                'chronic_conditions',
                'blood_type',
                'height',
                'weight',
                'occupation',
                'employer',
                'insurance_provider',
                'insurance_policy_number',
                'insurance_group_number',
                'preferred_language',
                'communication_preferences',
                'sms_notifications',
                'email_notifications',
                'marketing_communications',
            ]);
        });
    }
};