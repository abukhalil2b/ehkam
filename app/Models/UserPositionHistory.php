<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPositionHistory extends Model
{
    protected $guarded =[];

    protected $table = 'user_position_history';

    protected $dates = ['start_date', 'end_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class, 'organizational_unit_id');
    }
}
