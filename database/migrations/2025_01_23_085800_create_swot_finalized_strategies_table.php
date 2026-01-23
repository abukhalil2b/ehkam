<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('swot_finalized_strategies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swot_project_id')->constrained()->cascadeOnDelete();
            $table->enum('dimension_type', ['financial', 'beneficiaries', 'internal_processes', 'learning_growth']);
            $table->text('strategic_goal')->nullable();
            $table->text('performance_indicator')->nullable();
            $table->json('initiatives')->nullable();
            $table->timestamps();

            $table->unique(['swot_project_id', 'dimension_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swot_finalized_strategies');
    }
};
