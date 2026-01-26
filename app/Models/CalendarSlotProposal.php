<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarSlotProposal extends Model
{
    protected $guarded = [];

    public function appointmentRequest()
    {
        return $this->belongsTo(AppointmentRequest::class);
    }

    public function selectedBySecretary()
    {
        return $this->belongsTo(User::class, 'selected_by');
    }

    public function scopeAvailable($query)
    {
        return $query->whereNull('selected_by');
    }
}
