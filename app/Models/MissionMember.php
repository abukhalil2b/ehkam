<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MissionMember extends Model
{
    protected $fillable = [
        'mission_id',
        'user_id',
        'role',
        'can_create_tasks',
        'can_view_all_tasks',
    ];

    protected $casts = [
        'can_create_tasks' => 'boolean',
        'can_view_all_tasks' => 'boolean',
    ];

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isLeader(): bool
    {
        return $this->role === 'leader';
    }
}