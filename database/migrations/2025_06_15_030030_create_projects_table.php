<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Every Year create new projects and related Activities
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('cate', 20)->nullable();
            $table->text('description')->nullable();

            // new fields
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->decimal('spent_budget', 15, 2)->nullable();
            $table->string('budget_type', 100)->nullable();
            $table->decimal('completion_percentage', 5, 2)->nullable();
            $table->decimal('indicator_weight', 5, 2)->nullable();
            $table->text('qualitative_evaluation')->nullable();
            $table->text('evaluation_notes')->nullable();

            $table->bigInteger('executor_id')->nullable();

            $table->foreignId('indicator_id')
                ->constrained('indicators')
                ->onDelete('cascade');

            $table->foreignId('current_stage_id')
                ->nullable()
                ->constrained('workflow_stages')
                ->nullOnDelete();

            $table->enum('status', [
                'draft',
                'submitted',
                'approved',
                'returned'
            ])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
