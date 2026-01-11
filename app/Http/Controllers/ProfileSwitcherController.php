<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileSwitcherController extends Controller
{
    public function switch(Request $request, $id)
    {
        $user = Auth::user();

        // Verify the user owns this profile
        if (!$user->profiles()->where('profiles.id', $id)->exists()) {
            abort(403, 'Unauthorized action.');
        }

        // Set Attribute in Session
        session(['active_profile_id' => $id]);

        return back()->with('success', 'تم تغيير الملف الشخصي بنجاح');
    }

    public function reset()
    {
        session()->forget('active_profile_id');
        return back()->with('success', 'تمت استعادة الصلاحيات الكاملة');
    }
}
