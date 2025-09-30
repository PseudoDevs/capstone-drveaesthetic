<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('email_notifications')->default(true);
            $table->boolean('appointment_confirmations')->default(true);
            $table->boolean('appointment_reminders_24h')->default(true);
            $table->boolean('appointment_reminders_2h')->default(true);
            $table->boolean('appointment_cancellations')->default(true);
            $table->boolean('feedback_requests')->default(true);
            $table->boolean('service_updates')->default(true);
            $table->boolean('promotional_offers')->default(false);
            $table->boolean('newsletter')->default(false);
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
