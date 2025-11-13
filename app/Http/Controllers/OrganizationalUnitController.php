<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrganizationalUnit;


class OrganizationalUnitController extends Controller
{

    public function index()
{
    // Fetch all units in ONE query
    $allUnits = OrganizationalUnit::all();

    // Group by parent_id
    $grouped = $allUnits->groupBy('parent_id');

    // Recursive function to build tree in memory
    $buildTree = function ($parentId) use (&$buildTree, $grouped) {
        return $grouped->get($parentId, collect())->map(function ($unit) use ($buildTree, $grouped) {
            $unit->children = $buildTree($unit->id);
            return $unit;
        });
    };

    // Build tree starting from null parent_id
    $topLevelUnits = $buildTree(null);

    return view('organizational_unit.index', compact('topLevelUnits'));
}




    public function create()
    {

        $organizationalUnits = OrganizationalUnit::all();

        return view('organizational_unit.create', compact('organizationalUnits'));
    }



    public function storeUnit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Directorate,Department,Section',
            'parent_id' => 'nullable|exists:organizational_units,id',
        ]);

        $unit = OrganizationalUnit::create($request->only(['name', 'type', 'parent_id']));

        return redirect()->route('organizational_unit.index')->with('success', "تمت إضافة الوحدة التنظيمية ({$unit->name}) بنجاح.");
    }
}
