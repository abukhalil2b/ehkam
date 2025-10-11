<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * This to create a form for project assessment
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->bigInteger('project_id');
            $table->timestamps();
        });

        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['range', 'text']);
            $table->tinyInteger('max_point')->nullable(); // (1-5) incase type = range
            $table->string('content')->nullable(); // e.g (how do you advice your relative for purchase from our store?) 
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
