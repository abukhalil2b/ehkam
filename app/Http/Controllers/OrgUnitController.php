<?php

namespace App\Http\Controllers;

use App\Models\OrgUnit;
use App\Models\Position;
use App\Services\OrgStructureService;
use Illuminate\Http\Request;

class OrgUnitController extends Controller
{
    protected OrgStructureService $structureService;

    public function __construct(OrgStructureService $structureService)
    {
        $this->structureService = $structureService;
    }

    public function index()
    {
        // Get the root organizational unit (Minister level)
        $rootUnit = OrgUnit::whereNull('parent_id')
            ->with([
                'children' => function ($query) {
                    $query->orderBy('hierarchy_order')->orderBy('name');
                },
                'children.children' => function ($query) {
                    $query->orderBy('hierarchy_order')->orderBy('name');
                },
                'children.children.children' => function ($query) {
                    $query->orderBy('hierarchy_order')->orderBy('name');
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
            ->orderBy('hierarchy_order')
            ->orderBy('name')
            ->get();

        // Count statistics using service
        $stats = $this->structureService->getStats();

        return view('org_units.index', compact('rootUnit', 'allUnits', 'stats'));
    }

    public function create()
    {
        $OrgUnits = OrgUnit::orderBy('hierarchy_order')->orderBy('name')->get();
        $typeLabels = OrgStructureService::getTypeLabels();
        $allowedParentRules = OrgStructureService::getAllowedParentRules();

        return view('org_units.create', compact('OrgUnits', 'typeLabels', 'allowedParentRules'));
    }

    public function storeUnit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Minister,Undersecretary,Directorate,Department,Section,Expert',
            'parent_id' => 'nullable|exists:org_units,id',
            'hierarchy_order' => 'nullable|integer|min:0|max:255',
        ]);

        try {
            $unit = $this->structureService->createUnit($request->only([
                'name', 'type', 'parent_id', 'hierarchy_order'
            ]));

            return redirect()->route('org_unit.index')
                ->with('success', "تمت إضافة الوحدة ({$unit->name}) بنجاح. الرمز: {$unit->unit_code}");
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function edit(OrgUnit $orgUnit)
    {
        $orgUnit->load([
            'parent',
            'positions.currentEmployees.user'
        ]);
        
        $parentUnits = OrgUnit::where('id', '!=', $orgUnit->id)
            ->orderBy('hierarchy_order')
            ->orderBy('name')
            ->get();

        $linkedPositionIds = $orgUnit->positions->pluck('id')->toArray();
        $availablePositions = Position::whereNotIn('id', $linkedPositionIds)
            ->orderBy('title')
            ->get();

        $typeLabels = OrgStructureService::getTypeLabels();
        $allowedParentRules = OrgStructureService::getAllowedParentRules();

        return view('org_units.edit', compact(
            'orgUnit', 
            'parentUnits', 
            'availablePositions',
            'typeLabels',
            'allowedParentRules'
        ));
    }

    public function update(Request $request, OrgUnit $orgUnit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Minister,Undersecretary,Directorate,Department,Section,Expert',
            'parent_id' => 'nullable|exists:org_units,id|not_in:' . $orgUnit->id,
            'hierarchy_order' => 'nullable|integer|min:0|max:255',
        ]);

        try {
            $this->structureService->updateUnit($orgUnit, $request->only([
                'name', 'type', 'parent_id', 'hierarchy_order'
            ]));

            return redirect()->route('org_unit.edit', $orgUnit->id)
                ->with('success', 'تم تحديث بيانات الوحدة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function destroy(OrgUnit $orgUnit)
    {
        try {
            $name = $orgUnit->name;
            $this->structureService->deleteUnit($orgUnit);

            return redirect()->route('org_unit.index')
                ->with('success', "تم حذف الوحدة ({$name}) بنجاح");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * إدارة المديريات
     */
    public function directorates()
    {
        $units = $this->structureService->getUnitsByType('Directorate');
        $allowedParents = $this->structureService->getAllowedParentsForType('Directorate');
        $stats = $this->structureService->getStats();
        
        return view('org_units.structure.directorates', [
            'units' => $units,
            'allowedParents' => $allowedParents,
            'stats' => $stats,
            'type' => 'Directorate',
            'typeLabel' => 'مديرية عامة',
            'typeLabelPlural' => 'المديريات العامة',
        ]);
    }

    /**
     * إدارة الدوائر
     */
    public function departments()
    {
        $units = $this->structureService->getUnitsByType('Department');
        $allowedParents = $this->structureService->getAllowedParentsForType('Department');
        $stats = $this->structureService->getStats();
        
        return view('org_units.structure.departments', [
            'units' => $units,
            'allowedParents' => $allowedParents,
            'stats' => $stats,
            'type' => 'Department',
            'typeLabel' => 'دائرة',
            'typeLabelPlural' => 'الدوائر',
        ]);
    }

    /**
     * إدارة الأقسام
     */
    public function sections()
    {
        $units = $this->structureService->getUnitsByType('Section');
        $allowedParents = $this->structureService->getAllowedParentsForType('Section');
        $stats = $this->structureService->getStats();
        
        return view('org_units.structure.sections', [
            'units' => $units,
            'allowedParents' => $allowedParents,
            'stats' => $stats,
            'type' => 'Section',
            'typeLabel' => 'قسم',
            'typeLabelPlural' => 'الأقسام',
        ]);
    }

    /**
     * إضافة سريعة لوحدة من صفحة متخصصة
     */
    public function storeQuick(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Minister,Undersecretary,Directorate,Department,Section,Expert',
            'parent_id' => 'required|exists:org_units,id',
            'hierarchy_order' => 'nullable|integer|min:0|max:255',
        ]);

        try {
            $unit = $this->structureService->createUnit($request->only([
                'name', 'type', 'parent_id', 'hierarchy_order'
            ]));

            return back()->with('success', "تمت إضافة {$unit->name} بنجاح. الرمز: {$unit->unit_code}");
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function addPosition(Request $request)
    {
        $request->validate([
            'org_unit_id' => 'required|exists:org_units,id',
            'position_id' => 'required|exists:positions,id',
        ]);

        $orgUnit = OrgUnit::findOrFail($request->org_unit_id);

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
