<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{

    public function create()
    {
        $loggedUser = auth()->user();

        if (!in_array($loggedUser->user_type, ['admin', 'super_admin'])) {
            abort(403, 'لاتملك الصلاحية');
        }

        $roles = Role::latest('id')->get();


        return view('admin.user.create', compact('roles', 'groups'));
    }

    public function checkCivilId(Request $request)
    {
        $civilId = $request->input('civil_id');

        if (!preg_match('/^\d{6,10}$/', $civilId)) {
            return response()->json([
                'status' => 'invalid_format',
                'message' => 'نموذج البطاقة المدنية غير صالح.'
            ], 422);
        }

        if (User::where('civil_id', $civilId)->exists()) {
            return response()->json([
                'status' => 'exists',
                'message' => 'الرقم المدني موجود ولايمكن إنشاء حساب جديد بهذا الرقم. ابحث عنه في المتدربين'
            ], 409);
        }

        return response()->json([
            'status' => 'available',
            'message' => 'التسجيل متاح.'
        ], 200);
    }


    public function store(Request $request)
    {
        $loggedUser = auth()->user();

        if (!in_array($loggedUser->user_type, ['admin', 'super_admin'])) {
            abort(403, 'لاتملك الصلاحية');
        }

        $profile = Role::findOrFail($request->profile_id);

        // Abort if the profile's title is not 'trainer' or 'trainee'
        if (!in_array($profile->title, ['trainer', 'trainee'])) {
            abort(403, 'يسمح فقط بإضافة المدرب والمتدرب');
        }

        $request->validate([
            'civil_id' => [
                'required',
                'regex:/^\d{6,10}$/',
                'unique:users,civil_id',
            ],
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:20',
            'last_name' => 'nullable|string|max:20',
            'about' => 'nullable|string',
            'phone' => 'nullable|digits:8',
            'date_of_birth' => 'nullable|date',
            'groups' => 'array', // expect multiple group ids
            'groups.*' => 'exists:groups,id',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'civil_id' => $request->civil_id,
                'name' => $request->name,
                'password' => Hash::make($request->civil_id),
                'user_type' => $profile->title,
                'phone' => $request->phone,
                'plain_password' => $request->civil_id,
            ]);

            // Link Role (using new role_user pivot)
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $profile->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ✅ Attach groups if selected
            if ($request->filled('groups')) {
                $user->groups()->attach($request->groups);
            }

            DB::commit();
            return redirect()->route('admin.user.index', $user->user_type)->with('status', 'تم إنشاء الحساب بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الحساب.')->withInput();
        }
    }

    public function show(User $user)
    {
        // Roles already linked to the user
        $hisroles = $user->roles;
        $hisProfileIds = $hisroles->pluck('id');

        // Available roles (not linked, excluding "admin")
        $availableroles = Role::query()
            ->whereNotIn('id', $hisProfileIds)
            ->whereNot('title', 'admin')
            ->get();

        // Groups already linked to the user
        $hisGroups = $user->groups;

        // Available groups (not yet assigned)
        $availableGroups = Group::whereNotIn('id', $hisGroups->pluck('id'))->get();

        return view('admin.user.show', compact(
            'user',
            'hisroles',
            'availableroles',
            'hisGroups',
            'availableGroups',
        ));
    }



    public function index($profileTitle = 'trainee')
    {
        $loggedUser = auth()->user();

        $profile = Role::whereTitle($profileTitle)->first();

        if (!$profile) {
            abort(403);
        }

        // Get the search query from the request
        $search = request('search');

        // Start a query on users related to the role
        $usersQuery = User::whereHas('roles', function ($query) use ($profile) {
            $query->where('role_user.role_id', $profile->id);
        });

        // If a search term is provided, apply the search filter
        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('civil_id', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Paginate the results with the search filter applied
        $users = $usersQuery->paginate(100);

        return view('admin.user.index', compact('users', 'profileTitle'));
    }

    public function edit(User $user)
    {
        // Retrieve all available groups
        $groups = Group::all();

        // Eager load the user's groups to prevent N+1 query issues
        $user->load('groups');


        return view('admin.user.edit', compact('user', 'groups'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:20',
            'last_name' => 'nullable|string|max:20',
            'about' => 'nullable|string',
            'phone' => 'nullable|digits:8',
            'date_of_birth' => 'nullable|date',
            // Add validation for the `groups` input
            'groups' => 'nullable|array',
            'groups.*' => 'integer|exists:groups,id',
        ]);

        // Update user information
        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        // Use the `sync` method to attach/detach groups
        $user->groups()->sync($request->input('groups', []));

        return redirect()->route('admin.user.index', $user->user_type)->with('success', 'تم تحديث الملف.');
    }

    public function passwordEdit(User $user)
    {
        return view('admin.user.password.edit', compact('user'));
    }

    public function passwordUpdate(Request $request, User $user)
    {
        $loggedUser = auth()->user();

        // Only admin or super_admin can reset passwords
        if (!in_array($loggedUser->user_type, ['admin', 'super_admin'])) {
            abort(403, 'لاتملك الصلاحية');
        }

        // Prevent admin from resetting super_admin password
        if ($loggedUser->user_type !== 'super_admin' && $user->user_type === 'super_admin') {
            abort(403, 'لاتملك الصلاحية');
        }

        // Validate password input
        $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'plain_password' => $request->password
        ]);

        return redirect()
            ->route('admin.user.index', $user->user_type)
            ->with('success', 'تم تحديث كلمة المرور بنجاح');
    }

    public function deleteForm(User $user)
    {
        return view('admin.user.user_delete_form');
    }

    // Add permission to a user
    public function addPermission(User $user, Permission $permission)
    {
        // Prevent duplicates
        if (!$user->permissions()->where('permissions.id', $permission->id)->exists()) {
            $user->permissions()->attach($permission->id);
        }

        return back()->with('success', 'تمت إضافة الصلاحية للمستخدم');
    }

    // Remove permission from a user
    public function removePermission(User $user, Permission $permission)
    {
        $user->permissions()->detach($permission->id);

        return back()->with('success', 'تمت إزالة الصلاحية من المستخدم');
    }
}
