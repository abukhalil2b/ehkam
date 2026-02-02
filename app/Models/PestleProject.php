<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PestleProject extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'is_finalized' => 'boolean',
        'finalized_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($project) {
            $project->public_token = Str::random(32);
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(PestleBoard::class);
    }

    public function finalize()
    {
        return $this->hasOne(PestleFinalize::class);
    }

    public function finalizedStrategies()
    {
        return $this->hasMany(PestleFinalizedStrategy::class);
    }
}
