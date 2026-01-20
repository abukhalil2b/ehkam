<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSwitcherController extends Controller
{
    /**
     * Switch to a specific role.
     * Saves active_role_id to database for persistence across sessions.
     */
    public function switch(Request $request, $id)
    {
        $user = Auth::user();

        // Verify the user owns this role
        if (!$user->roles()->where('roles.id', $id)->exists()) {
            abort(403, 'Unauthorized action.');
        }

        // Save to database for persistence
        $user->update(['active_role_id' => $id]);

        return back()->with('success', 'تم تغيير الدور بنجاح');
    }

    /**
     * Reset to default (all permissions from all roles).
     */
    public function reset()
    {
        $user = Auth::user();
        $user->update(['active_role_id' => null]);

        return back()->with('success', 'تمت استعادة الصلاحيات الكاملة');
    }
}
