<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spbe_sla_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->integer('period_year');
            $table->integer('period_quarter');
            $table->float('compliance_rate');
            $table->text('findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->string('report_file')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spbe_sla_reviews');
    }
};
