<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\OrgUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Positions with Hierarchy
        $allPositions = Position::all();
        $groupedPositions = $allPositions->groupBy('reports_to_position_id');

        $buildPositionTree = function ($parentId) use (&$buildPositionTree, $groupedPositions) {
            return $groupedPositions->get($parentId, collect())->map(function ($pos) use ($buildPositionTree) {
                $pos->subordinates = $buildPositionTree($pos->id);
                return $pos;
            });
        };
        $topLevelPositions = $buildPositionTree(null);

        // For the 'Create Position' Modal that might be on the index page
        $allUnits = OrgUnit::all();

        return view('positions.index', compact('topLevelPositions', 'allPositions', 'allUnits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allUnits = OrgUnit::all();
        $allPositions = Position::all();
        return view('positions.create', compact('allUnits', 'allPositions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'reports_to_position_id' => 'nullable|exists:positions,id',
            'org_unit_id' => 'required|exists:org_units,id',
            'job_code' => 'nullable|string|unique:positions,job_code',
        ]);

        // Auto-generate job code if not provided
        $jobCode = $request->job_code ?? 'POS-' . Str::upper(Str::random(6));

        $position = Position::create([
            'title' => $request->title,
            'reports_to_position_id' => $request->reports_to_position_id,
            'job_code' => $jobCode,
        ]);

        // Attach to Org Unit
        $position->orgUnits()->attach($request->org_unit_id);

        return redirect()->route('positions.index')
            ->with('success', "تم إنشاء المسمى الوظيفي ({$position->title}) بنجاح.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        $position->load(['reportsTo', 'subordinates', 'orgUnits', 'currentEmployees.user']);
        return view('positions.show', compact('position'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        $allPositions = Position::where('id', '!=', $position->id)->get();
        $allUnits = OrgUnit::all();

        // Load currently attached units
        $currentUnitIds = $position->orgUnits->pluck('id')->toArray();

        return view('positions.edit', compact('position', 'allPositions', 'allUnits', 'currentUnitIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $position)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'reports_to_position_id' => 'nullable|exists:positions,id',
            // Prevent cycling reporting to itself or children
            'reports_to_position_id' => [
                'nullable',
                'exists:positions,id',
                function ($attribute, $value, $fail) use ($position) {
                    if ($value == $position->id) {
                        $fail('لا يمكن للوظيفة أن تتبع نفسها.');
                    }
                    // TODO: Add more robust circular dependency check if needed
                },
            ],
            'org_unit_id' => 'required|exists:org_units,id',
            'job_code' => 'nullable|string|unique:positions,job_code,' . $position->id,
        ]);

        $position->update([
            'title' => $request->title,
            'reports_to_position_id' => $request->reports_to_position_id,
            'job_code' => $request->job_code ?? $position->job_code,
        ]);

        // Update Org Unit
        // Assuming primarily one unit for now, as per original logic, but using sync to be safe
        $position->orgUnits()->sync([$request->org_unit_id]);

        return redirect()->route('positions.index')
            ->with('success', "تم تحديث المسمى الوظيفي ({$position->title}) بنجاح.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        // 1. Check for Subordinates
        if ($position->subordinates()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المسمى الوظيفي لأنه يحتوي على وظائف تابعة له. يرجى نقل التبعية أولاً.');
        }

        // 2. Check for Active Employees
        if ($position->currentEmployees()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المسمى الوظيفي لأنه مشغول حالياً من قبل موظفين.');
        }

        $position->delete();

        return redirect()->route('positions.index')
            ->with('success', 'تم حذف المسمى الوظيفي بنجاح.');
    }
}
