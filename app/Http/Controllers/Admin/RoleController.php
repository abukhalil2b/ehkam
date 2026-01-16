<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * RoleController - Manages RBAC Roles
 * 
 * Roles are assigned to users and grant permissions.
 */
class RoleController extends Controller
{
    /**
     * Display a listing of all roles.
     */
    public function index()
    {
        $roles = Role::withCount(['permissions'])->get();
        return view('admin.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:50|unique:roles,title',
            'slug' => 'nullable|string|max:50|unique:roles,slug',
            'description' => 'nullable|string|max:255',
        ]);

        Role::create([
            'title' => $request->title,
            'slug' => $request->slug ?? Str::slug($request->title, '_'),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'تم إنشاء الدور بنجاح');
    }
}
