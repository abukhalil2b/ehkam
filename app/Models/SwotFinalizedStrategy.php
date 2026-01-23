<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SwotFinalizedStrategy extends Model
{
    protected $fillable = [
        'swot_project_id',
        'dimension_type',
        'strategic_goal',
        'performance_indicator',
        'initiatives',
    ];

    protected $casts = [
        'initiatives' => 'array',
    ];

    // Dimension type constants
    const TYPE_FINANCIAL = 'financial';
    const TYPE_BENEFICIARIES = 'beneficiaries';
    const TYPE_INTERNAL_PROCESSES = 'internal_processes';
    const TYPE_LEARNING_GROWTH = 'learning_growth';

    // Arabic labels for dimension types
    public static function getDimensionLabels(): array
    {
        return [
            self::TYPE_FINANCIAL => 'البعد المالي',
            self::TYPE_BENEFICIARIES => 'بعد المستفيدين',
            self::TYPE_INTERNAL_PROCESSES => 'العمليات الداخلية',
            self::TYPE_LEARNING_GROWTH => 'التعلم والنمو',
        ];
    }

    public function project()
    {
        return $this->belongsTo(SwotProject::class, 'swot_project_id');
    }

    public function getDimensionLabelAttribute(): string
    {
        return self::getDimensionLabels()[$this->dimension_type] ?? $this->dimension_type;
    }
}
