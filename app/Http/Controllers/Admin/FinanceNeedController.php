<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceNeed;
use Illuminate\Http\Request;

class FinanceNeedController extends Controller
{
    public function index()
    {
        $needs = FinanceNeed::orderBy('id', 'desc')->get();

        return view('admin.finance_need.index', compact('needs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        FinanceNeed::create($request->only(['name', 'category', 'description']));

        return redirect()->route('admin.finance_need.index')
            ->with('success', 'تم إضافة الاحتياج بنجاح');
    }
}
