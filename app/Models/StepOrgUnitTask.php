<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StepOrgUnitTask extends Model
{
    protected $guarded = [];

    public function OrgUnit()
    {
        return $this->belongsTo(OrgUnit::class);
    }

    public function periodTemplate()
    {
        return $this->belongsTo(PeriodTemplate::class);
    }
}
