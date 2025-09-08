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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('service_id');
            $table->integer('staff_id');
            $table->date('appointment_date');
            $table->text('appointment_time');
            $table->boolean('is_rescheduled');
            $table->boolean('is_paid');
            $table->enum('status', ['PENDING', 'SCHEDULED', 'ON-GOING', 'CANCELLED', 'DECLINED', 'RESCHEDULE', 'COMPLETED']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
