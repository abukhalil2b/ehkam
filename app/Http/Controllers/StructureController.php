<?php

namespace App\Http\Controllers;

use App\Models\OrganizationalUnit;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StructureController extends Controller
{
    // Assuming the necessary models (Position, OrganizationalUnit) and Request are imported

    // Method to show the list of unattached positions
    public function missingUnitsIndex()
    {
        // 1. Fetch positions that have no associated organizational units.
        // This relies on the organizationalUnits() relationship being defined in the Position model.
        $unattachedPositions = Position::doesntHave('organizationalUnits')->get();

        // 2. Fetch all units for the searchable dropdown selection
        $allUnits = OrganizationalUnit::all(['id', 'name', 'type']);

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
            'organizational_unit_id' => 'required|exists:organizational_units,id',
        ]);

        $position = Position::findOrFail($request->position_id);
        $unitId = $request->organizational_unit_id;

        // Attach the position to the selected organizational unit
        // The attach() method ensures a record is created in the pivot table.
        $position->organizationalUnits()->attach($unitId);

        // Redirect back to the same page. The successfully attached position will no longer appear 
        // because it now has a relationship.
        return back()->with('success', "تم ربط المسمى الوظيفي '{$position->title}' بالوحدة التنظيمية بنجاح. سيختفي من هذه القائمة.");
    }
}
