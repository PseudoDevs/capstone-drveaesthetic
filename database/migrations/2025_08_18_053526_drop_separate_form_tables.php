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
        Schema::dropIfExists('medical_information_forms');
        Schema::dropIfExists('consent_waiver_forms');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tables can be recreated from previous migrations if needed
    }
};
