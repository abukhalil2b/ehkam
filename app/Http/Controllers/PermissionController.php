<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('title')->get();
        // You'll need a blade view at resources/views/permission/index.blade.php
        return view('permission.index', compact('permissions'));
    }
}
