<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Alter digital_services table
        if (Schema::hasTable('spbe_sla_digital_services')) {
            Schema::table('spbe_sla_digital_services', function (Blueprint $table) {
                if (Schema::hasColumn('spbe_sla_digital_services', 'owner_unit')) {
                    $table->dropColumn('owner_unit');
                }
                $table->uuid('unit_kerja_id')->nullable()->after('description');
            });
        }

        // Alter users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'unit_kerja_id')) {
                    $table->uuid('unit_kerja_id')->nullable()->after('tenant_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('spbe_sla_digital_services')) {
            Schema::table('spbe_sla_digital_services', function (Blueprint $table) {
                if (Schema::hasColumn('spbe_sla_digital_services', 'unit_kerja_id')) {
                    $table->dropColumn('unit_kerja_id');
                }
                $table->string('owner_unit')->nullable();
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'unit_kerja_id')) {
                    $table->dropColumn('unit_kerja_id');
                }
            });
        }
    }
};
