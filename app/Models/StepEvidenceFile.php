<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StepEvidenceFile extends Model
{
    protected $table = 'step_evidence_files';

    protected $fillable = [
        'step_id',
        'uploaded_by',
        'file_path',
        'file_name',
        'status',
        'reviewer_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // =========== RELATIONSHIPS ===========

    public function step(): BelongsTo
    {
        return $this->belongsTo(Step::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // =========== HELPERS ===========

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'قيد المراجعة',
            'approved' => 'مقبول',
            'returned' => 'معاد',
            default    => '—',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'yellow',
            'approved' => 'green',
            'returned' => 'red',
            default    => 'gray',
        };
    }
}
