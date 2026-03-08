<?php
namespace App\Http\Controllers;

use App\Models\QuranSchoolStatistic;
use App\Models\Governorate;
use Illuminate\Http\Request;

class QuranSchoolStatisticController extends Controller
{
    public function index()
    {
        $quranSchools = QuranSchoolStatistic::with(['governorate', 'wilayat'])
            ->orderBy('year', 'desc')
            ->paginate(15);

        return view('statistic.quran_school.index', compact('quranSchools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // جلب المحافظات مع ولاياتها لاستخدامها في القوائم المنسدلة المرتبطة (Alpine.js)
        $governorates = Governorate::with('wilayats')->get();
        return view('statistic.quran_school.create', compact('governorates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validated = $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'wilayat_id' => 'nullable|exists:wilayats,id',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'schools_count' => 'required|integer|min:0',
            'students_male' => 'required|integer|min:0',
            'students_female' => 'required|integer|min:0',
            'teachers_male' => 'required|integer|min:0',
            'teachers_female' => 'required|integer|min:0',
        ]);

        // الحفظ مع منع التكرار لنفس المنطقة والسنة
        QuranSchoolStatistic::updateOrCreate(
            [
                'governorate_id' => $validated['governorate_id'],
                'wilayat_id' => $validated['wilayat_id'],
                'year' => $validated['year'],
            ],
            $validated
        );

        // التوجيه مع رسالة نجاح
        return redirect()->route('quran-schools.index')
            ->with('success', 'تم حفظ إحصائيات مدارس القرآن الكريم بنجاح');
    }
}