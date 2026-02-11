<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
        
        Schema::create('swot_projects', function (Blueprint $table) {
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

        Schema::create('swot_boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swot_project_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['strength', 'weakness', 'opportunity', 'threat']);
            $table->string('content', 100);
            $table->string('participant_name', 20);
            $table->string('ip_address', 45);
            $table->string('session_id');
            $table->timestamps();

            $table->index(['swot_project_id', 'type']);
            $table->index('session_id');
        });

        Schema::create('swot_finalizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swot_project_id')->constrained()->cascadeOnDelete();
            $table->text('summary')->nullable();
            $table->text('strength_strategy')->nullable();
            $table->text('weakness_strategy')->nullable();
            $table->text('opportunity_strategy')->nullable();
            $table->text('threat_strategy')->nullable();
            $table->json('action_items')->nullable(); // [{title, owner, priority, deadline}]
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swot_boards');
        Schema::dropIfExists('swot_projects');
    }
};
