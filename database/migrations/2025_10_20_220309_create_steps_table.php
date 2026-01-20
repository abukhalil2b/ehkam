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

            $table->foreignId('assigned_user_id')
                ->nullable()
                ->unsigned();

            $table->json('meta')->nullable();

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

            // Phase / Stage (approve and close) does it need an evidence
            $table->boolean('is_need_evidence_file')->default(false);

            $table->boolean('is_need_to_put_target')->default(false);

            // For ordering or grouping
            $table->unsignedInteger('ordered')->default(0);

        });

        Schema::create('step_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_transition_id');
            $table->foreignId('step_id');
            $table->text('notes');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('step_feedbacks');
        Schema::dropIfExists('steps');
    }
};
