<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodTemplate extends Model
{
    // Allows mass assignment for all fields except the reserved ones.
    protected $guarded = [];

    // NOTE: If you are using the 'period' column on the Indicator table as a foreign key,
    // the relationship here will be a standard HasMany.

    /**
     * Get the indicators that use this period template.
     */
    public function indicators(): HasMany
    {
        // Assuming 'period' on Indicator is the foreign key (or matches 'name' or 'cate' if using the custom relation).
        return $this->hasMany(Indicator::class, 'period', 'name');
    }
}