<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ImpersonateController - Allows superadmin to impersonate other users.
 * 
 * Only user ID 1 (superadmin) can impersonate other users.
 * Original user ID is stored in session to allow returning.
 */
class ImpersonateController extends Controller
{
    /**
     * Start impersonating a user.
     * Stores the original user ID in session.
     */
    public function start(User $user)
    {
        // Only user ID 1 (superadmin) can impersonate
        if (Auth::id() !== 1) {
            abort(403, 'فقط المشرف الأعلى يمكنه انتحال شخصية مستخدم آخر');
        }

        // Cannot impersonate yourself
        if ($user->id === 1) {
            return back()->with('error', 'لا يمكنك انتحال شخصية نفسك');
        }

        // Store original user ID in session
        session(['impersonate_original_user_id' => Auth::id()]);
        session(['impersonate_user_id' => $user->id]);

        return redirect()->route('dashboard')
            ->with('success', "أنت الآن تتصفح كـ {$user->name}");
    }

    /**
     * Stop impersonating and return to original user.
     */
    public function stop()
    {
        if (!session()->has('impersonate_user_id')) {
            return back()->with('error', 'أنت لا تنتحل شخصية أي مستخدم');
        }

        // Clear impersonation session data
        session()->forget('impersonate_user_id');
        session()->forget('impersonate_original_user_id');

        return redirect()->route('dashboard')
            ->with('success', 'تم إيقاف انتحال الشخصية بنجاح');
    }
}
