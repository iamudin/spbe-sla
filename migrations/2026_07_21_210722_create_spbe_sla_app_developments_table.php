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
        Schema::create('spbe_sla_app_developments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('digital_service_id')->index();
            $table->uuid('ticket_id')->nullable()->index();
            
            $table->text('specification')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->text('it_staff')->nullable(); // JSON or text listing staff
            $table->enum('status', ['Planning', 'Designing', 'Developing', 'Testing', 'Deployed'])->default('Planning');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Note: Since digital_services and tickets use uuid, these are basic indexes.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spbe_sla_app_developments');
    }
};
