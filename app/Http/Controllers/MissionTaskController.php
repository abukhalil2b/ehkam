<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\MissionMember;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MissionTaskController extends Controller
{
    public function missionIndex()
    {
        $missions = Mission::with([
            'leader',
            'members.user'
        ])->withCount('tasks')->latest()->get();

        $users = User::select('id', 'name')->get();

        return view('missions.index', compact('missions', 'users'));
    }

    public function missionStore(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'leader_id' => 'required|exists:users,id',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
            'permissions' => 'nullable|array',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($data) {

            $mission = Mission::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'leader_id' => $data['leader_id'],
                'creator_id' => auth()->id(),
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
            ]);

            // القائد
            MissionMember::create([
                'mission_id' => $mission->id,
                'user_id' => $data['leader_id'],
                'role' => 'leader',
                'can_create_tasks' => true,
                'can_view_all_tasks' => true,
            ]);

            // الأعضاء
            if (!empty($data['member_ids'])) {
                foreach ($data['member_ids'] as $userId) {
                    if ($userId == $data['leader_id'])
                        continue;

                    $perm = $data['permissions'][$userId] ?? [];

                    MissionMember::create([
                        'mission_id' => $mission->id,
                        'user_id' => $userId,
                        'role' => 'member',
                        'can_create_tasks' => !empty($perm['can_create_tasks']) ? 1 : 0,
                        'can_view_all_tasks' => !empty($perm['can_view_all_tasks']) ? 1 : 0,
                    ]);
                }
            }
        });

        return back()->with('success', 'تم إنشاء المهمة بنجاح');
    }

    public function missionUpdate(Request $request)
    {
        // التحقق من وجود ID المهمة أولاً
        $data = $request->validate([
            'mission_id' => 'required|exists:missions,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'leader_id' => 'required|exists:users,id',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
            'permissions' => 'nullable|array',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($data) {
            $mission = Mission::findOrFail($data['mission_id']);

            // 1. تحديث بيانات المهمة الأساسية
            $mission->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'leader_id' => $data['leader_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);

            // 2. تحديث الأعضاء (الحذف ثم الإضافة لضمان نظافة البيانات)
            $mission->members()->delete();

            // إضافة القائد بصلاحيات كاملة
            MissionMember::create([
                'mission_id' => $mission->id,
                'user_id' => $data['leader_id'],
                'role' => 'leader',
                'can_create_tasks' => true,
                'can_view_all_tasks' => true,
            ]);

            // إضافة الأعضاء المختارين (إذا وجدوا)
            if (!empty($data['member_ids'])) {
                foreach ($data['member_ids'] as $userId) {
                    // تخطي المستخدم إذا كان هو نفسه القائد (لمنع التكرار)
                    if ($userId == $data['leader_id'])
                        continue;

                    $perm = $data['permissions'][$userId] ?? [];

                    MissionMember::create([
                        'mission_id' => $mission->id,
                        'user_id' => $userId,
                        'role' => 'member',
                        // التأكد من تحويل القيم القادمة من Alpine إلى Boolean
                        'can_create_tasks' => filter_var($perm['can_create_tasks'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'can_view_all_tasks' => filter_var($perm['can_view_all_tasks'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    ]);
                }
            }
        });

        return back()->with('success', 'تم تحديث المهمة بنجاح');
    }

    /**
     * عرض لوحة كانبان للمهمة
     */
    public function show(Mission $mission)
    {
        Gate::authorize('view', $mission);
        $user = auth()->user();

        $query = $mission->tasks()->with(['creator', 'assignedUser']);

        if (!$mission->isLeader($user)) {
            $query->where(function ($q) use ($user) {
                $q->where('is_private', false)
                    ->orWhere('creator_id', $user->id)
                    ->orWhere('assigned_to', $user->id);
            });
        }

        $tasksForJs = $query->get()->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'priority' => $task->priority,
                'status' => $task->status,
                'is_private' => (bool) $task->is_private,
                'assigned_to' => $task->assigned_to,
                'due_date' => $task->due_date?->format('Y-m-d'),
                'creator_id' => $task->creator_id,
            ];
        });

        $members = $mission->users()
            ->select('users.id', 'users.name')
            ->get()
            ->map(fn($m) => ['id' => $m->id, 'name' => $m->name])
            ->toArray();

        $colors = ['#3498db', '#2ecc71', '#f39c12', '#9b59b6', '#e74c3c', '#1abc9c', '#34495e', '#d35400'];

        return view('missions.task.show', [
            'members' => $members,
            'mission' => $mission,
            'tasks' => $tasksForJs,
            'colors' => $colors,
        ]);
    }

    /**
     * إنشاء مهمة جديدة
     */
    public function store(Request $request, Mission $mission)
    {
        Gate::authorize('create', [Task::class, $mission]);

        $user = auth()->user();
        $isLeader = $mission->isLeader($user);

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:high,medium,low',
            'due_date' => 'nullable|date|after_or_equal:today',
        ];

        // المسؤول فقط يمكنه اختيار المخصص له والخصوصية
        if ($isLeader) {
            $rules['assigned_to'] = 'required|exists:users,id';
            $rules['is_private'] = 'boolean';

            // التحقق من أن المخصص له عضو في المشروع
            $request->validate($rules);
            $validated = $request->only(['title', 'description', 'priority', 'assigned_to', 'is_private', 'due_date']);

            $targetMember = $mission->members()
                ->where('user_id', $validated['assigned_to'])
                ->first();

            if (!$targetMember) {
                return response()->json([
                    'message' => 'المستخدم المحدد ليس عضواً في المشروع'
                ], 422);
            }
        } else {
            $request->validate($rules);
            $validated = $request->only(['title', 'description', 'priority', 'due_date']);
            $validated['assigned_to'] = $user->id;
            $validated['is_private'] = true;
        }

        $task = $mission->tasks()->create([
            ...$validated,
            'creator_id' => $user->id,
            'status' => 'pending',
        ]);

        // تسجيل الحدث
        $task->logs()->create([
            'user_id' => $user->id,
            'action' => 'created',
            'new_values' => $task->toArray(),
        ]);

        // Send Notification if assigned to someone else
        if ($task->assigned_to && $task->assigned_to !== $user->id) {
            $assignee = User::find($task->assigned_to);
            if ($assignee) {
                $assignee->notify(new \App\Notifications\MissionTaskAssigned($task));
            }
        }

        return response()->json([
            'message' => 'تم إنشاء المهمة بنجاح',
            'task' => $task->load(['creator', 'assignedUser'])
        ], 201);
    }

    /**
     * تحديث المهمة
     */
    public function update(Request $request, Mission $mission, Task $task)
    {
        Gate::authorize('update', $task);

        $user = auth()->user();
        $isLeader = $mission->isLeader($user);

        $rules = [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'sometimes|in:high,medium,low',
            'due_date' => 'nullable|date',
        ];

        if ($isLeader) {
            $rules['assigned_to'] = 'sometimes|exists:users,id';
            $rules['is_private'] = 'sometimes|boolean';
            $rules['status'] = 'sometimes|in:pending,in_progress,completed,cancelled';
        }

        $validated = $request->validate($rules);

        // إذا تم تغيير assigned_to، تحقق من العضوية
        if (isset($validated['assigned_to'])) {
            $targetMember = $mission->members()
                ->where('user_id', $validated['assigned_to'])
                ->first();

            if (!$targetMember) {
                return response()->json([
                    'message' => 'المستخدم المحدد ليس عضواً في المشروع'
                ], 422);
            }
        }

        $oldValues = $task->toArray();

        // إذا تم تحديث الحالة إلى مكتمل
        if (isset($validated['status']) && $validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif (isset($validated['status']) && $validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        // تسجيل التغيير
        $task->logs()->create([
            'user_id' => $user->id,
            'action' => 'updated',
            'old_values' => $oldValues,
            'new_values' => $task->fresh()->toArray(),
        ]);

        return response()->json([
            'message' => 'تم تحديث المهمة بنجاح',
            'task' => $task->fresh()->load(['creator', 'assignedUser'])
        ]);
    }

    /**
     * تغيير ترتيب المهمة (Drag & Drop)
     */
    public function reorder(Request $request, Mission $mission, Task $task)
    {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'order' => 'required|integer|min:0',
        ]);

        // التحقق من أن المستخدم المخصص له عضو في المشروع
        $targetMember = $mission->members()
            ->where('user_id', $validated['assigned_to'])
            ->first();

        if (!$targetMember) {
            return response()->json([
                'message' => 'المستخدم ليس عضواً في المشروع'
            ], 422);
        }

        $oldAssignedTo = $task->assigned_to;
        $task->update($validated);

        // تسجيل النقل
        if ($oldAssignedTo !== $validated['assigned_to']) {
            $task->logs()->create([
                'user_id' => auth()->id(),
                'action' => 'reassigned',
                'old_values' => ['assigned_to' => $oldAssignedTo],
                'new_values' => ['assigned_to' => $validated['assigned_to']],
            ]);
        }

        return response()->json([
            'message' => 'تم نقل المهمة بنجاح',
            'task' => $task->fresh()
        ]);
    }

    /**
     * تغيير حالة المهمة
     */
    public function updateStatus(Request $request, Mission $mission, Task $task)
    {
        Gate::authorize('updateStatus', $task);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $oldStatus = $task->status;

        if ($validated['status'] === 'completed') {
            $task->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        } else {
            $task->update([
                'status' => $validated['status'],
                'completed_at' => null,
            ]);
        }

        // تسجيل التغيير
        $task->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'status_changed',
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $validated['status']],
        ]);

        return response()->json([
            'message' => 'تم تحديث حالة المهمة بنجاح',
            'task' => $task->fresh()
        ]);
    }

    /**
     * حذف المهمة
     */
    public function destroy(Mission $mission, Task $task)
    {
        Gate::authorize('delete', $task);

        // تسجيل الحذف قبل الحذف
        $task->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'old_values' => $task->toArray(),
        ]);

        $task->delete();

        return response()->json([
            'message' => 'تم حذف المهمة بنجاح'
        ]);
    }
}
