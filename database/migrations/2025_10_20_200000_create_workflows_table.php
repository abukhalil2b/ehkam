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
            $table->string('entity_type')->default('App\Models\Activity')->unique();
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

            // Example content of workflow_stages.meta for "Stage 1"
            // {
            //     "validation": {
            //         "requires_attachment": true,
            //         "allowed_file_types": ["pdf", "jpg"],
            //         "max_size_kb": 5000
            //     }
            // }
            $table->json('meta')->nullable();

            // How many days allowed for this stage? (e.g., 3 days)
            // Nullable because not every stage (like "Archived") needs a timer.
            $table->integer('allowed_days')->nullable();

            $table->timestamps();

            $table->unique(['workflow_id', 'order']);
        });

        // Scenario: The deadline passed yesterday.

        // Check: Find all activity where due_at < now() AND escalation_level == 0.

        // Action: Send email to Supervisor.

        // Update: Set escalation_level = 1.

        // Scenario: The deadline passed 2 days ago (It happened "again").

        // Check: Find all activity where due_at < now()->subDays(1) AND escalation_level == 1.

        // Action: Trigger Escalation (e.g., Email Manager / Reassign Ticket).

        // Update: Set escalation_level = 2.

        // ========== 5. WORKFLOW TRANSITIONS (POLYMORPHIC) ==========
        // Audit log recording every movement through the workflow
        // Works with any model (Step, AimSectorFeedback, etc.)
        Schema::create('workflow_transitions', function (Blueprint $table) {
            $table->id();

            // Polymorphic relationship - supports any model
            $table->string('workflowable_type');
            $table->unsignedBigInteger('workflowable_id');
            $table->index(['workflowable_type', 'workflowable_id'], 'workflowable_index');

            $table->foreignId('actor_id')->constrained('users');
            //(if coming from Draft set from_stage_id = null )
            $table->foreignId('from_stage_id')->nullable()->constrained('workflow_stages')->nullOnDelete();
            $table->foreignId('to_stage_id')->nullable()->constrained('workflow_stages')->nullOnDelete();
            $table->enum('action', ['submit', 'approve', 'return', 'reject']);
            $table->text('comments')->nullable();
            $table->timestamps();
        });

        // ========== WORKFLOW TRANSITION ATTACHMENTS ==========
        // Stores the actual files uploaded during a transition
        Schema::create('workflow_requirements', function (Blueprint $table) {
            $table->id();

            // Link strictly to the specific transition event
            // This allows you to know exactly WHO uploaded it and WHEN
            $table->foreignId('workflow_transition_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('file_name'); // Original name (e.g., "my_invoice.pdf")
            $table->string('file_path'); // Storage path
            $table->string('mime_type');
            $table->unsignedInteger('size_kb');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('workflow_requirements');
        Schema::dropIfExists('workflow_transitions');
        Schema::dropIfExists('workflow_stages');
        Schema::dropIfExists('workflows');
        Schema::dropIfExists('user_workflow_team');
        Schema::dropIfExists('workflow_teams');
    }
};
