<?php

namespace App\Http\Controllers;

use App\Models\AppointmentRequest;
use App\Models\CalendarSlotProposal;
use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentRequestController extends Controller
{
    protected AppointmentService $service;

    public function __construct(AppointmentService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of appointment requests.
     */
    public function index(Request $request)
    {
        $query = AppointmentRequest::with(['requester', 'minister', 'workflowInstance.currentStage']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by requester if provided
        if ($request->has('requester_id') && $request->requester_id) {
            $query->where('requester_id', $request->requester_id);
        }

        // Show only current user's requests if not admin
        // Also show requests where user is the minister
        $user = Auth::user();
        if (!$request->has('all') && !$user->hasPermission('appointment_request.index_all')) {
            $query->where(function ($q) use ($user) {
                $q->where('requester_id', $user->id)
                  ->orWhere('minister_id', $user->id);
            });
        }

        $appointments = $query->latest()->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment request.
     */
    public function create()
    {
        // Get list of ministers (users who can receive appointment requests)
        $minister = User::find(11);

        return view('appointments.create', compact('minister'));
    }

    /**
     * Store a newly created appointment request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'minister_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'priority' => 'nullable|in:low,normal,high,urgent',
        ]);

        // Prevent users from requesting appointments with themselves
        if ($validated['minister_id'] == Auth::id()) {
            return back()
                ->withInput()
                ->with('error', 'لا يمكنك طلب موعد مع نفسك');
        }

        try {
            $appointmentRequest = DB::transaction(function () use ($validated) {
                return $this->service->createRequest($validated, Auth::user());
            });

            Log::info('Appointment request created', [
                'appointment_id' => $appointmentRequest->id,
                'requester_id' => Auth::id(),
                'minister_id' => $validated['minister_id'],
            ]);

            return redirect()
                ->route('appointments.show', $appointmentRequest)
                ->with('success', 'تم إنشاء طلب الموعد بنجاح وتم إرساله إلى سير العمل');
        } catch (\Exception $e) {
            Log::error('Failed to create appointment request', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء طلب الموعد. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Display the specified appointment request.
     */
    public function show(AppointmentRequest $appointmentRequest)
    {
        // Authorization: User must be requester, minister, or have permission
        $user = Auth::user();
        if ($appointmentRequest->requester_id !== $user->id 
            && $appointmentRequest->minister_id !== $user->id 
            && !$user->hasPermission('appointment_request.index_all')) {
            abort(403, 'غير مصرح لك بعرض هذا الطلب');
        }

        $appointmentRequest->load([
            'requester',
            'minister',
            'workflowInstance.workflow',
            'workflowInstance.currentStage.team',
            'transitions.actor',
            'transitions.toStage',
            'slotProposals'
        ]);

        // Get available slots for secretary selection
        $availableSlots = $appointmentRequest->slotProposals()
            ->available()
            ->orderBy('start_date')
            ->get();

        return view('appointments.show', compact('appointmentRequest', 'availableSlots'));
    }

    /**
     * Approve the appointment request (workflow approval).
     */
    public function approve(Request $request, AppointmentRequest $appointmentRequest)
    {
        $validated = $request->validate([
            'comments' => 'nullable|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($appointmentRequest, $validated) {
                $this->service->approveRequest(
                    $appointmentRequest,
                    Auth::user(),
                    $validated['comments'] ?? null
                );
            });

            Log::info('Appointment request approved', [
                'appointment_id' => $appointmentRequest->id,
                'user_id' => Auth::id(),
            ]);

            return back()->with('success', 'تم الموافقة على طلب الموعد بنجاح');
        } catch (\Exception $e) {
            Log::error('Failed to approve appointment request', [
                'appointment_id' => $appointmentRequest->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'حدث خطأ أثناء الموافقة. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Select a slot for the appointment (secretary action).
     */
    public function selectSlot(Request $request, AppointmentRequest $appointmentRequest)
    {
        $validated = $request->validate([
            'slot_id' => 'required|exists:calendar_slot_proposals,id',
        ]);

        try {
            DB::transaction(function () use ($validated, $appointmentRequest) {
                $slot = CalendarSlotProposal::findOrFail($validated['slot_id']);

                // Verify the slot belongs to this appointment request
                if ($slot->appointment_request_id !== $appointmentRequest->id) {
                    throw new \Exception('الموعد المحدد لا ينتمي إلى هذا الطلب');
                }

                $this->service->selectSlot($appointmentRequest, Auth::user(), $slot);
            });

            Log::info('Appointment slot selected', [
                'appointment_id' => $appointmentRequest->id,
                'slot_id' => $validated['slot_id'],
                'user_id' => Auth::id(),
            ]);

            return back()->with('success', 'تم اختيار الموعد بنجاح');
        } catch (\Exception $e) {
            Log::error('Failed to select appointment slot', [
                'appointment_id' => $appointmentRequest->id,
                'slot_id' => $validated['slot_id'] ?? null,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'حدث خطأ أثناء اختيار الموعد. يرجى المحاولة مرة أخرى.');
        }
    }
}
