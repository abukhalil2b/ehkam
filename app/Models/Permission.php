<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
   public $timestamps = false;

    protected $guarded = [];

    public function profiles()
    {
        return $this->belongsToMany(Profile::class, 'profile_permission', 'permission_id', 'profile_id');
    }


    // Permissions can be assigned directly to many Users
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permission');
    }
}
