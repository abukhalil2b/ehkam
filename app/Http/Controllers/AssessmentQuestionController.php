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
        // This just loads the form view. No data fetching needed yet.
        return view('assessment_questions.create');
    }


    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            'type' => 'required|in:range,text',
            'content' => 'required|string|nullable|max:255',
            'description' => 'nullable|string',
            'max_point' => 'nullable|integer|min:1|max:20',
        ]);

        // Clean up the data based on type
        if ($validatedData['type'] === 'text') {
            $validatedData['max_point'] = null;
        } elseif ($validatedData['type'] === 'range' && empty($validatedData['max_point'])) {
            return back()->withErrors(['max_point' => 'الحد الأقصى للنقاط مطلوب لسؤال المدى.'])->withInput();
        }

        // 2. Determine the next order value and add it to validated data
        // The new question should be placed at the end of the list
        $maxOrder = AssessmentQuestion::max('ordered');
        $validatedData['ordered'] = ($maxOrder !== null) ? $maxOrder + 1 : 1;

        // 3. Create the new AssessmentQuestion record
        AssessmentQuestion::create($validatedData);

        // 4. Redirect with a success message
        return redirect()->route('assessment_questions.index')
            ->with('success', 'تم إنشاء سؤال التقييم بنجاح!');
    }

    public function index()
    {
        // Fetch all questions, sorted by the 'ordered' column for correct display order
        $questions = AssessmentQuestion::orderBy('ordered')->get();

        return view('assessment_questions.index', compact('questions'));
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
