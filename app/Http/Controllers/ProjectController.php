<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\Project;
use App\Models\Sector;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Indirect;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Indicator $indicator)
    {
        $current_year = now()->year;

        $projects = Project::where('indicator_id', $indicator->id)
            ->where('current_year', $current_year)
            ->get();

        return view('project.index', compact('projects', 'indicator','current_year'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Indicator $indicator)
    {
        $sectors = Sector::all();

        return view('project.create', compact('sectors', 'indicator'));
    }


    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255', // Ensure title is unique
            'description' => 'nullable|string',
            'sector_id' => 'required|integer|exists:sectors,id',
            'indicator_id' => 'required|integer|exists:indicators,id',
        ]);

        $validatedData['current_year'] = now()->year;

        // 2. Create the new Project record
        $project = Project::create($validatedData);

        // 3. Redirect to a relevant page with a success message
        // You might change 'project.index' to 'project.show' if you want to view the new project
        return redirect()->route('project.index',$validatedData['indicator_id'])
            ->with('success', 'المشروع: "' . $project->title . '" تم اضافه!');
    }

    public function edit(Project $project)
    {
        // Fetch all sectors to populate the dropdown
        $sectors = Sector::all();

        $indicators = Indicator::all();

        // Pass the project data and sectors to the view
        return view('project.edit', compact('project', 'sectors','indicators'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            // Ensure title is unique, but ignore the current project's title
            'title' => 'required|string|max:255|unique:projects,title,' . $project->id,
            'description' => 'nullable|string',
            // Check that the sector_id exists in the 'sectors' table
            'sector_id' => 'required|integer|exists:sectors,id',
            'indicator_id' => 'required|integer|exists:indicators,id',
            // Note: Add validation for other fields (like department, section, type) 
            // once you include them in the form and database.
        ]);

        // 2. Update the Project record
        $project->update($validatedData);

        // 3. Redirect to a relevant page with a success message
        return redirect()->route('project.index',$request->indicator_id) // Assuming 'project.index' is the list of projects
            ->with('success', 'المشروع: "' . $project->title . '" تم تحديثه بنجاح!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project = Project::with('indicator')->where('id',$project->id)->first();

        $current_year = date('Y');

        return view('project.show', compact('project','current_year'));
    }


    public function taskShow(Project $project)
    {
        return view('project.task.show', compact('project'));
    }
}
