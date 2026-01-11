<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class ProfileController extends Controller
{


    public function edit()
    {
        $user = Auth::user();

        // 1. Fetch the CURRENT active history record (where end_date is NULL)
        // We use 'with' to eager-load the position and unit to avoid N+1 queries.
        $currentAssignment = EmployeeAssignment::where('user_id', $user->id)
            ->whereNull('end_date')
            ->with(['position', 'OrgUnit'])
            ->first();

        return view('profile', [ // Assuming your profile view is 'profile.show'
            'user' => $user,
            'currentAssignment' => $currentAssignment,
        ]);
    }
}
