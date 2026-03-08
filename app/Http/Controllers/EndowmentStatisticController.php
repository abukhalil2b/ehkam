<?php

namespace App\Http\Controllers;

use App\Models\Endowment;
use App\Models\EndowmentStatistic;
use Illuminate\Http\Request;

class EndowmentStatisticController extends Controller
{
    // عرض جميع إحصائيات مؤسسة وقفية معينة
    public function index(Endowment $endowment)
    {
        $statistics = $endowment->statistics()->orderBy('year', 'desc')->paginate(15);
        return view('statistic.endowment_statistic.index', compact('endowment', 'statistics'));
    }

    // صفحة إضافة إحصائية لسنة جديدة
    public function create(Endowment $endowment)
    {
        return view('statistic.endowment_statistic.create', compact('endowment'));
    }

    // حفظ الإحصائية
    public function store(Request $request, Endowment $endowment)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'employees_count' => 'required|integer|min:0',
            'revenues' => 'required|numeric|min:0', // numeric يقبل الكسور العشرية للمبالغ
            'expenses' => 'required|numeric|min:0',
        ]);

        // استخدمنا updateOrCreate لمنع تكرار إحصائية لنفس المؤسسة في نفس السنة
        $endowment->statistics()->updateOrCreate(
            ['year' => $validated['year']],
            [
                'employees_count' => $validated['employees_count'],
                'revenues' => $validated['revenues'],
                'expenses' => $validated['expenses'],
            ]
        );

        return redirect()->route('endowments.statistics.index', $endowment->id)
            ->with('success', 'تم حفظ الإحصائيات المالية والإدارية بنجاح.');
    }
}
