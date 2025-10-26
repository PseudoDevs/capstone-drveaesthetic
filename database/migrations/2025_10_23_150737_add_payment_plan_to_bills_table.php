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
        Schema::table('bills', function (Blueprint $table) {
            $table->enum('payment_type', ['full', 'staggered'])->default('full')->after('bill_type');
            $table->integer('total_installments')->nullable()->after('payment_type');
            $table->decimal('down_payment', 10, 2)->nullable()->after('total_installments');
            $table->decimal('installment_amount', 10, 2)->nullable()->after('down_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn([
                'payment_type',
                'total_installments',
                'down_payment',
                'installment_amount',
            ]);
        });
    }
};