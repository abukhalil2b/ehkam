<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('com_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained('com_participants')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('com_questions')->onDelete('cascade');
            $table->foreignId('option_id')->constrained('com_options')->onDelete('cascade');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
            
            $table->unique(['participant_id', 'question_id']);
            $table->index(['question_id', 'is_correct']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_answers');
    }
};
