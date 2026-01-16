<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StepWorkflow extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function step()
    {
        return $this->belongsTo(Step::class);
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
