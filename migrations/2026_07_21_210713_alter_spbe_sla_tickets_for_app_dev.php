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
        Schema::table('spbe_sla_tickets', function (Blueprint $table) {
            $table->uuid('digital_service_id')->nullable()->change();
        });
        
        // Alter category enum to include Pembuatan Aplikasi
        DB::statement("ALTER TABLE spbe_sla_tickets MODIFY COLUMN category ENUM('Backend', 'UI/UX', 'Database', 'Network', 'Software', 'Other', 'Pembuatan Aplikasi') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting enum can be tricky if there's data, we'll just leave it or try
        DB::statement("ALTER TABLE spbe_sla_tickets MODIFY COLUMN category ENUM('Backend', 'UI/UX', 'Database', 'Network', 'Software', 'Other') NOT NULL");
        
        Schema::table('spbe_sla_tickets', function (Blueprint $table) {
            $table->uuid('digital_service_id')->nullable(false)->change();
        });
    }
};
