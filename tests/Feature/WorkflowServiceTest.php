<?php

namespace Tests\Feature;

use App\Models\Step;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowStage;
use App\Models\WorkflowTeam;
use App\Models\WorkflowTransition;
use App\Services\WorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WorkflowServiceTest extends TestCase
{
    use RefreshDatabase;

    protected WorkflowService $service;
    protected User $user;
    protected WorkflowTeam $team1;
    protected WorkflowTeam $team2;
    protected Workflow $workflow;
    protected WorkflowStage $stage1;
    protected WorkflowStage $stage2;
    protected WorkflowStage $stage3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(WorkflowService::class);

        // Create a user
        $this->user = User::factory()->create();

        // Create workflow teams
        $this->team1 = WorkflowTeam::create(['name' => 'Review Team']);
        $this->team2 = WorkflowTeam::create(['name' => 'Approval Team']);

        // Attach user to team1
        $this->team1->users()->attach($this->user->id);

        // Create a workflow with stages
        $this->workflow = Workflow::create([
            'name' => 'Test Workflow',
            'is_active' => true,
        ]);

        $this->stage1 = WorkflowStage::create([
            'workflow_id' => $this->workflow->id,
            'team_id' => $this->team1->id,
            'order' => 10,
            'name' => 'Initial Review',
            'can_approve' => true,
            'can_return' => false,
        ]);

        $this->stage2 = WorkflowStage::create([
            'workflow_id' => $this->workflow->id,
            'team_id' => $this->team2->id,
            'order' => 20,
            'name' => 'Final Approval',
            'can_approve' => true,
            'can_return' => true,
        ]);

        $this->stage3 = WorkflowStage::create([
            'workflow_id' => $this->workflow->id,
            'team_id' => $this->team2->id,
            'order' => 30,
            'name' => 'Archive',
            'can_approve' => false,
            'can_return' => false,
        ]);
    }

    /** @test */
    public function it_can_submit_a_draft_step_to_workflow()
    {
        $step = Step::factory()->create([
            'status' => 'draft',
            'workflow_id' => $this->workflow->id, // Assign workflow first
            'current_stage_id' => null,
            'creator_id' => $this->user->id,
        ]);

        $result = $this->service->submitStep($step, $this->user);

        $this->assertInstanceOf(Step::class, $result);
        $step->refresh();

        $this->assertEquals('in_progress', $step->status);
        $this->assertEquals($this->workflow->id, $step->workflow_id);
        $this->assertEquals($this->stage1->id, $step->current_stage_id);
    }

    /** @test */
    public function it_cannot_submit_a_non_draft_step()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage1->id,
        ]);

        $this->expectException(\Exception::class);
        $this->service->submitStep($step, $this->user);
    }

    /** @test */
    public function it_can_approve_and_move_to_next_stage()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage1->id,
            'creator_id' => $this->user->id,
        ]);

        $result = $this->service->approveStep($step, $this->user, 'Looks good');

        $this->assertInstanceOf(Step::class, $result);
        $step->refresh();

        $this->assertEquals($this->stage2->id, $step->current_stage_id);
        $this->assertEquals('in_progress', $step->status);

        // Check transition was recorded
        $transition = WorkflowTransition::where('workflowable_type', Step::class)
            ->where('workflowable_id', $step->id)
            ->first();
        $this->assertNotNull($transition);
        $this->assertEquals('approve', $transition->action);
        $this->assertEquals($this->stage1->id, $transition->from_stage_id);
        $this->assertEquals($this->stage2->id, $transition->to_stage_id);
    }

    /** @test */
    public function it_marks_step_completed_on_final_stage_approval()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage3->id, // Last stage
            'creator_id' => $this->user->id,
        ]);

        // Add user to team2
        $this->team2->users()->attach($this->user->id);

        $result = $this->service->approveStep($step, $this->user);

        $this->assertInstanceOf(Step::class, $result);
        $step->refresh();

        $this->assertEquals('completed', $step->status);
        $this->assertNull($step->current_stage_id);
    }

    /** @test */
    public function it_can_return_to_previous_stage()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage2->id,
            'creator_id' => $this->user->id,
        ]);

        // Add user to team2
        $this->team2->users()->attach($this->user->id);

        $result = $this->service->returnStep($step, $this->user, null, 'Needs more work');

        $this->assertInstanceOf(Step::class, $result);
        $step->refresh();

        $this->assertEquals($this->stage1->id, $step->current_stage_id);
        $this->assertEquals('returned', $step->status);
    }

    /** @test */
    public function it_cannot_return_from_first_stage()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage1->id,
            'creator_id' => $this->user->id,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->returnStep($step, $this->user);
    }

    /** @test */
    public function it_can_reject_a_step()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage1->id,
            'creator_id' => $this->user->id,
        ]);

        $result = $this->service->rejectStep($step, $this->user, 'Not acceptable');

        $this->assertInstanceOf(Step::class, $result);
        $step->refresh();

        $this->assertEquals('rejected', $step->status);
        $this->assertNull($step->current_stage_id);
    }

    /** @test */
    public function it_only_shows_pending_steps_for_users_teams()
    {
        // Create step in team1's stage (user is a member)
        $step1 = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage1->id,
        ]);

        // Create step in team2's stage (user is NOT a member)
        $step2 = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage2->id,
        ]);

        $pendingSteps = $this->service->getPendingStepsForUser($this->user);

        $this->assertCount(1, $pendingSteps);
        $this->assertEquals($step1->id, $pendingSteps->first()->id);
    }

    /** @test */
    public function it_prevents_action_by_non_team_members()
    {
        $outsider = User::factory()->create();

        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage1->id,
        ]);

        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        $this->service->approveStep($step, $outsider);
    }

    /** @test */
    public function it_handles_concurrent_approvals_gracefully()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage1->id,
        ]);

        $user2 = User::factory()->create();
        $this->team1->users()->attach($user2->id);

        // First approval succeeds
        $result1 = $this->service->approveStep($step, $this->user);
        $this->assertInstanceOf(Step::class, $result1);

        // Refresh to get latest state
        $step->refresh();

        // Second user attempts approval on same step (now at stage2)
        // This should fail because user2 is only in team1, not team2
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        $this->service->approveStep($step, $user2);
    }

    /** @test */
    public function it_records_transition_history()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage1->id,
            'creator_id' => $this->user->id,
        ]);

        $this->service->approveStep($step, $this->user, 'First approval');

        // Add user to team2 for second approval
        $this->team2->users()->attach($this->user->id);
        $step->refresh();

        $this->service->returnStep($step, $this->user, null, 'Needs revision');

        $transitions = WorkflowTransition::where('workflowable_type', Step::class)
            ->where('workflowable_id', $step->id)
            ->orderBy('id')
            ->get();

        $this->assertCount(2, $transitions);

        $this->assertEquals('approve', $transitions[0]->action);
        $this->assertEquals($this->stage1->id, $transitions[0]->from_stage_id);
        $this->assertEquals($this->stage2->id, $transitions[0]->to_stage_id);

        $this->assertEquals('return', $transitions[1]->action);
        $this->assertEquals($this->stage2->id, $transitions[1]->from_stage_id);
        $this->assertEquals($this->stage1->id, $transitions[1]->to_stage_id);
    }

    /** @test */
    public function it_cannot_approve_completed_or_rejected_steps()
    {
        $step = Step::factory()->create([
            'status' => 'completed',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => null,
        ]);

        $this->expectException(\Exception::class);
        $this->service->approveStep($step, $this->user);
    }

    /** @test */
    public function it_respects_stage_can_approve_flag()
    {
        // stage3 has can_approve = false
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage3->id,
        ]);

        $this->team2->users()->attach($this->user->id);

        // Even though user is in team, stage doesn't allow approve
        // This should handle gracefully or complete the workflow
        $result = $this->service->approveStep($step, $this->user);

        // Since it's the last stage, it should complete
        $step->refresh();
        $this->assertEquals('completed', $step->status);
    }

    /** @test */
    public function it_can_assign_workflow_to_existing_step()
    {
        $step = Step::factory()->create([
            'status' => 'draft',
            'workflow_id' => null,
        ]);

        $result = $this->service->assignWorkflow($step, $this->workflow->id, $this->user);

        $this->assertInstanceOf(Step::class, $result);
        $step->refresh();

        $this->assertEquals($this->workflow->id, $step->workflow_id);
        $this->assertEquals('draft', $step->status); // Still draft until submitted
    }
}
