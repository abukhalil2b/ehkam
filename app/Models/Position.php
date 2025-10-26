<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['title', 'reports_to_position_id'];

    public function subordinates()
    {
        return $this->hasMany(Position::class, 'reports_to_position_id');
    }

    public function organizationalUnits()
    {
        return $this->belongsToMany(OrganizationalUnit::class);
    }
}
