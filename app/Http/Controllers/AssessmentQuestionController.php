<?php

namespace App\Http\Controllers;

use App\Models\AssessmentQuestion; // Make sure to create this model
use Illuminate\Http\Request;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            'type' => 'required|in:range,text',
            'content' => 'required|string|nullable|max:255',
            'max_point' => 'nullable|integer|min:1|max:10', 
        ]);

        // Clean up the data based on type
        if ($validatedData['type'] === 'text') {
            $validatedData['max_point'] = null; // Ensure max_point is null for text questions
        } elseif ($validatedData['type'] === 'range' && empty($validatedData['max_point'])) {
            // Re-validate or add a default if range is selected but max_point is missing
            return back()->withErrors(['max_point' => 'الحد الأقصى للنقاط مطلوب لسؤال المدى.'])->withInput();
        }

        // 2. Create the new AssessmentQuestion record
        $question = AssessmentQuestion::create($validatedData);

        // 3. Redirect with a success message
        return redirect()->route('assessment_questions.index') 
                         ->with('success', 'تم إنشاء سؤال التقييم بنجاح!');
    }
    
    // Add an index method to show existing questions
    public function index()
    {
        $questions = AssessmentQuestion::latest('id')->get();
        return view('assessment_questions.index', compact('questions'));
    }

    public function edit(AssessmentQuestion $question)
    {
        // No need for findOrFail, Laravel handles the 404 for you.
        return view('assessment_questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssessmentQuestion $question)
    {
        // $question is already the model instance we need.

        $validatedData = $request->validate([
            // Add 'sometimes' rule so if the content or type is not sent, it won't fail (though in a full form, they usually are required)
            'type' => 'required|in:range,text', 
            'content' => 'required|string|max:255',
            'max_point' => 'nullable|integer|min:1|max:10', 
        ]);

        // Clean up data based on type (Logic is correct)
        if ($validatedData['type'] === 'text') {
            $validatedData['max_point'] = null;
        } elseif ($validatedData['type'] === 'range' && empty($validatedData['max_point'])) {
            return back()->withErrors(['max_point' => 'يجب تحديد الحد الأقصى للنقاط للسؤال ذي النطاق (Range).'])->withInput();
        }

       $question->update($validatedData);

        return redirect()->route('assessment_questions.index') 
                         ->with('success', 'تم تحديث سؤال التقييم بنجاح!'); // Clearer success message
    }

}