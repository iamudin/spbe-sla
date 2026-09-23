<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spbe_sla_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ticket_number')->unique();
            $table->uuid('digital_service_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('assigned_agent_id')->nullable()->index();
            
            $table->enum('category', ['Backend', 'UI/UX', 'Database', 'Network', 'Software', 'Other']);
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent/P1']);
            $table->enum('status', ['Open', 'Assigned', 'In Progress', 'Pending', 'Resolved', 'Closed'])->default('Open');
            
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->timestamp('response_due_at')->nullable();
            $table->timestamp('resolution_due_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spbe_sla_tickets');
    }
};
