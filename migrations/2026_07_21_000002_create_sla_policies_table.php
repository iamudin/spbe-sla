<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spbe_sla_policies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('digital_service_id')->index();
            $table->string('version');
            $table->date('effective_date');
            $table->float('uptime_target')->default(99.9);
            $table->integer('response_time_target_minutes');
            $table->float('resolution_time_target_hours');
            $table->integer('rpo_minutes')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spbe_sla_policies');
    }
};
