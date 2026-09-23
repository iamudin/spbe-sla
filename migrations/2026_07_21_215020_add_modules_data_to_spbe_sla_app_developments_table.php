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
        Schema::table('spbe_sla_app_developments', function (Blueprint $table) {
            // Using text/longText for wide compatibility, can store JSON array
            $table->longText('modules_data')->nullable()->after('specification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spbe_sla_app_developments', function (Blueprint $table) {
            $table->dropColumn('modules_data');
        });
    }
};
