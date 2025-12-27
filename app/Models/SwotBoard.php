<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SwotBoard extends Model
{
    protected $fillable = [
        'swot_project_id',
        'type',
        'content',
        'participant_name',
        'ip_address',
        'session_id',
    ];

    public function project()
    {
        return $this->belongsTo(SwotProject::class, 'swot_project_id');
    }
}