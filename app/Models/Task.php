<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contracts\HasWorkflow;
use App\Traits\Workflowable;

class Task extends Model implements HasWorkflow
{
    use SoftDeletes, Workflowable;

    protected $fillable = [
        'mission_id',
        'title',
        'description',
        'priority',
        'status',
        'is_private',
        'creator_id',
        'assigned_to',
        'due_date',
        'completed_at',
        'order',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد الانتظار',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتملة',
            default => 'غير معروف',
        };
    }

    public function getStatusStylesAttribute(): array
    {
        return match ($this->status) {
            'pending' => [
                'text' => 'text-yellow-700',
                'bg' => 'bg-yellow-100',
                'border' => 'border-yellow-300'
            ],
            'in_progress' => [
                'text' => 'text-blue-700',
                'bg' => 'bg-blue-100',
                'border' => 'border-blue-300'
            ],
            'completed' => [
                'text' => 'text-green-700',
                'bg' => 'bg-green-100',
                'border' => 'border-green-300'
            ],
            default => [
                'text' => 'text-gray-700',
                'bg' => 'bg-gray-100',
                'border' => 'border-gray-300'
            ],
        };
    }



    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_assignees')
            ->withPivot('status', 'notes', 'completed_at')
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function logs()
    {
        return $this->hasMany(TaskLog::class);
    }

    // Helper methods
    public function canBeViewedBy(User $user): bool
    {
        // المسؤول يرى كل شيء
        if ($this->mission->isLeader($user)) {
            return true;
        }

        // إذا كانت المهمة عامة
        if (!$this->is_private) {
            return $this->mission->isMember($user);
        }

        // إذا كانت خاصة، فقط المنشئ أو المخصص له
        return $this->creator_id === $user->id || $this->assigned_to === $user->id;
    }

    public function canBeEditedBy(User $user): bool
    {
        return $this->mission->isLeader($user) || $this->creator_id === $user->id;
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
