<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrgUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_code',
        'name',
        'type',
        'parent_id',
    ];

    /**
     * Get the parent organizational unit.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'parent_id');
    }

    /**
     * Get all child organizational units.
     */
    public function children(): HasMany
    {
        return $this->hasMany(OrgUnit::class, 'parent_id');
    }

    /**
     * Get all positions associated with this organizational unit.
     */
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'org_unit_positions');
    }

    /**
     * Get all employee assignments for this organizational unit.
     */
    public function employeeAssignments(): HasMany
    {
        return $this->hasMany(EmployeeAssignment::class);
    }

    /**
     * Get all descendants recursively.
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get all ancestors recursively.
     */
    public function ancestors()
    {
        return $this->parent()->with('ancestors');
    }

    /**
     * Scope to get only root organizational units (no parent).
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the full hierarchical path of this unit.
     */
    public function getPathAttribute(): string
    {
        $path = collect([$this->name]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }

        return $path->implode(' > ');
    }

    /**
     * Get the depth level in the hierarchy.
     */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $parent = $this->parent;

        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }

        return $depth;
    }

    public function calendarPermissions()
{
    return $this->hasMany(CalendarPermission::class);
}

}