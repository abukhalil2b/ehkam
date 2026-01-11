<?php

namespace App\Http\Controllers;

use App\Models\OrgUnit;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StructureController extends Controller
{
    // Assuming the necessary models (Position, OrgUnit) and Request are imported

    // Method to show the list of unattached positions
    public function missingUnitsIndex()
    {
        // 1. Fetch positions that have no associated organizational units.
        // This relies on the OrgUnits() relationship being defined in the Position model.
        $unattachedPositions = Position::doesntHave('OrgUnits')->get();

        // 2. Fetch all units for the searchable dropdown selection
        $allUnits = OrgUnit::all(['id', 'name', 'type']);

        // Check if there are any positions needing attachment
        if ($unattachedPositions->isEmpty()) {
            return redirect()->route('admin_position.index')->with('info', '✅ لا توجد مسميات وظيفية متبقية تحتاج لربط وحدة تنظيمية.');
        }

        // Pass the data to the dedicated view
        return view('admin_structure.missing_units_assignment', compact('unattachedPositions', 'allUnits'));
    }

    // Method to handle the attachment action for a single position
    public function attachUnitToPosition(Request $request)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id',
            'org_unit_id' => 'required|exists:org_units,id',
        ]);

        $position = Position::findOrFail($request->position_id);
        $unitId = $request->org_unit_id;

        // Attach the position to the selected organizational unit
        // The attach() method ensures a record is created in the pivot table.
        $position->OrgUnits()->attach($unitId);

        // Redirect back to the same page. The successfully attached position will no longer appear 
        // because it now has a relationship.
        return back()->with('success', "تم ربط المسمى الوظيفي '{$position->title}' بالوحدة التنظيمية بنجاح. سيختفي من هذه القائمة.");
    }
}
