<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // First add the column as nullable to allow population of existing records
            $table->unsignedBigInteger('client_id')->nullable()->after('bill_id');
        });

        // Populate client_id from the related bill for existing payments
        DB::statement('
            UPDATE payments p
            INNER JOIN bills b ON p.bill_id = b.id
            SET p.client_id = b.client_id
            WHERE p.client_id IS NULL
        ');

        // Now add the foreign key constraint
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Make the column not nullable after populating data
        DB::statement('ALTER TABLE payments MODIFY client_id BIGINT UNSIGNED NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
};
