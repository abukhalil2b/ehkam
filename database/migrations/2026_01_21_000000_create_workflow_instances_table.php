<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workflow_instances', function (Blueprint $table) {
            $table->id();

            // The definition being followed
            $table->foreignId('workflow_id')->constrained()->cascadeOnDelete();

            // The entity possessing this workflow (Activity, Project, etc.)
            $table->morphs('workflowable'); // Adds workflowable_type, workflowable_id

            // Current position
            $table->foreignId('current_stage_id')->nullable()->constrained('workflow_stages')->nullOnDelete();

            // Who started it
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();

            // State
            $table->enum('status', ['draft', 'in_progress', 'completed', 'returned', 'rejected', 'delayed'])
                ->default('draft');

            $table->timestamp('stage_due_at')->nullable();

            // 0=Normal, 1=Warning, 2=Escalated
            $table->tinyInteger('escalation_level')->default(0);

            $table->timestamps();

            // Ensure one active workflow per entity (optional but recommended)
            $table->unique(['workflowable_type', 'workflowable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_instances');
    }
};
