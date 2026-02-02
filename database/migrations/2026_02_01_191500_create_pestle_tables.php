<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Projects Table
        Schema::create('pestle_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('public_token')->unique();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_finalized')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->index('public_token');
            $table->index(['created_by', 'is_active']);
        });

        // 2. Boards Table (The items added by participants)
        Schema::create('pestle_boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pestle_project_id')->constrained()->onDelete('cascade');
            // PESTLE Dimensions
            $table->enum('type', ['political', 'economic', 'social', 'technological', 'legal', 'environmental']);
            $table->string('content', 100);
            $table->string('participant_name', 20);
            $table->string('ip_address', 45);
            $table->string('session_id');
            $table->timestamps();

            $table->index(['pestle_project_id', 'type']);
            $table->index('session_id');
        });

        // 3. Finalizes Table (Summary and Strategies)
        Schema::create('pestle_finalizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pestle_project_id')->constrained()->cascadeOnDelete();
            $table->text('summary')->nullable();

            // Strategies for each dimension (optional, or we use the detailed table below)
            // Keeping these for backward compatibility with SWOT structure if needed, 
            // but for PESTLE usually we just analyze them. Let's keep a generic strategy text for each if simple mode.
            $table->text('political_strategy')->nullable();
            $table->text('economic_strategy')->nullable();
            $table->text('social_strategy')->nullable();
            $table->text('technological_strategy')->nullable();
            $table->text('legal_strategy')->nullable();
            $table->text('environmental_strategy')->nullable();

            $table->json('action_items')->nullable(); // [{title, owner, priority, deadline}]
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // 4. Finalized Strategies (Detailed BSC-like or detailed analysis per dimension)
        Schema::create('pestle_finalized_strategies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pestle_project_id')->constrained()->cascadeOnDelete();
            // Using same enum types as board for consistency
            $table->enum('dimension_type', ['political', 'economic', 'social', 'technological', 'legal', 'environmental']);
            $table->text('strategic_goal')->nullable();
            $table->text('performance_indicator')->nullable();
            $table->json('initiatives')->nullable();
            $table->timestamps();

            $table->unique(['pestle_project_id', 'dimension_type'], 'pestle_strat_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pestle_finalized_strategies');
        Schema::dropIfExists('pestle_finalizes');
        Schema::dropIfExists('pestle_boards');
        Schema::dropIfExists('pestle_projects');
    }
};
