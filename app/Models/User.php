<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Profile;

class User extends Authenticatable
{
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected ?array $cachedPermissions = null;

    public function assignProfile($profile)
    {
        $profileId = $profile instanceof Profile ? $profile->id : $profile;
        $this->profiles()->syncWithoutDetaching($profileId);
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class);
    }

    public function profiles()
    {
        // Use the default naming conventions or specify the custom pivot table 'user_profile'
        return $this->belongsToMany(Profile::class, 'user_profile', 'user_id', 'profile_id')
            ->withTimestamps(); // Include timestamps from the pivot table
    }

    /**
     * Remove a profile from the user.
     * @param Profile|int $profile
     */
    public function revokeProfile($profile)
    {
        $profileId = $profile instanceof Profile ? $profile->id : $profile;
        $this->profiles()->detach($profileId);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permission', 'user_id', 'permission_id');
    }

    /**
     * Sync direct permissions for the user.
     * @param array $permissions Array of Permission IDs
     */
    public function syncPermissions(array $permissions)
    {
        $this->permissions()->sync($permissions);
    }

    public function hasPermission(string $slug): bool
    {
        // 1. Super Admin Bypass (Crucial for maintenance)
        if ($this->id === 1) {
            return true;
        }

        // 2. Check if the required slug is in the user's permission list
        return in_array($slug, $this->getPermissions());
    }

    // Assuming this method is inside a User Model or similar class
    public function getPermissions()
    {
        // 1. Get the IDs of all profiles assigned to the current user.
        $profileIds = DB::table('user_profile')
            ->where('user_id', $this->id)
            ->pluck('profile_id')
            ->toArray();

        // Initialize an array to store all unique permission slugs.
        $allPermissionsSlugs = [];

        // 2. Get the slugs for all permissions associated with the user's profiles.
        if (!empty($profileIds)) {
            $profilePermissionsSlugs = DB::table('profile_permission')
                ->join('permissions', 'profile_permission.permission_id', '=', 'permissions.id')
                ->whereIn('profile_permission.profile_id', $profileIds)
                ->pluck('permissions.slug')
                ->toArray();

            $allPermissionsSlugs = array_merge($allPermissionsSlugs, $profilePermissionsSlugs);
        }

        // 3. Get the slugs for all permissions directly (individually) assigned to the user.
        $individualPermissionsSlugs = DB::table('user_permission')
            ->join('permissions', 'user_permission.permission_id', '=', 'permissions.id')
            ->where('user_permission.user_id', $this->id)
            ->pluck('permissions.slug')
            ->toArray();

        $allPermissionsSlugs = array_merge($allPermissionsSlugs, $individualPermissionsSlugs);

        // 4. Ensure all permissions are unique and re-index the array.
        return array_values(array_unique($allPermissionsSlugs));
    }

    // 1. Relationship to all historical assignments
    public function positionHistory()
    {
        return $this->hasMany(UserPositionHistory::class);
    }

    // 2. Relationship to the CURRENT (active) assignment history record
    public function currentHistory()
    {
        return $this->hasOne(UserPositionHistory::class)
            ->whereNull('end_date') // A null end_date signifies the current, active assignment
            ->latest('start_date'); // Ensures we get the most recent active one if multiple somehow exist
    }

    // Current active position
    public function currentPosition()
    {
        return $this->hasOne(UserPositionHistory::class)
            ->whereNull('end_date')
            ->latest('start_date') // in case multiple active (defensive)
            ->with('position')     // eager load position relation
            ->first()?->position;  // return Position instance
    }

    // Current active organizational unit
    public function currentUnit()
    {
        return $this->hasOne(UserPositionHistory::class)
            ->whereNull('end_date')
            ->latest('start_date')
            ->with('organizationalUnit')
            ->first()?->organizationalUnit; // return OrganizationalUnit instance
    }
    public function currentPositionHistory()
    {
        return $this->hasOne(UserPositionHistory::class)
            ->whereNull('end_date')
            ->latest('start_date');
    }

    public function currentUnitHistory()
    {
        return $this->hasOne(UserPositionHistory::class)
            ->whereNull('end_date')
            ->latest('start_date');
    }

    public function latestHistory()
    {
        return $this->hasOne(UserPositionHistory::class)->latest('start_date');
    }
}
