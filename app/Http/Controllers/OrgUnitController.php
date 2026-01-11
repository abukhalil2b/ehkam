<?php

namespace App\Http\Controllers;

use App\Models\OrgUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrgUnitController extends Controller
{

    public function index()
    {
        // Get the root organizational unit (Minister level)
        $rootUnit = OrgUnit::whereNull('parent_id')
            ->with([
                'children' => function ($query) {
                    $query->orderBy('type')->orderBy('name');
                },
                'children.children' => function ($query) {
                    $query->orderBy('type')->orderBy('name');
                },
                'children.children.children' => function ($query) {
                    $query->orderBy('type')->orderBy('name');
                },
                'positions.employees' => function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', now());
                }
            ])
            ->first();

        // Get all organizational units in a flat structure for alternative views
        $allUnits = OrgUnit::with([
            'parent',
            'positions.employees' => function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            }
        ])
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        // Count statistics
        $stats = [
            'total_units' => OrgUnit::count(),
            'ministers' => OrgUnit::where('type', 'Minister')->count(),
            'directorates' => OrgUnit::where('type', 'Directorate')->count(),
            'departments' => OrgUnit::where('type', 'Department')->count(),
            'sections' => OrgUnit::where('type', 'Section')->count(),
            'experts' => OrgUnit::where('type', 'Expert')->count(),
        ];

        return view('org_units.index', compact('rootUnit', 'allUnits', 'stats'));
    }

    public function create()
    {

        $OrgUnits = OrgUnit::all();

        return view('org_units.create', compact('OrgUnits'));
    }


    public function storeUnit(Request $request)
    {
        // 1. Validate (Updated to include all types)
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Minister,Undersecretary,Directorate,Department,Section,Expert',
            'parent_id' => 'nullable|exists:org_units,id',
        ]);

        // 2. Define Prefixes
        $prefixes = [
            'Minister' => 'MIN',
            'Undersecretary' => 'UND',
            'Directorate' => 'DIR',
            'Department' => 'DEP',
            'Section' => 'SEC',
            'Expert' => 'EXP',
        ];

        $type = $request->type;
        $prefix = $prefixes[$type] ?? 'ORG';

        // 3. Generate Unique Unit Code
        // We count existing units of this type to determine the next number
        // We use a loop to ensure uniqueness in case records were deleted
        $counter = OrgUnit::where('type', $type)->count() + 1;

        do {
            $code = $prefix . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $exists = OrgUnit::where('unit_code', $code)->exists();
            if ($exists) {
                $counter++;
            }
        } while ($exists);

        // 4. Create the Unit
        $unit = OrgUnit::create([
            'unit_code' => $code,
            'name' => $request->name,
            'type' => $type,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('org_unit.index')
            ->with('success', "تمت إضافة الوحدة ({$unit->name}) بنجاح. الرمز: {$unit->unit_code}");
    }
    public function edit(OrgUnit $orgUnit)
    {
        $orgUnit->load(['parent', 'positions.employees']);
        $parentUnits = OrgUnit::where('id', '!=', $orgUnit->id)->get();
        // Load all available positions for the dropdown
        $allPositions = \App\Models\Position::orderBy('title')->get();

        return view('org_units.edit', compact('orgUnit', 'parentUnits', 'allPositions'));
    }

    public function update(Request $request, OrgUnit $orgUnit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Minister,Undersecretary,Directorate,Department,Section,Expert',
            'parent_id' => 'nullable|exists:org_units,id|not_in:' . $orgUnit->id,
        ]);

        $orgUnit->update([
            'name' => $request->name,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('org_unit.edit', $orgUnit->id)
            ->with('success', 'تم تحديث بيانات الوحدة بنجاح');
    }

    public function addPosition(Request $request)
    {
        $request->validate([
            'org_unit_id' => 'required|exists:org_units,id',
            'position_id' => 'required|exists:positions,id',
        ]);

        $orgUnit = OrgUnit::findOrFail($request->org_unit_id);

        // Check if already attached
        if (!$orgUnit->positions()->where('positions.id', $request->position_id)->exists()) {
            $orgUnit->positions()->attach($request->position_id);
            return back()->with('success', 'تم ربط الوظيفة بالوحدة بنجاح');
        }

        return back()->with('info', 'هذه الوظيفة مرتبطة بالفعل بهذه الوحدة');
    }

    public function removePosition(Request $request)
    {
        $request->validate([
            'org_unit_id' => 'required|exists:org_units,id',
            'position_id' => 'required|exists:positions,id',
        ]);

        $orgUnit = OrgUnit::findOrFail($request->org_unit_id);
        $orgUnit->positions()->detach($request->position_id);

        return back()->with('success', 'تم فك ارتباط الوظيفة بنجاح');
    }
}
