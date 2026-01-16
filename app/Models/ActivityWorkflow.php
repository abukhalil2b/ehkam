<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityWorkflow extends Model
{
    protected $guarded = [];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedRole()
    {
        return $this->belongsTo(Role::class, 'assigned_role');
    }
}
