<?php

namespace App\Http\Controllers;

use App\Models\CalendarDelegation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CalendarDelegationController extends Controller
{
    public function index()
    {
        $myDelegations = CalendarDelegation::with(['employee', 'manager'])
            ->where('manager_id', auth()->id())
            ->where('is_active', true)
            ->get();

        $delegatedToMe = CalendarDelegation::with(['employee', 'manager'])
            ->where('employee_id', auth()->id())
            ->where('is_active', true)
            ->get();

        $availableUsers = User::whereNotIn('id', [auth()->id()])
            ->whereNotIn('id', $myDelegations->pluck('employee_id'))
            ->select('id', 'name', 'email')
            ->get();

        // Fetch User's Active Departments
        $myDepartments = auth()->user()->positionHistory()
            ->whereNull('end_date')
            ->with('orgUnit')
            ->get()
            ->pluck('orgUnit')
            ->unique('id');

        return view('calendar.delegations', compact('myDelegations', 'delegatedToMe', 'availableUsers', 'myDepartments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => [
                'required',
                'exists:users,id',
                Rule::notIn([auth()->id()]) // User cannot delegate to themselves
            ],
        ]);

        // Check if delegation already exists
        $existing = CalendarDelegation::where('manager_id', auth()->id())
            ->where('employee_id', $request->employee_id)
            ->first();

        if ($existing) {
            if ($existing->is_active) {
                return back()->with('error', 'التفويض موجود بالفعل');
            } else {
                // Reactivate
                $existing->update([
                    'is_active' => true,
                    'granted_at' => now(),
                    'revoked_at' => null,
                ]);
                return back()->with('success', 'تم إعادة تفعيل التفويض');
            }
        }

        CalendarDelegation::create([
            'manager_id' => auth()->id(),
            'employee_id' => $request->employee_id,
            'is_active' => true,
            'granted_at' => now(),
        ]);

        return back()->with('success', 'تم منح التفويض بنجاح');
    }

    public function destroy(CalendarDelegation $delegation)
    {
        if ($delegation->manager_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بإلغاء هذا التفويض');
        }

        $delegation->revoke();

        return back()->with('success', 'تم إلغاء التفويض بنجاح');
    }
}
