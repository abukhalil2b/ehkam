<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();

            // The project this step belongs to
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('activity_id')
                ->constrained()
                ->cascadeOnDelete();

            // ========== WORKFLOW ENGINE COLUMNS ==========
            // Which workflow design is this step following?
            // Must be nullable because existing steps didn't have a workflow
            $table->foreignId('workflow_id')
                ->nullable()
                ->constrained('workflows')
                ->nullOnDelete();

            // WHERE is it right now? (Points to workflow_stages table)
            $table->foreignId('current_stage_id')
                ->nullable()
                ->constrained('workflow_stages')
                ->nullOnDelete();

            // Who created this step
            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Status flag for the item itself
            // Movement is driven by current_stage_id, NOT status
            // Only terminal statuses (completed, rejected) allow current_stage_id = NULL
            $table->enum('status', ['draft', 'in_progress', 'completed', 'returned', 'rejected'])
                ->default('draft');

            // Optional assignment + metadata
            $table->foreignId('assigned_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->tinyInteger('priority')->default(3);

            $table->date('due_date')->nullable();

            $table->json('meta')->nullable();
            // ========== END WORKFLOW ENGINE COLUMNS ==========

            // Basic info
            $table->string('name'); // اسم الخطوة
            $table->date('start_date')->nullable(); // من
            $table->date('end_date')->nullable();   // إلى
            $table->decimal('target_percentage', 5, 2)->default(0); // المستهدف %

            // Phase / Stage (e.g.  التخطيط والتطوير، التنفيذ، المراجعة، الاعتماد والإغلاق)
            $table->enum('phase', [
                'planning',
                'implementation',
                'review',
                'close'
            ])->comment('مرحلة العمل');


            // Supporting documents / notes
            $table->text('supporting_document')->nullable();

            $table->boolean('is_need_evidence_file')->default(false);

            $table->boolean('is_need_to_put_target')->default(false);

            // For ordering or grouping
            $table->unsignedInteger('ordered')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
