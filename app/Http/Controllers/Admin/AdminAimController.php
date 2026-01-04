<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aim;
use Illuminate\Http\Request;


class AdminAimController extends Controller
{
    public function index()
    {
        $aims = Aim::latest()->get();
        return view('admin.aim.index', compact('aims'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Aim::create([
            'title' => $request->title,
        ]);

        return redirect()
            ->route('admin.aim.index')
            ->with('success', 'تم إضافة الهدف بنجاح');
    }

    public function edit(Aim $aim)
    {
        return view('admin.aim.edit', compact('aim'));
    }

    public function update(Request $request, Aim $aim)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $aim->update([
            'title' => $request->title,
        ]);

        return redirect()
            ->route('admin.aim.index')
            ->with('success', 'تم تحديث الهدف بنجاح');
    }

    public function destroy(Aim $aim)
    {
        $aim->delete();

        return redirect()
            ->route('admin.aim.index')
            ->with('success', 'تم حذف الهدف بنجاح');
    }
}

