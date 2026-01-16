<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // ========== 1. WORKFLOW TEAMS ==========
        // Groups of users for assignments (e.g., Execution Team, Planning Team)
        Schema::create('workflow_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // ========== 2. USER-WORKFLOW-TEAM PIVOT ==========
        Schema::create('user_workflow_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workflow_team_id')->constrained('workflow_teams')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'workflow_team_id']);
        });

        // ========== 3. WORKFLOW DEFINITIONS ==========
        // The "map" - defines a specific process (e.g., "Standard Project Approval")
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ========== 4. WORKFLOW STAGES ==========
        // The ordered stops on the map (Team 1 → Team 2 → Team 3)
        Schema::create('workflow_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('workflow_teams')->cascadeOnDelete();

            // Stage order using gapped integers (10, 20, 30...)
            // Allows inserting new stages (e.g., order 15) without reindexing
            $table->integer('order');

            $table->string('name');
            $table->boolean('can_approve')->default(true);
            $table->boolean('can_return')->default(true);

            $table->enum('assignment_type', ['team', 'user', 'role'])->default('team');
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->unique(['workflow_id', 'order']);
        });

        // ========== 5. STEP TRANSITIONS ==========
        // Audit log recording every movement of a step through the workflow
        Schema::create('step_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('step_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_id')->constrained('users');
            $table->foreignId('from_stage_id')->nullable()->constrained('workflow_stages')->nullOnDelete();
            $table->foreignId('to_stage_id')->nullable()->constrained('workflow_stages')->nullOnDelete();
            $table->enum('action', ['submit', 'approve', 'return', 'reject']);
            $table->text('comments')->nullable();
            $table->timestamps();
        });


    }

    public function down()
    {
        Schema::dropIfExists('step_workflows');
        Schema::dropIfExists('step_transitions');
        Schema::dropIfExists('workflow_stages');
        Schema::dropIfExists('workflows');
        Schema::dropIfExists('user_workflow_team');
        Schema::dropIfExists('workflow_teams');
    }
};
