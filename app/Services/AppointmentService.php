<?php

namespace App\Services;

use App\Models\AppointmentRequest;
use App\Models\User;
use App\Models\CalendarSlotProposal;
use Illuminate\Support\Facades\DB;


class AppointmentService
{

    public function __construct()
    {
    }

    public function createRequest(array $data, User $user): AppointmentRequest
    {
        return DB::transaction(function () use ($data, $user) {
            $data['requester_id'] = $user->id;
            $data['status'] = 'in_progress';

            $request = AppointmentRequest::create($data);

            return $request->fresh();
        });
    }

    public function approveRequest(AppointmentRequest $request, User $manager, ?string $comments = null): AppointmentRequest
    {
        return DB::transaction(function () use ($request, $manager, $comments) {
            $request->update(['status' => 'approved']);
            return $request->fresh();
        });
    }

    public function selectSlot(AppointmentRequest $request, User $secretary, CalendarSlotProposal $slot): AppointmentRequest
    {
        return DB::transaction(function () use ($request, $secretary, $slot) {
            $slot->update(['selected_by' => $secretary->id]);

            $request->update(['status' => 'completed']);
            return $request->fresh();
        });
    }

    public function pendingForUser(User $user)
    {
        return AppointmentRequest::pendingForUser($user)->get();
    }
}
