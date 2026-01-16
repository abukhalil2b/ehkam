<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicatorWorkflow extends Model
{
    protected $guarded = [];

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedRole()
    {
        return $this->belongsTo(Role::class, 'assigned_role');
    }
}
