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
        Schema::table('spbe_sla_digital_services', function (Blueprint $table) {
            $table->string('status')->default('Active')->after('url');
        });

        // Migrate data
        DB::table('spbe_sla_digital_services')->where('is_active', false)->update(['status' => 'Inactive']);
        DB::table('spbe_sla_digital_services')->where('is_active', true)->update(['status' => 'Active']);

        Schema::table('spbe_sla_digital_services', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spbe_sla_digital_services', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('url');
        });

        // Migrate data
        DB::table('spbe_sla_digital_services')->where('status', 'Inactive')->update(['is_active' => false]);
        DB::table('spbe_sla_digital_services')->where('status', '!=', 'Inactive')->update(['is_active' => true]);

        Schema::table('spbe_sla_digital_services', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
