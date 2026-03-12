<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    protected $guarded = [];

    public function activities()
    {
        // A Project has many Activities. The 'activities' table must contain the foreign key 'project_id'.
        return $this->hasMany(Activity::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function executor()
    {
        return $this->belongsTo(OrgUnit::class, 'executor_id');
    }

    // ========== STATUS HELPER METHODS ==========

    /**
     * Check if project is in a terminal state
     */
    public function isTerminal(): bool
    {
        return in_array($this->status, ['approved', 'rejected']);
    }

    /**
     * Check if project is a draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Get human-readable status label in Arabic
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'مسودة',
            'submitted' => 'قيد المراجعة',
            'approved' => 'معتمد',
            'returned' => 'معاد',
            'rejected' => 'مرفوض',
            default => 'غير معروف',
        };
    }
}
