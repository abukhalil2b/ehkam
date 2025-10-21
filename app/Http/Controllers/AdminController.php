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

class AdminController extends Controller
{

    // 1. Show the assignment form
    public function editUserPermissions(User $user)
    {
        $allProfiles = Profile::all();
        $allPermissions = Permission::all();

        // Get currently assigned data
        $assignedProfileIds = $user->profiles->pluck('id')->toArray();
        $assignedPermissionIds = $user->permissions->pluck('id')->toArray();

        return view('admin.user_permissions_edit', compact(
            'user',
            'allProfiles',
            'allPermissions',
            'assignedProfileIds',
            'assignedPermissionIds'
        ));
    }

    // 2. Process the assignment form submission
    public function updateUserPermissions(Request $request, User $user)
    {
        $request->validate([
            'profiles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        // Sync Profiles (This replaces all current profiles with the new list)
        // If the user can only have ONE profile, use $user->profiles()->sync((array)$request->profiles);
        // If they can have multiple, this is fine:
        $user->profiles()->sync((array)$request->input('profiles'));

        // Sync Direct Permissions (This replaces all current direct permissions with the new list)
        $user->syncPermissions((array)$request->input('permissions'));

        return redirect()->back()->with('success', 'تم تحديث الصلاحيات');
    }

    public function createUser()
    {
        $organizationalUnits = OrganizationalUnit::all();
        $allPositions = Position::all();

        // FIX: Add $topLevelUnits and $topLevelPositions 
        // to satisfy components or inherited layout logic.
        $topLevelUnits = OrganizationalUnit::whereNull('parent_id')->get();
        $topLevelPositions = Position::whereNull('reports_to_position_id')->get();

        $users = User::with([
            'positionHistory',
            'currentHistory.position',
            'currentHistory.organizationalUnit'
        ])->get();

        // Pass ALL required variables to the view
        return view('admin_users.create', compact(
            'organizationalUnits',
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
            'password' => 'required|string|min:8|confirmed',

            // New assignment fields
            'position_id' => 'required|exists:positions,id',
            'organizational_unit_id' => 'required|exists:organizational_units,id',
            'start_date' => 'required|date',
        ]);

        // 2. User Creation
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            // Note: Additional user fields go here if needed
        ]);

        // 3. Initial Position Assignment (UserPositionHistory creation)
        UserPositionHistory::create([
            'user_id' => $user->id,
            'position_id' => $validatedData['position_id'],
            'organizational_unit_id' => $validatedData['organizational_unit_id'],
            'start_date' => $validatedData['start_date'],
            'end_date' => null, // This is the current assignment
        ]);

        return redirect()->route('admin_structure.index')
            ->with('success', "تم إنشاء المستخدم الجديد ({$user->name}) بنجاح.");
    }

    public function indexUsers()
    {
        // This now correctly calls the newly defined latestHistory() method.
        $users = User::with(['profiles', 'latestHistory.position'])
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('admin.user.index', compact('users'));
    }

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

        return view('admin_structure.index', [
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

    /**
     * تخزين مسمى وظيفي جديد في قاعدة البيانات.
     */
    public function storePosition(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:positions,title',
            'reports_to_position_id' => 'nullable|exists:positions,id',
        ]);

        $position = Position::create($request->only(['title', 'reports_to_position_id']));

        return redirect()->route('admin_structure.index')->with('success', "تمت إضافة المسمى الوظيفي ({$position->title}) بنجاح.");
    }

    /**
     * تعيين/ترقية موظف عن طريق تحديث السجل القديم وإنشاء سجل جديد.
     */
    public function assignUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'new_position_id' => 'required|exists:positions,id',
            'new_unit_id' => 'nullable|exists:organizational_units,id',
            'start_date' => 'required|date',
        ]);

        // 1. إغلاق السجل الوظيفي القديم للموظف (إذا وُجد)
        UserPositionHistory::where('user_id', $validated['user_id'])
            ->whereNull('end_date')
            ->update(['end_date' => Carbon::parse($validated['start_date'])->subDay()->format('Y-m-d')]);

        // 2. إنشاء سجل وظيفي جديد (التعيين الحالي)
        UserPositionHistory::create([
            'user_id' => $validated['user_id'],
            'position_id' => $validated['new_position_id'],
            'organizational_unit_id' => $validated['new_unit_id'],
            'start_date' => $validated['start_date'],
            'end_date' => null,
        ]);

        return redirect()->route('admin_structure.index')->with('success', 'تم تسجيل التعيين/الترقية بنجاح.');
    }

    public function getPositionsByUnit(Request $request)
    {
        $unitId = $request->get('unit_id');

        // البحث عن الوحدة التنظيمية باستخدام المعرّف
        $unit = OrganizationalUnit::find($unitId);

        // إذا لم يتم العثور على الوحدة، نرجع قائمة فارغة
        if (!$unit) {
            return response()->json([]);
        }

        // استخدام علاقة positions() المحددة في نموذج OrganizationalUnit
        // لجلب المسميات الوظيفية المرتبطة بهذه الوحدة عبر جدول الربط.
        $positions = $unit->positions()
            ->get(['positions.id', 'positions.title']);

        return response()->json($positions);
    }

    public function showUser(User $user)
    {
        // Load relationships for the view
        $user->load([
            'profiles',
            'permissions',
            'positionHistory.position',        // full history with position
            'positionHistory.organizationalUnit'
        ]);

        // Optional: Get latest active history
        $latestHistory = $user->positionHistory()
            ->whereNull('end_date')
            ->latest('start_date')
            ->first();

        // Load all profiles and permissions for selection forms
        $allProfiles = Profile::orderBy('title')->get();
        $allPermissions = Permission::orderBy('title')->get();

        $positions = Position::orderBy('title')->get();
        $units = OrganizationalUnit::orderBy('name')->get();

        return view('admin.user.show', compact(
            'user',
            'latestHistory',
            'allProfiles',
            'allPermissions',
            'positions',
            'units'
        ));
    }


    public function editPosition(Position $position)
    {
        // Fetch all positions to populate the 'reports_to' dropdown, 
        // excluding the current position itself to prevent self-reporting loops.
        $allPositions = Position::where('id', '!=', $position->id)->get();

        return view('admin_structure.positions.edit', compact('position', 'allPositions'));
    }

    public function updatePosition(Request $request, User $user)
    {
        // Restrict access if necessary (Auth::id() !== 1)
        if (Auth::id() !== 1) {
            // ... (Error handling) ...
        }

        $validated = $request->validate([
            'position_id' => ['required', 'exists:positions,id'],
            'organizational_unit_id' => ['required', 'exists:organizational_units,id'], // Made unit required for new assignment
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
            'organizational_unit_id' => $validated['organizational_unit_id'],
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

        $units = OrganizationalUnit::all();
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
            'organizational_unit_id' => ['required', 'exists:organizational_units,id'],
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
            'organizational_unit_id' => $validated['organizational_unit_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
        ]);

        return redirect()->route('admin_users.show', $user)
            ->with('success', 'تم تصحيح السجل الوظيفي الحالي بنجاح.');
    }
}
