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
        Schema::create('com_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('com_competitions')->onDelete('cascade');
            $table->text('question_text');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            
            $table->index(['competition_id', 'is_active']);
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_questions');
    }
};
