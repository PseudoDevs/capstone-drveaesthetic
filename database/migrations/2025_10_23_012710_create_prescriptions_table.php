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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('prescribed_by')->constrained('users')->onDelete('cascade'); // Staff/Doctor who prescribed
            $table->string('medication_name');
            $table->string('dosage');
            $table->string('frequency'); // e.g., "Twice daily", "Once a day"
            $table->string('duration'); // e.g., "7 days", "2 weeks"
            $table->text('instructions')->nullable(); // Special instructions
            $table->text('notes')->nullable(); // Doctor's notes
            $table->date('prescribed_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
