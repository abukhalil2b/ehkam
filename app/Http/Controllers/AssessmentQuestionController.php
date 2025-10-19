<?php

namespace App\Http\Controllers;

use App\Models\AssessmentQuestion; // Make sure to create this model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssessmentQuestionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('assessment_questions.create');
    }

    public function store(Request $request)
    {
        // Validate inputs
        $validatedData = $request->validate([
            'type' => 'required|in:range,text',
            'content' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_point' => 'nullable|integer|min:1|max:20',
            'assessment_year' => 'required|digits:4',
        ]);

        // Adjust max_point logic
        if ($validatedData['type'] === 'text') {
            $validatedData['max_point'] = null;
        } elseif ($validatedData['type'] === 'range' && empty($validatedData['max_point'])) {
            return back()->withErrors(['max_point' => 'الحد الأقصى للنقاط مطلوب لسؤال المدى.'])->withInput();
        }

        // Determine order
        $maxOrder = AssessmentQuestion::where('assessment_year', $validatedData['assessment_year'])->max('ordered');
        $validatedData['ordered'] = ($maxOrder !== null) ? $maxOrder + 1 : 1;

        // Create
        AssessmentQuestion::create($validatedData);

        // Redirect
        return redirect()->route('assessment_questions.index')
            ->with('success', 'تم إنشاء سؤال التقييم بنجاح للسنة ' . $validatedData['assessment_year'] . '!');
    }

    public function index()
    {
        // 1. Determine the boundaries for the current year
        $currentYear = now()->year;
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        // 2. Fetch questions created within the current year, sorted by the 'ordered' column
        // IMPORTANT: Change 'created_at' if your table uses a different date column for the year filter.
        $questions = AssessmentQuestion::whereBetween('created_at', [$startOfYear, $endOfYear])
            ->orderBy('ordered')
            ->get();

        // 3. Pass the questions and the current year to the view
        return view('assessment_questions.index', compact('questions', 'currentYear'));
    }

    public function edit(AssessmentQuestion $question)
    {
        // No need for findOrFail, Laravel handles the 404 for you.
        return view('assessment_questions.edit', compact('question'));
    }


    public function update(Request $request, AssessmentQuestion $question)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:range,text',
            'content' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_point' => 'nullable|integer|min:1|max:20',
        ]);

        // Clean up data based on type (Logic is correct)
        if ($validatedData['type'] === 'text') {
            $validatedData['max_point'] = null;
        } elseif ($validatedData['type'] === 'range' && empty($validatedData['max_point'])) {
            return back()->withErrors(['max_point' => 'يجب تحديد الحد الأقصى للنقاط للسؤال ذي النطاق (Range).'])->withInput();
        }

        $question->update($validatedData);

        return redirect()->route('assessment_questions.index')
            ->with('success', 'تم تحديث سؤال التقييم بنجاح!');
    }

    public function updateOrdered(Request $request)
    {
        // 1. Validate the incoming IDs array (as a comma-separated string from the frontend)
        $request->validate([
            'orderIds' => 'required|string',
        ]);

        // Convert comma-separated string to an array of integers
        $orderIds = array_map('intval', explode(',', $request->input('orderIds')));

        if (empty($orderIds)) {
            return response()->json(['message' => 'No IDs provided for reordering.'], 400);
        }

        // 2. Begin transaction for database safety
        DB::beginTransaction();

        try {
            // 3. Iterate over the provided IDs and update their 'ordered' column
            foreach ($orderIds as $index => $id) {
                // $index starts at 0, so the new order will be 1, 2, 3...
                $newOrder = $index + 1;

                // Update the question's 'ordered' field
                AssessmentQuestion::where('id', $id)->update(['ordered' => $newOrder]);
            }

            DB::commit();

            return response()->json(['message' => 'تم تحديث الترتيب بنجاح.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Assessment question reorder failed: ' . $e->getMessage());

            return response()->json(['message' => 'فشل في تحديث الترتيب.', 'error' => $e->getMessage()], 500);
        }
    }
}
