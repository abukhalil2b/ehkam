<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationalUnit extends Model
{
    protected $fillable = ['name', 'type', 'parent_id'];

    // Self-referencing relationship for unit hierarchy
    public function parent()
    { 
        return $this->belongsTo(OrganizationalUnit::class, 'parent_id'); 
    }
    
    public function children()
    { 
        return $this->hasMany(OrganizationalUnit::class, 'parent_id'); 
    }

    public function positions()
    {
        // The second argument 'organizational_unit_position' specifies the pivot table name.
        return $this->belongsToMany(Position::class, 'organizational_unit_position');
    }
   
}