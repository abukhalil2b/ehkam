<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\OrganizationalUnit;
use App\Models\Position;
use App\Models\User;
use App\Models\Profile;
use App\Models\Permission;
use App\Models\UserPositionHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrganizationalUnitController extends Controller
{


    public function index()
    {
        // Eager-load essential user relationships
        $users = User::with([
            'currentHistory.position',
            'currentHistory.organizationalUnit',
        ])->get();

        // Hierarchical data for units & positions
        $topLevelUnits = OrganizationalUnit::whereNull('parent_id')
            ->with('children.children')
            ->get();

        $topLevelPositions = Position::whereNull('reports_to_position_id')
            ->with('subordinates.subordinates')
            ->get();

        // Flat data for dropdowns / forms
        $organizationalUnits = OrganizationalUnit::all();
        $allPositions = Position::all();

        return view('organizational_unit.index', [
            'users' => $users,
            'organizationalUnits' => $organizationalUnits,
            'allPositions' => $allPositions,
            'topLevelUnits' => $topLevelUnits,
            'topLevelPositions' => $topLevelPositions,
        ]);
    }


    public function storeUnit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Directorate,Department,Section',
            'parent_id' => 'nullable|exists:organizational_units,id',
        ]);

        $unit = OrganizationalUnit::create($request->only(['name', 'type', 'parent_id']));

        return redirect()->route('admin_structure.index')->with('success', "تمت إضافة الوحدة التنظيمية ({$unit->name}) بنجاح.");
    }
}
