<?php

namespace App\Http\Controllers;

use App\Models\KpiYear;
use Illuminate\Http\Request;

class KpiYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years = KpiYear::ordered()->get();
        return view('admin.kpi-years.index', compact('years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kpi-years.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|unique:kpi_years,year',
            'name' => 'required|string|max:100',
        ]);

        $maxOrder = KpiYear::max('display_order') ?? 0;

        KpiYear::create([
            'year' => $request->year,
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
            'display_order' => $maxOrder + 1,
        ]);

        return redirect()->route('kpi-years.index')
            ->with('success', 'تم إضافة السنة بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KpiYear $kpiYear)
    {
        return view('admin.kpi-years.form', compact('kpiYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KpiYear $kpiYear)
    {
        $request->validate([
            'year' => 'required|integer|unique:kpi_years,year,' . $kpiYear->id,
            'name' => 'required|string|max:100',
        ]);

        $kpiYear->update([
            'year' => $request->year,
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('kpi-years.index')
            ->with('success', 'تم تحديث السنة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KpiYear $kpiYear)
    {
        $kpiYear->delete();
        return redirect()->route('kpi-years.index')
            ->with('success', 'تم حذف السنة بنجاح');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(KpiYear $kpiYear)
    {
        $kpiYear->toggleActive();
        return back()->with('success', 'تم تغيير الحالة بنجاح');
    }

    /**
     * Move year up in order.
     */
    public function moveUp(KpiYear $kpiYear)
    {
        // Get the previous item in order
        $previousYear = KpiYear::where('display_order', '<', $kpiYear->display_order)
            ->orderBy('display_order', 'desc')
            ->first();

        if ($previousYear) {
            // Swap display_order values
            $tempOrder = $kpiYear->display_order;
            $kpiYear->update(['display_order' => $previousYear->display_order]);
            $previousYear->update(['display_order' => $tempOrder]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Move year down in order.
     */
    public function moveDown(KpiYear $kpiYear)
    {
        // Get the next item in order
        $nextYear = KpiYear::where('display_order', '>', $kpiYear->display_order)
            ->orderBy('display_order', 'asc')
            ->first();

        if ($nextYear) {
            // Swap display_order values
            $tempOrder = $kpiYear->display_order;
            $kpiYear->update(['display_order' => $nextYear->display_order]);
            $nextYear->update(['display_order' => $tempOrder]);
        }

        return response()->json(['success' => true]);
    }
}
