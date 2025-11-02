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
        // Check if column doesn't exist before adding
        if (!Schema::hasColumn('prescriptions', 'prescribed_by')) {
            Schema::table('prescriptions', function (Blueprint $table) {
                $table->foreignId('prescribed_by')
                    ->after('client_id')
                    ->constrained('users')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('prescriptions', 'prescribed_by')) {
            Schema::table('prescriptions', function (Blueprint $table) {
                $table->dropForeign(['prescribed_by']);
                $table->dropColumn('prescribed_by');
            });
        }
    }
};

