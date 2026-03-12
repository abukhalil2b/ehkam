<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AppointmentRequest extends Model
{

    protected $guarded = [];

    public function slotProposals()
    {
        return $this->hasMany(CalendarSlotProposal::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function minister()
    {
        return $this->belongsTo(User::class, 'minister_id');
    }

    public function scopePendingForUser($query, User $user)
    {
        return $query->where('status', 'in_progress');
    }

    public function isApproved(): bool
    {
        return $this->status === 'completed';
    }
}
