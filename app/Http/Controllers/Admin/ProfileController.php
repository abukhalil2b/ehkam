<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = Profile::withCount(['permissions'])->get();
        return view('admin.profile.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.profile.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255|unique:profiles,title']);
        Profile::create($request->only('title'));
        return redirect()->route('admin.profiles.index')->with('success', 'تم إنشاء الملف الشخصي بنجاح');
    }

    // Edit permissions is handled by AdminProfilePermissionController
}
