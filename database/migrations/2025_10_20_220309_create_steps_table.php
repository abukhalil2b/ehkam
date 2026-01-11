<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

            // Status of the step
            $table->enum('status', [
                'not_started',  // لم يبدأ
                'in_progress',  // في الإجراء
                'delayed',      // متأخر
                'completed',    // منجز
                'approved'      // معتمد
            ])->default('not_started');

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
