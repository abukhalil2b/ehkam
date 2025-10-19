<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
     public $timestamps = false;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'profile_permission','profile_id','permission_id');
    }
}
