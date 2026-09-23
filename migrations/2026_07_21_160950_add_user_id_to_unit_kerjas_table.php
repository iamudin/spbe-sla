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
        Schema::table('spbe_sla_unit_kerjas', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spbe_sla_unit_kerjas', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
