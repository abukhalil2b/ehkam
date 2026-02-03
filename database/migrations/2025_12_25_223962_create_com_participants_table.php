<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('com_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('com_competitions')->onDelete('cascade');
            $table->string('name');
            $table->string('ip_address', 45); // IPv6 support
            $table->integer('score')->default(0);
            $table->timestamps();
            $table->foreignId('current_question_id')->nullable()->after('score')
                ->constrained('com_questions')->nullOnDelete();
            $table->timestamp('question_started_at')->nullable()->after('current_question_id');
            $table->boolean('auto_mode')->default(false)->after('question_started_at');
            $table->unique(['competition_id', 'ip_address']);
            $table->index('competition_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_participants');
    }
};
