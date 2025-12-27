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
        Schema::create('com_competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('status', ['closed', 'started', 'finished'])->default('closed');
            $table->string('join_code', 10)->unique();
            $table->unsignedBigInteger('current_question_id')->nullable();
            $table->timestamp('question_started_at')->nullable();
            $table->timestamps();
            
            $table->index('join_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_competitions');
    }
};
