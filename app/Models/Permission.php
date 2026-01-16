<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Permission Model for RBAC Authorization
 * 
 * Permissions define what actions can be performed.
 * They are assigned to Roles, NOT directly to Users.
 */
class Permission extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
    ];

    /**
     * The roles that have this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
