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
            $table->dropColumn('form_type');
            $table->enum('form_type', ['medical_information', 'consent_waiver'])
                ->nullable()
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('form_type');
            $table->enum('form_type', ['medical_information', 'consultation_form', 'treatment_form', 'follow_up_form'])
                ->nullable()
                ->after('status');
        });
    }
};
