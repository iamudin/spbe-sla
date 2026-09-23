<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spbe_sla_action_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sla_review_id')->index();
            $table->string('finding_aspect');
            $table->text('action_taken');
            $table->integer('progress_percentage')->default(0);
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spbe_sla_action_plans');
    }
};
