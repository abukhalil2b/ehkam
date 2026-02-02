<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PestleFinalize extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'action_items' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(PestleProject::class, 'pestle_project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
