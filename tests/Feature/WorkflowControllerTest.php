<?php

namespace Tests\Feature;

use App\Models\Step;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowStage;
use App\Models\WorkflowTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;
    protected WorkflowTeam $team;
    protected Workflow $workflow;
    protected WorkflowStage $stage;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user with permissions
        $this->admin = User::factory()->create();
        // Assume a permission system - for now we'll test basic auth

        // Create regular user
        $this->regularUser = User::factory()->create();

        // Create workflow team
        $this->team = WorkflowTeam::create(['name' => 'Test Team']);
        $this->team->users()->attach($this->regularUser->id);

        // Create workflow
        $this->workflow = Workflow::create([
            'name' => 'Test Workflow',
            'is_active' => true,
        ]);

        $this->stage = WorkflowStage::create([
            'workflow_id' => $this->workflow->id,
            'team_id' => $this->team->id,
            'order' => 10,
            'name' => 'Review Stage',
            'can_approve' => true,
            'can_return' => true,
        ]);
    }

    // ========== ADMIN WORKFLOW TEAM ROUTES ==========

    /** @test */
    public function guests_cannot_access_workflow_team_routes()
    {
        $this->get(route('admin.workflow.teams.index'))
            ->assertRedirect(route('login'));

        $this->get(route('admin.workflow.teams.create'))
            ->assertRedirect(route('login'));

        $this->post(route('admin.workflow.teams.store'), ['name' => 'Test'])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_users_can_view_workflow_teams_index()
    {
        $this->actingAs($this->admin)
            ->get(route('admin.workflow.teams.index'))
            ->assertOk();
    }

    /** @test */
    public function users_can_create_workflow_teams()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.workflow.teams.store'), [
                'name' => 'New Team',
                'description' => 'A new team for testing',
                'user_ids' => [$this->regularUser->id],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('workflow_teams', ['name' => 'New Team']);
    }

    /** @test */
    public function workflow_team_creation_requires_name()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.workflow.teams.store'), [
                'description' => 'No name',
            ])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function users_can_update_workflow_teams()
    {
        $this->actingAs($this->admin)
            ->put(route('admin.workflow.teams.update', $this->team), [
                'name' => 'Updated Team Name',
                'user_ids' => [],
            ])
            ->assertRedirect();

        $this->team->refresh();
        $this->assertEquals('Updated Team Name', $this->team->name);
    }

    // ========== ADMIN WORKFLOW DEFINITION ROUTES ==========

    /** @test */
    public function users_can_create_workflow_definitions()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.workflow.definitions.store'), [
                'name' => 'New Workflow',
                'description' => 'Test workflow',
                'is_active' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('workflows', ['name' => 'New Workflow']);
    }

    /** @test */
    public function users_can_add_stages_to_workflow()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.workflow.stages.store', $this->workflow), [
                'name' => 'New Stage',
                'team_id' => $this->team->id,
                'can_approve' => true,
                'can_return' => false,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('workflow_stages', [
            'workflow_id' => $this->workflow->id,
            'name' => 'New Stage',
            'can_approve' => true,
            'can_return' => false,
        ]);
    }

    /** @test */
    public function users_can_update_stages()
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.workflow.stages.update', $this->stage), [
                'name' => 'Updated Stage',
                'team_id' => $this->team->id,
                'can_approve' => false,
                'can_return' => true,
            ])
            ->assertRedirect();

        $this->stage->refresh();
        $this->assertEquals('Updated Stage', $this->stage->name);
        $this->assertFalse($this->stage->can_approve);
        $this->assertTrue($this->stage->can_return);
    }

    // ========== USER WORKFLOW ACTION ROUTES ==========

    /** @test */
    public function guests_cannot_access_workflow_action_routes()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage->id,
        ]);

        $this->get(route('workflow.pending'))
            ->assertRedirect(route('login'));

        $this->post(route('workflow.approve', $step))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function users_can_view_their_pending_steps()
    {
        Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage->id,
        ]);

        $this->actingAs($this->regularUser)
            ->get(route('workflow.pending'))
            ->assertOk()
            ->assertViewHas('steps');
    }

    /** @test */
    public function team_members_can_approve_steps()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage->id,
        ]);

        $this->actingAs($this->regularUser)
            ->post(route('workflow.approve', $step), [
                'comments' => 'Approved',
            ])
            ->assertRedirect();

        $step->refresh();
        // Should be completed since it's the only stage
        $this->assertEquals('completed', $step->status);
    }

    /** @test */
    public function non_team_members_cannot_approve_steps()
    {
        $outsider = User::factory()->create();

        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage->id,
        ]);

        $this->actingAs($outsider)
            ->post(route('workflow.approve', $step))
            ->assertForbidden();
    }

    /** @test */
    public function users_can_return_steps_with_comments()
    {
        // Create a second stage so we can return from it
        $stage2 = WorkflowStage::create([
            'workflow_id' => $this->workflow->id,
            'team_id' => $this->team->id,
            'order' => 20,
            'name' => 'Second Stage',
            'can_approve' => true,
            'can_return' => true,
        ]);

        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $stage2->id,
        ]);

        $this->actingAs($this->regularUser)
            ->post(route('workflow.return', $step), [
                'comments' => 'Needs revision',
            ])
            ->assertRedirect();

        $step->refresh();
        $this->assertEquals('returned', $step->status);
        $this->assertEquals($this->stage->id, $step->current_stage_id);
    }

    /** @test */
    public function users_can_reject_steps()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage->id,
        ]);

        $this->actingAs($this->regularUser)
            ->post(route('workflow.reject', $step), [
                'comments' => 'Not acceptable',
            ])
            ->assertRedirect();

        $step->refresh();
        $this->assertEquals('rejected', $step->status);
    }

    /** @test */
    public function users_can_view_step_history()
    {
        $step = Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage->id,
        ]);

        $this->actingAs($this->regularUser)
            ->get(route('workflow.history', $step))
            ->assertOk()
            ->assertViewHas('step');
    }

    // ========== EDGE CASES ==========

    /** @test */
    public function cannot_delete_team_used_by_active_stages()
    {
        $this->actingAs($this->admin)
            ->delete(route('admin.workflow.teams.destroy', $this->team))
            ->assertRedirect();

        // Team should still exist
        $this->assertDatabaseHas('workflow_teams', ['id' => $this->team->id]);
    }

    /** @test */
    public function cannot_delete_workflow_with_active_steps()
    {
        Step::factory()->create([
            'status' => 'in_progress',
            'workflow_id' => $this->workflow->id,
            'current_stage_id' => $this->stage->id,
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.workflow.definitions.destroy', $this->workflow))
            ->assertRedirect();

        // Workflow should still exist
        $this->assertDatabaseHas('workflows', ['id' => $this->workflow->id]);
    }

    /** @test */
    public function can_reindex_stages()
    {
        // Create stages with gaps
        WorkflowStage::create([
            'workflow_id' => $this->workflow->id,
            'team_id' => $this->team->id,
            'order' => 100,
            'name' => 'Gap Stage',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.workflow.stages.reindex', $this->workflow))
            ->assertRedirect();

        $stages = WorkflowStage::where('workflow_id', $this->workflow->id)
            ->orderBy('order')
            ->pluck('order')
            ->toArray();

        // Stages should be reindexed with consistent gaps
        $this->assertEquals([10, 20], $stages);
    }
}
