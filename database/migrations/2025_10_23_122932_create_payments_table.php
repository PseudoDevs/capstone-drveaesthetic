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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('bill_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('received_by')->constrained('users')->onDelete('cascade');
            
            // Payment details
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'check', 'bank_transfer', 'card', 'other'])->default('cash');
            $table->string('payment_reference')->nullable(); // check number, transaction reference, etc.
            $table->text('notes')->nullable();
            
            // Payment status
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->date('payment_date');
            $table->datetime('processed_at')->nullable();
            
            // Additional information
            $table->string('bank_name')->nullable();
            $table->string('check_number')->nullable();
            $table->date('check_date')->nullable();
            $table->text('transaction_details')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};