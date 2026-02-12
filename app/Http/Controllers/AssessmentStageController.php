<?php

namespace App\Http\Controllers;

use App\Models\AssessmentStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentStageController extends Controller
{
    public function index()
    {
        // جلب المراحل مع عدد النتائج المرتبطة بكل منها
        $stages = AssessmentStage::withCount('assessmentResults as results_count')->get();

        return view('assessment_stages.index', compact('stages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        AssessmentStage::create($request->all());

        return redirect()->route('assessment_stages.index')->with('success', 'تمت إضافة المرحلة بنجاح');
    }

    public function update(Request $request, AssessmentStage $assessmentStage)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $assessmentStage->update($request->all());

        return redirect()->route('assessment_stages.index')->with('success', 'تم تحديث المرحلة بنجاح');
    }

    public function destroy(AssessmentStage $assessmentStage)
    {
        try {
            DB::transaction(function () use ($assessmentStage) {
                // حذف كافة النتائج المرتبطة بهذه المرحلة أولاً
                $assessmentStage->assessmentResults()->delete();

                // ثم حذف المرحلة نفسها
                $assessmentStage->delete();
            });

            return redirect()->route('assessment_stages.index')
                ->with('success', 'تم حذف المرحلة وكافة التقييمات المرتبطة بها بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('assessment_stages.index')
                ->with('error', 'حدث خطأ أثناء محاولة الحذف.');
        }
    }
}
