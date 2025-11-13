<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StepOrganizationalUnitTask extends Model
{
    protected $guarded = [];

    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    public function periodTemplate()
    {
        return $this->belongsTo(PeriodTemplate::class);
    }
}
