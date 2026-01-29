<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\OrgUnit;
use App\Models\Position;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Sector;
use App\Models\EmployeeAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function create()
    {

        $users = User::where('user_type', 'staff')->get();
        // Hierarchical data for units & positions
        $topLevelUnits = OrgUnit::whereNull('parent_id')
            ->with('children.children')
            ->get();

        $topLevelPositions = Position::all();

        // Flat data for dropdowns / forms
        $OrgUnits = OrgUnit::all();
        $allPositions = Position::all();

        return view('admin_structure.positions.create', [
            'OrgUnits' => $OrgUnits,
            'allPositions' => $allPositions,
            'topLevelUnits' => $topLevelUnits,
            'topLevelPositions' => $topLevelPositions,
            'users' => $users,
        ]);
    }


    public function index()
    {
        // ------------------------------
        // 1️⃣ Organizational Units (already optimized)
        // ------------------------------
        $allUnits = OrgUnit::all();
        $groupedUnits = $allUnits->groupBy('parent_id');

        $buildUnitTree = function ($parentId) use (&$buildUnitTree, $groupedUnits) {
            return $groupedUnits->get($parentId, collect())->map(function ($unit) use ($buildUnitTree) {
                $unit->children = $buildUnitTree($unit->id);
                return $unit;
            });
        };
        $topLevelUnits = $buildUnitTree(null);

        // ------------------------------
        // 2️⃣ Positions (flat list)
        // ------------------------------
        $allPositions = Position::all();
        $topLevelPositions = $allPositions;

        return view('admin_structure.index', compact(
            'topLevelUnits',
            'topLevelPositions',
            'allUnits',
            'allPositions'
        ));
    }


    // 1. Show the assignment form
    public function editUserPermissions(User $user)
    {
        $allroles = Role::all();
        $allPermissions = Permission::all();

        // Get currently assigned data
        $assignedProfileIds = $user->roles->pluck('id')->toArray();

        return view('admin.user_permissions_edit', compact(
            'user',
            'allroles',
            'allPermissions',
            'assignedProfileIds'
        ));
    }

    // 2. Process the assignment form submission
    public function updateUserPermissions(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'nullable|array',
        ]);

        // Sync Roles (replaces all current roles with the new list)
        $user->roles()->sync((array) $request->input('roles'));

        // Clear permission cache
        $user->clearPermissionCache();

        return redirect()->back()->with('success', 'تم تحديث الصلاحيات');
    }

    public function createUserForSector()
    {
        $sectors = Sector::all();
        $users = User::with('sectors')->whereHas('sectors')->get(); // eager loaded
        return view('admin_users.create_for_sector', compact('sectors', 'users'));
    }

    public function storeUserForSector(Request $request)
    {
        // Validation
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'sector_id' => 'required|array',
        ]);

        // Create User
        $user = User::create([
            'user_type' => 'staff',
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['email']), // default
        ]);

        // Assign sectors
        $user->sectors()->sync($request->sector_id);

        return redirect()->route('admin_users.create_for_sector')
            ->with('success', "تم إنشاء المستخدم الجديد ({$user->name}) بنجاح.");
    }


    public function linkUserWithSectorCreate(User $user)
    {
        $sectors = Sector::all();
        return view('admin_users.link_user_with_sector_create', compact('sectors', 'user'));
    }

    public function linkUserWithSectorStore(Request $request, User $user)
    {
        $request->validate([
            'sector_id' => 'required|array'
        ]);

        $user->sectors()->sync($request->sector_id);

        return redirect()
            ->route('admin_users.index', $user)
            ->with('success', 'تم ربط المستخدم بالقطاعات بنجاح');
    }



    public function createUser()
    {
        $OrgUnits = OrgUnit::all();
        $allPositions = Position::all();

        // FIX: Add $topLevelUnits and $topLevelPositions 
        // to satisfy components or inherited layout logic.
        $topLevelUnits = OrgUnit::whereNull('parent_id')->get();
        $topLevelPositions = Position::all();

        $users = User::with([
            'positionHistory',
            'currentHistory.position',
            'currentHistory.OrgUnit'
        ])->get();

        // Pass ALL required variables to the view
        return view('admin_users.create', compact(
            'OrgUnits',
            'allPositions',
            'topLevelUnits',
            'topLevelPositions',
            'users'
        ));
    }

    public function storeUser(Request $request)
    {
        // 1. Validation: Added validation for position and unit IDs
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',

            // New assignment fields
            'position_id' => 'required|exists:positions,id',
            'org_unit_id' => 'required|exists:org_units,id',
            'start_date' => 'required|date',
        ]);

        // 2. User Creation
        $user = User::create([
            'user_type' => 'staff',
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['email']),
        ]);

        // 3. Initial Position Assignment (EmployeeAssignment creation)
        EmployeeAssignment::create([
            'user_id' => $user->id,
            'position_id' => $validatedData['position_id'],
            'org_unit_id' => $validatedData['org_unit_id'],
            'start_date' => $validatedData['start_date'],
            'end_date' => null, // This is the current assignment
        ]);

        return redirect()->route('admin_users.index')
            ->with('success', "تم إنشاء المستخدم الجديد ({$user->name}) بنجاح.");
    }

    public function indexUsers()
    {
        // This now correctly calls the newly defined latestHistory() method.
        $users = User::with(['roles', 'latestHistory.position'])
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('admin.user.index', compact('users'));
    }


    /**
     * تخزين مسمى وظيفي جديد في قاعدة البيانات.
     */
    public function storePosition(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',

            // 🚨 ADDED: Validation for the required unit ID
            'org_unit_id' => 'required|exists:org_units,id',
        ]);

        // 1. Create the new Position record
        $position = Position::create($request->only(['title']));

        // 2. 🚨 ADDED: Attach the newly created position to the selected organizational unit
        // The relationship is many-to-many, even if the UI only sends one unit ID
        $OrgUnitId = $request->input('org_unit_id');

        // Use the attach method on the relationship
        $position->OrgUnits()->attach($OrgUnitId);

        // NOTE: If you decide a Position can exist in multiple units, you'd handle an array here.
        // Given the UI only shows one selection, we assume one unit is attached initially.


        return redirect()->route('admin_position.index')->with('success', "تمت إضافة المسمى الوظيفي ({$position->title}) بنجاح.");
    }
    /**
     * تعيين/ترقية موظف عن طريق تحديث السجل القديم وإنشاء سجل جديد.
     */
    public function assignUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'new_position_id' => 'required|exists:positions,id',
            'new_unit_id' => 'nullable|exists:org_units,id',
            'start_date' => 'required|date',
        ]);

        // 1. إغلاق السجل الوظيفي القديم للموظف (إذا وُجد)
        EmployeeAssignment::where('user_id', $validated['user_id'])
            ->whereNull('end_date')
            ->update(['end_date' => Carbon::parse($validated['start_date'])->subDay()->format('Y-m-d')]);

        // 2. إنشاء سجل وظيفي جديد (التعيين الحالي)
        EmployeeAssignment::create([
            'user_id' => $validated['user_id'],
            'position_id' => $validated['new_position_id'],
            'org_unit_id' => $validated['new_unit_id'],
            'start_date' => $validated['start_date'],
            'end_date' => null,
        ]);

        return redirect()->route('admin_structure.index')->with('success', 'تم تسجيل التعيين/الترقية بنجاح.');
    }

    public function getPositionsByUnit(Request $request)
    {
        $unitId = $request->get('unit_id');

        // البحث عن الوحدة التنظيمية باستخدام المعرّف
        $unit = OrgUnit::find($unitId);

        // إذا لم يتم العثور على الوحدة، نرجع قائمة فارغة
        if (!$unit) {
            return response()->json([]);
        }

        // استخدام علاقة positions() المحددة في نموذج OrgUnit
        // لجلب المسميات الوظيفية المرتبطة بهذه الوحدة عبر جدول الربط.
        $positions = $unit->positions()
            ->get(['positions.id', 'positions.title']);

        return response()->json($positions);
    }

    public function showUser(User $user)
    {
        // Load relationships for the view
        $user->load([
            'roles',
            'positionHistory.position',
            'positionHistory.OrgUnit'
        ]);

        // Optional: Get latest active history
        $latestHistory = $user->positionHistory()
            ->whereNull('end_date')
            ->latest('start_date')
            ->first();

        // Load all roles and permissions for selection forms
        $allroles = Role::orderBy('title')->get();
        $allPermissions = Permission::orderBy('title')->get();

        $positions = Position::orderBy('title')->get();
        $units = OrgUnit::orderBy('name')->get();

        return view('admin.user.show', compact(
            'user',
            'latestHistory',
            'allroles',
            'allPermissions',
            'positions',
            'units'
        ));
    }


    public function editPosition(Position $position)
    {
        return view('admin_structure.positions.edit', compact('position'));
    }

    public function updatePosition(Request $request, User $user)
    {
        // Restrict access if necessary (Auth::id() !== 1)
        if (Auth::id() !== 1) {
            // ... (Error handling) ...
        }

        $validated = $request->validate([
            'position_id' => ['required', 'exists:positions,id'],
            'org_unit_id' => ['required', 'exists:org_units,id'], // Made unit required for new assignment
            'start_date' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $newStartDate = Carbon::parse($validated['start_date']);
        $activeRecord = $user->positionHistory()->whereNull('end_date')->first();

        // 1. Close the old active record (if it exists)
        if ($activeRecord) {
            // Close the old record one day before the new start date
            $activeRecord->update(['end_date' => $newStartDate->copy()->subDay()->format('Y-m-d')]);
        }

        // 2. Create the new record (The NEW ASSIGNMENT)
        $user->positionHistory()->create([
            'position_id' => $validated['position_id'],
            'org_unit_id' => $validated['org_unit_id'],
            'start_date' => $validated['start_date'],
            'end_date' => null, // This is the new active record
        ]);

        return redirect()->route('admin_users.show', $user)
            ->with('success', 'تم تسجيل التعيين/الترقية بنجاح.');
    }

    public function editPositionRecord(User $user)
    {
        $activeRecord = $user->currentHistory;

        if (!$activeRecord) {
            return redirect()->route('admin_users.show', $user)
                ->with('error', 'المستخدم ليس لديه سجل وظيفي نشط لتصحيحه.');
        }

        $units = OrgUnit::all();
        $positions = Position::all(); // Fetch all positions for the dropdown

        return view('admin.user.position.edit', compact('user', 'activeRecord', 'units', 'positions'));
    }

    // In AdminController

    /**
     * Update the current active position record (Correction only).
     */
    public function updateCorrection(Request $request, User $user)
    {
        // Restrict access if necessary (Auth::id() !== 1)
        if (Auth::id() !== 1) {
            // ... (Error handling) ...
        }

        $validated = $request->validate([
            'position_id' => ['required', 'exists:positions,id'],
            'org_unit_id' => ['required', 'exists:org_units,id'],
            'start_date' => ['required', 'date', 'before_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'], // Allow manually setting end_date if needed
        ]);

        $activeRecord = $user->currentHistory;

        if (!$activeRecord) {
            return redirect()->route('admin_users.show', $user)
                ->with('error', 'المستخدم ليس لديه سجل وظيفي نشط لتصحيحه.');
        }

        // Perform the correction update on the existing record
        $activeRecord->update([
            'position_id' => $validated['position_id'],
            'org_unit_id' => $validated['org_unit_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
        ]);

        return redirect()->route('admin_users.show', $user)
            ->with('success', 'تم تصحيح السجل الوظيفي الحالي بنجاح.');
    }
}
