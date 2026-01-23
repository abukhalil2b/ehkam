<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SwotProject extends Model
{
    protected $fillable = [
        'title',
        'public_token',
        'is_active',
        'created_by',
        'is_finalized',
        'finalized_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_finalized' => 'boolean',
        'finalized_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->public_token)) {
                $project->public_token = Str::random(32);
            }
        });

        static::created(function ($project) {
            // Disable previous projects by same user
            static::where('created_by', $project->created_by)
                ->where('id', '!=', $project->id)
                ->update(['is_active' => false]);

            // Delete old sessions from previous projects
            $oldProjects = static::where('created_by', $project->created_by)
                ->where('id', '!=', $project->id)
                ->pluck('id');

            SwotBoard::whereIn('swot_project_id', $oldProjects)->delete();
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function boards()
    {
        return $this->hasMany(SwotBoard::class);
    }

    public function getPublicUrlAttribute(): string
    {
        return route('swot.public', $this->public_token);
    }

    public function finalize()
    {
        return $this->hasOne(SwotFinalize::class);
    }

    public function finalizedStrategies()
    {
        return $this->hasMany(SwotFinalizedStrategy::class);
    }
}
