<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarDelegation extends Model
{
    protected $fillable = [
        'manager_id',
        'employee_id',
        'is_active',
        'granted_at',
        'revoked_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'granted_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function revoke(): void
    {
        $this->update([
            'is_active'  => false,
            'revoked_at' => now(),
        ]);
    }
}
