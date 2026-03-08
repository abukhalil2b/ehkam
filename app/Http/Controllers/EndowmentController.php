<?php

namespace App\Http\Controllers;

use App\Models\Endowment;
use App\Models\Governorate;
use Illuminate\Http\Request;

class EndowmentController extends Controller
{
    public function index()
    {
        // جلب المؤسسات الوقفية مع المحافظة التي تنتمي إليها
        $endowments = Endowment::with('governorate')->paginate(15);

        return view('statistic.endowment.index', compact('endowments'));
    }

    public function create()
    {
        // جلب المحافظات لعرضها في قائمة الاختيار
        $governorates = Governorate::all();
        return view('statistic.endowment.create', compact('governorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:endowments,name',
            'type' => 'required|in:عامة,خاصة',
            'governorate_id' => 'required|exists:governorates,id',
        ], [
            'name.unique' => 'اسم المؤسسة الوقفية مسجل مسبقاً.'
        ]);

        Endowment::create($validated);

        return redirect()->route('endowments.index')
            ->with('success', 'تم تسجيل المؤسسة الوقفية بنجاح.');
    }
    
    // يمكنك لاحقاً إضافة دوال edit و update و destroy بنفس النمط
}