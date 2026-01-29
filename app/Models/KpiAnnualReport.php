<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiAnnualReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'kpi_indicator_id',
        'year',
        'analysis',
        'challenges',
        'recommendations',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    /**
     * Get the indicator for this report.
     */
    public function indicator(): BelongsTo
    {
        return $this->belongsTo(KpiIndicator::class, 'kpi_indicator_id');
    }
}
