<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * This to create a form for project assessment
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->bigInteger('project_id');
            $table->string('current_year', 4)->default('2025');
            $table->boolean('is_feed_indicator')->default(false);

            // Who created this step
            $table->foreignId('creator_id')
                ->nullable()
                ->unsigned();

            // Status flag for the item itself
            // Movement is driven by current_stage_id, NOT status
            // Only terminal statuses (completed, rejected) allow current_stage_id = NULL
            $table->enum('status', ['draft', 'in_progress', 'completed', 'returned', 'rejected', 'delayed'])
                ->default('draft');

            $table->timestamps();
        });

        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['range', 'text']);
            $table->tinyInteger('max_point')->nullable(); // (1-5) incase type = range
            $table->string('content')->nullable(); // e.g (how do you advice your relative for purchase from our store?) 
            $table->string('description')->nullable();
            $table->tinyInteger('ordered')->default(1);
            $table->string('assessment_year')->default('2025');
            $table->timestamps();
        });

        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->foreignId('assessment_question_id')->constrained('assessment_questions')->onDelete('cascade');

            // Dedicated numeric column for scores
            $table->tinyInteger('range_answer')->nullable();

            // Dedicated string column for text/comments
            $table->text('text_answer')->nullable();

            $table->text('note')->nullable();

            $table->foreignId('user_id')->constrained('users')->nullable()->onDelete('cascade');

            $table->string('assessment_year')->default('2025');

            $table->bigInteger('position_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_results');
        Schema::dropIfExists('assessment_questions');
        Schema::dropIfExists('activities');
    }
};
