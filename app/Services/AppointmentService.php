<?php

namespace App\Services;

use App\Models\AppointmentRequest;
use App\Models\User;
use App\Models\CalendarSlotProposal;
use App\Services\WorkflowService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * AppointmentService - Business Logic for Appointment Requests
 * 
 * This service handles all business logic for the appointment request system,
 * including creating requests, managing approvals, and slot selection.
 * 
 * Key responsibilities:
 * - Creating new appointment requests with automatic workflow assignment
 * - Processing manager approvals (delegates to WorkflowService)
 * - Handling secretary slot selection with authorization checks
 * - Retrieving pending requests for users
 * 
 * Dependencies:
 * - WorkflowService: Manages workflow transitions and state
 * 
 * Usage example:
 * ```php
 * $service = app(AppointmentService::class);
 * 
 * // Create a new appointment request
 * $request = $service->createRequest([
 *     'minister_id' => 5,
 *     'subject' => 'Budget Discussion',
 *     'priority' => 'high'
 * ], $currentUser);
 * 
 * // Manager approves the request
 * $service->approveRequest($request, $manager, 'Approved for scheduling');
 * 
 * // Secretary selects a time slot
 * $slot = CalendarSlotProposal::find(1);
 * $service->selectSlot($request, $secretary, $slot);
 * ```
 * 
 * @see \App\Models\AppointmentRequest
 * @see \App\Services\WorkflowService
 * @see docs/appointments.md
 */
class AppointmentService
{
    /**
     * The workflow service instance.
     * 
     * @var WorkflowService
     */
    protected WorkflowService $workflow;

    /**
     * Create a new AppointmentService instance.
     * 
     * @param WorkflowService $workflow The workflow service for managing transitions
     */
    public function __construct(WorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    /**
     * Create a new appointment request and assign it to a workflow.
     * 
     * This method creates an appointment request and automatically assigns it
     * to the appropriate workflow based on the AppointmentRequest entity type.
     * The request is auto-submitted to start the approval process.
     * 
     * @param array $data The appointment request data:
     *                    - minister_id (int): The target minister's user ID
     *                    - subject (string): Subject of the appointment
     *                    - description (string|null): Optional description
     *                    - priority (string): 'low', 'normal', 'high', or 'urgent'
     * @param User $user The user creating the request (becomes requester)
     * 
     * @return AppointmentRequest The created appointment request with workflow assigned
     * 
     * @throws \Exception When no workflow is defined for AppointmentRequest model
     * @throws \Illuminate\Database\QueryException On database errors
     */
    public function createRequest(array $data, User $user): AppointmentRequest
    {
        return DB::transaction(function () use ($data, $user) {
            // Ensure requester_id is set
            $data['requester_id'] = $user->id;

            $request = AppointmentRequest::create($data);

            // Assign workflow (one workflow per model type)
            $workflow = \App\Models\Workflow::where('entity_type', AppointmentRequest::class)->first();
            
            if (!$workflow) {
                Log::error('Appointment workflow not found', [
                    'entity_type' => AppointmentRequest::class,
                    'user_id' => $user->id,
                ]);
                throw new \Exception('No workflow defined for AppointmentRequest. Please create a workflow first.');
            }

            $this->workflow->assignWorkflow($request, $workflow->id, $user, true); // auto submit

            return $request->fresh();
        });
    }

    /**
     * Approve an appointment request (manager approval step).
     * 
     * This method processes a manager's approval of an appointment request,
     * moving it to the next workflow stage (typically secretary scheduling).
     * 
     * Workflow behavior:
     * - Validates the manager has permission to act on current stage
     * - Records the approval transition with optional comments
     * - Moves request to the next workflow stage
     * - Fires StepApproved event for notifications
     * 
     * @param AppointmentRequest $request The appointment request to approve
     * @param User $manager The manager performing the approval
     * @param string|null $comments Optional approval comments
     * 
     * @return AppointmentRequest The updated appointment request
     * 
     * @throws \Exception When manager is not authorized to act on current stage
     * @throws \Exception When approval is not allowed at the current stage
     * 
     * @see \App\Services\WorkflowService::approveStep()
     */
    public function approveRequest(AppointmentRequest $request, User $manager, ?string $comments = null): AppointmentRequest
    {
        return DB::transaction(function () use ($request, $manager, $comments) {
            return $this->workflow->approveStep($request, $manager, $comments);
        });
    }

    /**
     * Select a calendar slot for an appointment request.
     * 
     * This method allows a secretary to select a proposed time slot for the appointment.
     * It includes authorization checks to ensure only the assigned secretary can act.
     * 
     * Authorization logic:
     * - For 'team' assignment: User must be a member of the assigned workflow team
     * - For 'user' assignment: User must be the specifically assigned user
     * 
     * After slot selection:
     * - The slot is marked as selected by the secretary
     * - The workflow advances to the next stage (or completes)
     * - A calendar event is typically created (handled by listeners)
     * 
     * @param AppointmentRequest $request The appointment request
     * @param User $secretary The secretary selecting the slot
     * @param CalendarSlotProposal $slot The proposed slot to select
     * 
     * @return AppointmentRequest The updated appointment request
     * 
     * @throws \Exception When workflow instance is missing or inactive
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException (403) When user is not authorized
     */
    public function selectSlot(AppointmentRequest $request, User $secretary, CalendarSlotProposal $slot): AppointmentRequest
    {
        return DB::transaction(function () use ($request, $secretary, $slot) {
            // Only allow secretary to act if workflow at correct stage
            $instance = $request->workflowInstance;
            
            if (!$instance || !$instance->currentStage) {
                Log::error('Cannot select slot: No workflow instance or current stage', [
                    'appointment_id' => $request->id,
                    'user_id' => $secretary->id,
                ]);
                throw new \Exception('لا يمكن اختيار الموعد: سير العمل غير نشط');
            }

            $currentStage = $instance->currentStage;

            // Check if user is in the team assigned to this stage
            $canAct = false;
            if ($currentStage->assignment_type === 'team' && $currentStage->team_id) {
                $canAct = $secretary->workflowTeams()->where('workflow_teams.id', $currentStage->team_id)->exists();
            } elseif ($currentStage->assignment_type === 'user') {
                $canAct = $currentStage->assigned_user_id === $secretary->id;
            }

            if (!$canAct) {
                Log::warning('Unauthorized slot selection attempt', [
                    'appointment_id' => $request->id,
                    'user_id' => $secretary->id,
                    'stage_id' => $currentStage->id,
                    'assignment_type' => $currentStage->assignment_type,
                ]);
                abort(403, 'You are not authorized to select a slot.');
            }

            $slot->update(['selected_by' => $secretary->id]);

            // Move workflow to next stage (approval completed)
            $this->workflow->approveStep($request, $secretary, "Slot confirmed: {$slot->start_date}");
            return $request->fresh();
        });
    }

    /**
     * Get all pending workflow items for a user.
     * 
     * Returns all items (including but not limited to appointment requests)
     * that are currently pending action from the specified user based on
     * their workflow team memberships.
     * 
     * Note: This method returns all pending workflow items, not just
     * appointment requests. Filter the results if you need only appointments.
     * 
     * @param User $user The user to get pending items for
     * 
     * @return \Illuminate\Support\Collection Collection of pending workflow items
     * 
     * @see \App\Services\WorkflowService::getPendingStepsForUser()
     */
    public function pendingForUser(User $user)
    {
        return $this->workflow->getPendingStepsForUser($user);
    }
}
