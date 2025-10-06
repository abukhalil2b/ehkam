<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Questionnaires (main form)
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Questions
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['single', 'multiple', 'range', 'text', 'date']);
            $table->string('question_text');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('min_value')->nullable(); // for range questions
            $table->unsignedTinyInteger('max_value')->nullable(); // for range questions
            $table->unsignedInteger('ordered')->default(0);
            $table->boolean('note_attachment')->default(false);
            $table->timestamps();
        });

        // Choices (for single and multiple questions)
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('choice_text')->nullable();
            $table->unsignedTinyInteger('ordered')->default(0);
            $table->timestamps();
        });

        // Answers (users’ responses)
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('questionnaire_id')->constrained('questionnaires')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('text_answer')->nullable();
            $table->unsignedTinyInteger('range_value')->nullable();
            $table->json('choice_ids')->nullable(); // supports both single and multiple
            $table->text('note')->nullable(); // extra field for note
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
        Schema::dropIfExists('choices');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('questionnaires');
    }
};
